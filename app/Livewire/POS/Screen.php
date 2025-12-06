<?php

namespace App\Livewire\Pos;

use App\Models\Branch;
use App\Models\BranchProduct;
use App\Models\CashierShift;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.pos')]
class Screen extends Component
{
    /** @var array<int, array{id: int|null, branch_product_id: int|null, name: string, sku: string, qty: int, price: int, total: int, stock: int}> */
    public array $cart = [];

    public string $barcodeInput = '';
    public string $productSearch = '';
    public string $categoryFilter = 'all';

    public ?array $scannedProduct = null;

    public function mount(): void
    {
        // Initialize empty cart - products will be loaded from branch inventory
        $this->cart = [];
    }

    public function searchByBarcode(): void
    {
        if (empty($this->barcodeInput)) {
            return;
        }

        $branchId = (int) session('active_branch_id', 0);

        if ($branchId <= 0) {
            $this->dispatch('pos-no-branch');
            return;
        }

        // Search in branch products first
        $branchProduct = BranchProduct::with('product')
            ->where('branch_id', $branchId)
            ->where('is_available', true)
            ->whereHas('product', function ($query) {
                $query->where('barcode', $this->barcodeInput)
                    ->orWhere('sku', $this->barcodeInput);
            })
            ->first();

        if ($branchProduct && $branchProduct->product) {
            $product = $branchProduct->product;
            $effectivePrice = (int) ($branchProduct->selling_price ?? $product->selling_price ?? 0);

            $this->scannedProduct = [
                'id' => $product->id,
                'branch_product_id' => $branchProduct->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'barcode' => $product->barcode,
                'price' => $effectivePrice,
                'image' => $product->image_path,
                'stock' => $branchProduct->stock_quantity ?? 0,
            ];

            // Check stock before adding
            if ($branchProduct->stock_quantity <= 0) {
                $this->dispatch('pos-out-of-stock', name: $product->name);
            } else {
                $this->addToCart(
                    $product->id,
                    $branchProduct->id,
                    $product->name,
                    $product->sku,
                    $effectivePrice,
                    $branchProduct->stock_quantity
                );
                $this->dispatch('pos-product-scanned');
            }
        } else {
            $this->scannedProduct = null;
            $this->dispatch('pos-product-not-found');
        }

        $this->barcodeInput = '';
    }

    public function addProductToCart(int $branchProductId): void
    {
        $branchId = (int) session('active_branch_id', 0);

        $branchProduct = BranchProduct::with('product')
            ->where('id', $branchProductId)
            ->where('branch_id', $branchId)
            ->where('is_available', true)
            ->first();

        if (!$branchProduct || !$branchProduct->product) {
            return;
        }

        $product = $branchProduct->product;
        $effectivePrice = (int) ($branchProduct->selling_price ?? $product->selling_price ?? 0);

        // Check stock
        $currentQtyInCart = collect($this->cart)
            ->where('branch_product_id', $branchProductId)
            ->sum('qty');

        if ($currentQtyInCart >= $branchProduct->stock_quantity) {
            $this->dispatch('pos-insufficient-stock', name: $product->name, available: $branchProduct->stock_quantity);
            return;
        }

        $this->addToCart(
            $product->id,
            $branchProduct->id,
            $product->name,
            $product->sku,
            $effectivePrice,
            $branchProduct->stock_quantity
        );

        $this->scannedProduct = [
            'id' => $product->id,
            'branch_product_id' => $branchProduct->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'barcode' => $product->barcode,
            'price' => $effectivePrice,
            'image' => $product->image_path,
            'stock' => $branchProduct->stock_quantity,
        ];

        $this->dispatch('pos-product-added');
    }

