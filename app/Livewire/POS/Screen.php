<?php

namespace App\Livewire\Pos;

use App\Models\CashierShift;
use App\Models\Product;
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
    /** @var array<int, array{id: int|null, name: string, sku: string, qty: int, price: int, total: int}> */
    public array $cart = [];

    public string $barcodeInput = '';

    public ?array $scannedProduct = null;
    public function mount(): void
    {
        $this->initializeDemoCart();
    }

    public function initializeDemoCart(): void
    {
        $this->cart = [
            ['id' => 1, 'name' => 'Indomie Goreng Fried Noodles', 'sku' => 'SKU-IND-001', 'qty' => 3, 'price' => 3500, 'total' => 10500],
            ['id' => 2, 'name' => 'Indomie Kari Ayam Noodles', 'sku' => 'SKU-IND-002', 'qty' => 5, 'price' => 3400, 'total' => 17000],
            ['id' => 3, 'name' => 'ABC Sambal Extra Pedas 135ml', 'sku' => 'SKU-ABC-221', 'qty' => 1, 'price' => 12800, 'total' => 12800],
            ['id' => 4, 'name' => 'ABC Kecap Manis 135ml', 'sku' => 'SKU-ABC-045', 'qty' => 2, 'price' => 9000, 'total' => 18000],
            ['id' => 5, 'name' => 'Ultra Milk Full Cream 1L', 'sku' => 'SKU-MIL-908', 'qty' => 2, 'price' => 18500, 'total' => 37000],
        ];
    }

    public function searchByBarcode(): void
    {
        if (empty($this->barcodeInput)) {
            return;
        }

        $product = Product::where(function ($query) {
                $query->where('barcode', $this->barcodeInput)
                    ->orWhere('sku', $this->barcodeInput);
            })
            ->where('is_active', true)
            ->first();

        if ($product) {
            $this->scannedProduct = [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'barcode' => $product->barcode,
                'price' => (int) $product->selling_price,
                'image' => $product->image_path,
                'stock' => $product->stock_quantity ?? 0,
            ];

            // Add to cart
            $this->addToCart(
                $product->id,
                $product->name,
                $product->sku,
                (int) $product->selling_price
            );

            $this->dispatch('pos-product-scanned');
        } else {
            $this->scannedProduct = null;
            $this->dispatch('pos-product-not-found');
        }

        $this->barcodeInput = '';
    }

    public function addToCart(int $productId, string $name, string $sku, int $price): void
    {
        $existingIndex = collect($this->cart)->search(fn ($item) => $item['id'] === $productId);

        if ($existingIndex !== false) {
            $this->cart[$existingIndex]['qty']++;
            $this->cart[$existingIndex]['total'] = $this->cart[$existingIndex]['qty'] * $this->cart[$existingIndex]['price'];
        } else {
            $this->cart[] = [
                'id' => $productId,
                'name' => $name,
                'sku' => $sku,
                'qty' => 1,
                'price' => $price,
                'total' => $price,
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
    }

    public function getCartTotals(): array
    {
        $subtotal = collect($this->cart)->sum('total');
        $discount = (int) round($subtotal * 0.05);
        $taxableAmount = $subtotal - $discount;
        $tax = (int) round($taxableAmount * 0.10);
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
            }

            $shift->increment('total_transactions');
            $shift->update([
                'total_sales' => $shift->total_sales + $total,
            ]);
        });

        $this->cart = [];
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
        $shiftSummary = [
            'branch' => 'BASA Mart – Tegal',
            'cashier' => 'Ayu Pratama',
            'since' => '08:00 WIB',
            'sales' => 1520000,
            'transactions' => 37,
            'cashOnHand' => 450000,
        ];

        $quickActions = [
            ['label' => 'Suspend Sale', 'icon' => 'pause'],
        ];

        $cartItems = $this->cart;
        $paymentSummary = $this->getCartTotals();

        $suspendedSales = [
            ['number' => '#1032', 'amount' => 120000, 'time' => '14:02', 'note' => 'Customer grabbing cash'],
            ['number' => '#1033', 'amount' => 45000, 'time' => '14:05', 'note' => 'Split payment'],
        ];

        $catalogTiles = [
            ['title' => 'Teh Botol Sosro', 'price' => 6500, 'badge' => 'Popular'],
            ['title' => 'Pocari Sweat 350ml', 'price' => 8500, 'badge' => 'Chilled'],
            ['title' => 'Downy Softener 900ml', 'price' => 32500, 'badge' => 'Promo'],
            ['title' => 'Roti Aoka Cokelat', 'price' => 9500, 'badge' => 'Fresh'],
            ['title' => 'Fresh Eggs 1 Doz', 'price' => 28500, 'badge' => 'Bulk'],
            ['title' => 'Bango Kecap 520ml', 'price' => 29500, 'badge' => 'Favorite'],
        ];

        $notifications = [
            ['type' => 'warning', 'message' => 'Inventory sync delayed 2 min'],
        ];


        $checkoutPromos = [
            ['label' => 'Member Weekend', 'description' => 'Automatic loyalty discount', 'amount' => -3500],
            ['label' => 'Buy 2 Snack Bundle', 'description' => 'Combo savings applied', 'amount' => -1500],
        ];

        $paymentMethods = [
            ['label' => 'Cash', 'status' => 'ready', 'shortcut' => 'F7'],
            ['label' => 'QRIS', 'status' => 'ready', 'shortcut' => 'F8'],
            ['label' => 'Debit', 'status' => 'ready', 'shortcut' => 'F9'],
            ['label' => 'Split', 'status' => 'custom', 'shortcut' => 'F10'],
        ];

        $splitPayments = [
            ['method' => 'Cash', 'amount' => 30000],
            ['method' => 'QRIS', 'amount' => 28315],
        ];

        $cashierTransactions = [
            [
                'number' => '#POS-2034',
                'time' => '08:12',
                'items' => 4,
                'amount' => 78_000,
                'channel' => 'Cash',
                'receipt' => [
                    'items' => [
                        ['name' => 'Indomie Goreng', 'qty' => 2, 'price' => 3_500, 'total' => 7_000],
                        ['name' => 'Aqua 600ml', 'qty' => 4, 'price' => 4_000, 'total' => 16_000],
                        ['name' => 'SilverQueen 65g', 'qty' => 1, 'price' => 14_500, 'total' => 14_500],
                    ],
                    'subtotal' => 37_500,
                    'discount' => 2_500,
                    'tax' => 3_000,
                    'total' => 38_000,
                ],
            ],
            [
                'number' => '#POS-2035',
                'time' => '08:25',
                'items' => 2,
                'amount' => 42_500,
                'channel' => 'QRIS',
                'receipt' => [
                    'items' => [
                        ['name' => 'Pocari Sweat 350ml', 'qty' => 2, 'price' => 8_500, 'total' => 17_000],
                        ['name' => 'Downy Softener 900ml', 'qty' => 1, 'price' => 32_500, 'total' => 32_500],
                    ],
                    'subtotal' => 49_500,
                    'discount' => 7_000,
                    'tax' => 2_475,
                    'total' => 44_975,
                ],
            ],
            [
                'number' => '#POS-2036',
                'time' => '08:41',
                'items' => 6,
                'amount' => 132_400,
                'channel' => 'Debit',
                'receipt' => [
                    'items' => [
                        ['name' => 'Ultra Milk 1L', 'qty' => 3, 'price' => 18_500, 'total' => 55_500],
                        ['name' => 'Bango Kecap 520ml', 'qty' => 1, 'price' => 29_500, 'total' => 29_500],
                        ['name' => 'Lays Rumput Laut 55g', 'qty' => 4, 'price' => 8_500, 'total' => 34_000],
                    ],
                    'subtotal' => 119_000,
                    'discount' => 6_000,
                    'tax' => 11_900,
                    'total' => 124_900,
                ],
            ],
            [
                'number' => '#POS-2037',
                'time' => '08:55',
                'items' => 1,
                'amount' => 18_500,
                'channel' => 'Cash',
                'receipt' => [
                    'items' => [
                        ['name' => 'Lifebuoy Body Wash 450ml', 'qty' => 1, 'price' => 18_500, 'total' => 18_500],
                    ],
                    'subtotal' => 18_500,
                    'discount' => 0,
                    'tax' => 1_850,
                    'total' => 20_350,
                ],
            ],
            [
                'number' => '#POS-2038',
                'time' => '09:07',
                'items' => 5,
                'amount' => 99_800,
                'channel' => 'QRIS',
                'receipt' => [
                    'items' => [
                        ['name' => 'Teh Botol Sosro', 'qty' => 6, 'price' => 6_500, 'total' => 39_000],
                        ['name' => 'Indomie Kari Ayam', 'qty' => 5, 'price' => 3_400, 'total' => 17_000],
                        ['name' => 'ABC Sambal Extra Pedas', 'qty' => 2, 'price' => 12_800, 'total' => 25_600],
                    ],
                    'subtotal' => 81_600,
                    'discount' => 4_100,
                    'tax' => 8_160,
                    'total' => 85_660,
                ],
            ],
            [
                'number' => '#POS-2039',
                'time' => '09:15',
                'items' => 3,
                'amount' => 61_500,
                'channel' => 'Cash',
                'receipt' => [
                    'items' => [
                        ['name' => 'Sari Roti Tawar', 'qty' => 1, 'price' => 19_500, 'total' => 19_500],
                        ['name' => 'Good Day Freeze Coffee', 'qty' => 3, 'price' => 9_000, 'total' => 27_000],
                        ['name' => 'Dettol Handwash Refill', 'qty' => 1, 'price' => 23_500, 'total' => 23_500],
                    ],
                    'subtotal' => 70_000,
                    'discount' => 8_500,
                    'tax' => 7_000,
                    'total' => 68_500,
                ],
            ],
        ];

        /** @noinspection PhpUndefinedMethodInspection */
        return view('livewire.pos.screen', [
            'shiftSummary' => $shiftSummary,
            'quickActions' => $quickActions,
            'cartItems' => $cartItems,
            'suspendedSales' => $suspendedSales,
            'catalogTiles' => $catalogTiles,
            'notifications' => $notifications,
            'paymentSummary' => $paymentSummary,
            'checkoutPromos' => $checkoutPromos,
            'paymentMethods' => $paymentMethods,
            'splitPayments' => $splitPayments,
            'cashierTransactions' => $cashierTransactions,
        ])->layoutData([
            'pageTitle' => 'Point of Sale',
        ]);
    }
}