    public function addToCart(int $productId, int $branchProductId, string $name, string $sku, int $price, int $stock): void
    {
        $existingIndex = collect($this->cart)->search(fn ($item) => $item['branch_product_id'] === $branchProductId);

        if ($existingIndex !== false) {
            $newQty = $this->cart[$existingIndex]['qty'] + 1;

            // Check stock limit
            if ($newQty > $stock) {
                $this->dispatch('pos-insufficient-stock', name: $name, available: $stock);
                return;
            }

            $this->cart[$existingIndex]['qty'] = $newQty;
            $this->cart[$existingIndex]['total'] = $newQty * $this->cart[$existingIndex]['price'];
        } else {
            $this->cart[] = [
                'id' => $productId,
                'branch_product_id' => $branchProductId,
                'name' => $name,
                'sku' => $sku,
                'qty' => 1,
                'price' => $price,
                'total' => $price,
                'stock' => $stock,
            ];
        }
    }

    public function updateCartQty(int $index, int $qty): void
    {
        if ($qty <= 0) {
            $this->removeFromCart($index);
            return;
        }

        if (isset($this->cart[$index])) {
            $stock = $this->cart[$index]['stock'] ?? PHP_INT_MAX;

            if ($qty > $stock) {
                $this->dispatch('pos-insufficient-stock', name: $this->cart[$index]['name'], available: $stock);
                $qty = $stock;
            }

            $this->cart[$index]['qty'] = $qty;
            $this->cart[$index]['total'] = $qty * $this->cart[$index]['price'];
        }
    }

    public function removeFromCart(int $index): void
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
    }

    public function clearCart(): void
    {
        $this->cart = [];
        $this->scannedProduct = null;
    }

    public function getCartTotals(): array
    {
        $subtotal = collect($this->cart)->sum('total');
        $discount = 0; // Can be calculated based on price lists/promos
        $taxableAmount = $subtotal - $discount;
        $tax = (int) round($taxableAmount * 0.11); // 11% PPN
        $total = $taxableAmount + $tax;

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'total' => $total,
        ];
    }

    public function completeCheckout(string $paymentMethodLabel, int $paidAmount): void
    {
        $user = Auth::user();

        if (! $user || empty($this->cart)) {
            return;
        }

        $branchId = (int) session('active_branch_id', 0);

        if ($branchId <= 0) {
            return;
        }

        $shift = CashierShift::where('branch_id', $branchId)
            ->where('cashier_id', $user->id)
            ->where('status', 'open')
            ->first();

        if (! $shift) {
            $this->dispatch('pos-no-shift');
            return;
        }

        $totals = $this->getCartTotals();
        $subtotal = $totals['subtotal'];
        $discount = $totals['discount'];
        $tax = $totals['tax'];
        $total = $totals['total'];

        if ($paidAmount < $total) {
            return;
        }

        $paymentMethod = $this->normalizePaymentMethod($paymentMethodLabel);
        $change = max(0, $paidAmount - $total);
        $cartItems = $this->cart;

        DB::transaction(function () use ($branchId, $user, $shift, $paymentMethod, $subtotal, $discount, $tax, $total, $paidAmount, $change, $cartItems) {
            $transaction = Transaction::create([
                'transaction_code' => $this->generateTransactionCode(),
                'branch_id' => $branchId,
                'cashier_id' => $user->id,
                'shift_id' => $shift->id,
                'type' => 'sale',
                'status' => 'completed',
                'payment_method' => $paymentMethod,
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'tax_amount' => $tax,
                'total_amount' => $total,
                'paid_amount' => $paidAmount,
                'change_amount' => $change,
                'completed_at' => now(),
            ]);

            foreach ($cartItems as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'] ?? null,
                    'product_name' => $item['name'],
                    'product_sku' => $item['sku'],
                    'unit_price' => $item['price'],
                    'quantity' => $item['qty'],
                    'discount_amount' => 0,
                    'subtotal' => $item['total'],
                ]);

                // Deduct stock from branch inventory
                if (isset($item['branch_product_id'])) {
                    BranchProduct::where('id', $item['branch_product_id'])
                        ->decrement('stock_quantity', $item['qty']);
                }
            }

            $shift->increment('total_transactions');
            $shift->update([
                'total_sales' => $shift->total_sales + $total,
            ]);
        });

        $this->cart = [];
        $this->scannedProduct = null;
        $this->dispatch('pos-checkout-complete');
    }

    private function normalizePaymentMethod(string $label): string
    {
        $value = strtolower($label);

        if (str_contains($value, 'qris')) {
            return 'qris';
        }

        if (str_contains($value, 'debit') || str_contains($value, 'card')) {
            return 'card';
        }

        if (str_contains($value, 'transfer')) {
            return 'transfer';
        }

        if (str_contains($value, 'mixed')) {
            return 'mixed';
        }

        return 'cash';
    }

    private function generateTransactionCode(): string
    {
        return 'POS-' . now()->format('YmdHis') . '-' . random_int(100, 999);
    }

    public function render(): View
    {
        $branchId = (int) session('active_branch_id', 0);
        $activeBranch = $branchId ? Branch::find($branchId) : null;
        $user = Auth::user();

        // Get current shift info
        $currentShift = null;
        if ($user && $branchId) {
            $currentShift = CashierShift::where('branch_id', $branchId)
                ->where('cashier_id', $user->id)
                ->where('status', 'open')
                ->first();
        }

        // Get branch products for catalog
        $catalogQuery = BranchProduct::with(['product.category'])
            ->where('branch_id', $branchId)
            ->where('is_available', true)
            ->where('stock_quantity', '>', 0);

        if ($this->productSearch !== '') {
            $catalogQuery->whereHas('product', function ($q) {
                $q->where('name', 'ilike', '%' . $this->productSearch . '%')
                    ->orWhere('sku', 'ilike', '%' . $this->productSearch . '%')
                    ->orWhere('barcode', 'ilike', '%' . $this->productSearch . '%');
            });
        }

        if ($this->categoryFilter !== 'all') {
            $catalogQuery->whereHas('product', function ($q) {
                $q->where('category_id', (int) $this->categoryFilter);
            });
        }

        $catalogProducts = $catalogQuery
            ->orderBy('is_featured', 'desc')
            ->limit(24)
            ->get()
            ->map(function ($bp) {
                return [
                    'id' => $bp->id,
                    'product_id' => $bp->product_id,
                    'name' => $bp->product->name,
                    'sku' => $bp->product->sku,
                    'price' => (int) ($bp->selling_price ?? $bp->product->selling_price ?? 0),
                    'stock' => $bp->stock_quantity,
                    'image' => $bp->product->image_path,
                    'is_featured' => $bp->is_featured,
                    'category' => $bp->product->category->name ?? null,
                ];
            });

        // Get categories for filter
        $categories = ProductCategory::whereHas('products.branchProducts', function ($q) use ($branchId) {
            $q->where('branch_id', $branchId)->where('is_available', true);
        })->orderBy('name')->get(['id', 'name']);

        $shiftSummary = [
            'branch' => $activeBranch?->name ?? 'No Branch Selected',
            'cashier' => $user?->name ?? 'Unknown',
            'since' => $currentShift?->started_at?->format('H:i') ?? '-',
            'sales' => $currentShift?->total_sales ?? 0,
            'transactions' => $currentShift?->total_transactions ?? 0,
            'cashOnHand' => ($currentShift?->opening_cash ?? 0) + ($currentShift?->total_sales ?? 0),
        ];

        $cartItems = $this->cart;
        $paymentSummary = $this->getCartTotals();

        $paymentMethods = [
            ['label' => 'Cash', 'status' => 'ready', 'shortcut' => 'F7'],
            ['label' => 'QRIS', 'status' => 'ready', 'shortcut' => 'F8'],
            ['label' => 'Debit', 'status' => 'ready', 'shortcut' => 'F9'],
            ['label' => 'Split', 'status' => 'custom', 'shortcut' => 'F10'],
        ];

        return view('livewire.pos.screen', [
            'shiftSummary' => $shiftSummary,
            'cartItems' => $cartItems,
            'catalogProducts' => $catalogProducts,
            'categories' => $categories,
            'paymentSummary' => $paymentSummary,
            'paymentMethods' => $paymentMethods,
            'activeBranch' => $activeBranch,
            'currentShift' => $currentShift,
            'hasShift' => $currentShift !== null,
        ])->layoutData([
            'pageTitle' => 'Point of Sale',
        ]);
    }
}
