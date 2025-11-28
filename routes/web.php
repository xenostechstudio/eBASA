<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\GeneralSetup\ActivityLogs\Index as GeneralSetupActivityLogs;
use App\Livewire\GeneralSetup\PaymentMethods\Index as GeneralSetupPaymentMethods;
use App\Livewire\GeneralSetup\Permissions\Index as GeneralSetupPermissions;
use App\Livewire\GeneralSetup\Portal as GeneralSetupPortal;
use App\Livewire\GeneralSetup\ProductCategories\Index as GeneralSetupProductCategories;
use App\Livewire\GeneralSetup\Products\Index as GeneralSetupProducts;
use App\Livewire\GeneralSetup\Roles\Index as GeneralSetupRoles;
use App\Livewire\GeneralSetup\Settings\Index as GeneralSetupSettings;
use App\Livewire\GeneralSetup\Users\Index as GeneralSetupUsers;
use App\Livewire\GeneralSetup\Warehouses\Index as GeneralSetupWarehouses;
use App\Livewire\HumanResource\Departments\Create as HrDepartmentCreate;
use App\Livewire\HumanResource\Departments\Index as HrDepartmentIndex;
use App\Livewire\HumanResource\Employees\Create as HrEmployeeCreate;
use App\Livewire\HumanResource\Employees\Edit as HrEmployeeEdit;
use App\Livewire\HumanResource\Employees\Index as HrEmployeeIndex;
use App\Livewire\HumanResource\Employments\Create as HrEmploymentCreate;
use App\Livewire\HumanResource\Employments\Index as HrEmploymentIndex;
use App\Livewire\HumanResource\Positions\Create as HrPositionCreate;
use App\Livewire\HumanResource\Positions\Index as HrPositionIndex;
use App\Livewire\HumanResource\Portal as HrPortal;
use App\Livewire\Inventory\Catalog\Bundles\Index as InventoryCatalogBundles;
use App\Livewire\Inventory\Catalog\PriceLists\Index as InventoryCatalogPriceLists;
use App\Livewire\Inventory\Catalog\Products\Index as InventoryCatalogProducts;
use App\Livewire\Inventory\Portal as InventoryPortal;
use App\Livewire\Inventory\Stock\Adjustments\Index as InventoryStockAdjustments;
use App\Livewire\Inventory\Stock\Levels\Index as InventoryStockLevels;
use App\Livewire\Inventory\Stock\Transfers\Index as InventoryStockTransfers;
use App\Livewire\Inventory\Branches\Create as GeneralSetupBranchesCreate;
use App\Livewire\Inventory\Branches\Index as GeneralSetupBranchesIndex;
use App\Livewire\Pos\Screen as PosScreen;
use App\Livewire\Procurement\Orders\Create as ProcurementOrderCreate;
use App\Livewire\Procurement\Orders\Index as ProcurementOrders;
use App\Livewire\Procurement\Portal as ProcurementPortal;
use App\Livewire\Procurement\Receipts\Index as ProcurementReceipts;
use App\Livewire\Procurement\Returns\Index as ProcurementReturns;
use App\Livewire\Procurement\Suppliers\Index as ProcurementSuppliers;
use App\Livewire\Report\Financial\Expenses as ReportFinancialExpenses;
use App\Livewire\Report\Financial\Revenue as ReportFinancialRevenue;
use App\Livewire\Report\Inventory\Movements as ReportInventoryMovements;
use App\Livewire\Report\Inventory\Stock as ReportInventoryStock;
use App\Livewire\Report\Portal as ReportPortal;
use App\Livewire\Report\Sales\Branches as ReportSalesBranches;
use App\Livewire\Report\Sales\Daily as ReportSalesDaily;
use App\Livewire\Report\Sales\Products as ReportSalesProducts;
use App\Livewire\Transaction\CashCounts as TransactionCashCounts;
use App\Livewire\Transaction\Index as TransactionIndex;
use App\Livewire\Transaction\Refunds as TransactionRefunds;
use App\Livewire\Transaction\Reports as TransactionReports;
use App\Livewire\Transaction\Settlements as TransactionSettlements;
use App\Livewire\Transaction\Shifts as TransactionShifts;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        $modules = collect(config('modules.list', []));
        $branchShortcuts = Branch::orderBy('name')->get();
        $activeBranchId = (int) session('active_branch_id', 0);
        $jakartaNow = now('Asia/Jakarta');

        return view('home', compact('modules', 'branchShortcuts', 'activeBranchId', 'jakartaNow'));
    })->name('home');

    Route::get('/dashboard', function () {
        $modules = collect(config('modules.list', []));

        return view('dashboard', compact('modules'));
    })->name('dashboard');

    Route::get('/pos', PosScreen::class)->name('pos');
    Route::get('/transactions', TransactionIndex::class)->name('transactions.index');
    Route::get('/transactions/reports', TransactionReports::class)->name('transactions.reports');
    Route::get('/transactions/settlements', TransactionSettlements::class)->name('transactions.settlements');
    Route::get('/transactions/refunds', TransactionRefunds::class)->name('transactions.refunds');
    Route::get('/transactions/shifts', TransactionShifts::class)->name('transactions.shifts');
    Route::get('/transactions/cash-counts', TransactionCashCounts::class)->name('transactions.cash-counts');
    Route::get('/inventory', InventoryPortal::class)->name('inventory.portal');
    Route::get('/inventory/stock/levels', InventoryStockLevels::class)->name('inventory.stock.levels');
    Route::get('/inventory/stock/adjustments', InventoryStockAdjustments::class)->name('inventory.stock.adjustments');
    Route::get('/inventory/stock/transfers', InventoryStockTransfers::class)->name('inventory.stock.transfers');
    Route::get('/inventory/catalog/products', InventoryCatalogProducts::class)->name('inventory.catalog.products');
    Route::get('/inventory/catalog/bundles', InventoryCatalogBundles::class)->name('inventory.catalog.bundles');
    Route::get('/inventory/catalog/price-lists', InventoryCatalogPriceLists::class)->name('inventory.catalog.price-lists');
    Route::get('/procurement', ProcurementPortal::class)->name('procurement.portal');
    Route::get('/procurement/suppliers', ProcurementSuppliers::class)->name('procurement.suppliers');
    Route::get('/procurement/orders', ProcurementOrders::class)->name('procurement.orders');
    Route::get('/procurement/orders/create', ProcurementOrderCreate::class)->name('procurement.orders.create');
    Route::get('/procurement/receipts', ProcurementReceipts::class)->name('procurement.receipts');
    Route::get('/procurement/returns', ProcurementReturns::class)->name('procurement.returns');
    Route::get('/reports', ReportPortal::class)->name('reports.portal');
    Route::get('/reports/sales/daily', ReportSalesDaily::class)->name('reports.sales.daily');
    Route::get('/reports/sales/products', ReportSalesProducts::class)->name('reports.sales.products');
    Route::get('/reports/sales/branches', ReportSalesBranches::class)->name('reports.sales.branches');
    Route::get('/reports/inventory/stock', ReportInventoryStock::class)->name('reports.inventory.stock');
    Route::get('/reports/inventory/movements', ReportInventoryMovements::class)->name('reports.inventory.movements');
    Route::get('/reports/financial/revenue', ReportFinancialRevenue::class)->name('reports.financial.revenue');
    Route::get('/reports/financial/expenses', ReportFinancialExpenses::class)->name('reports.financial.expenses');
    Route::get('/general-setup', GeneralSetupPortal::class)->name('general-setup.portal');
    Route::get('/general-setup/users', GeneralSetupUsers::class)->name('general-setup.users.index');
    Route::get('/general-setup/branches', GeneralSetupBranchesIndex::class)->name('general-setup.branches.index');
    Route::get('/general-setup/branches/create', GeneralSetupBranchesCreate::class)->name('general-setup.branches.create');
    Route::get('/general-setup/warehouses', GeneralSetupWarehouses::class)->name('general-setup.warehouses.index');
    Route::get('/general-setup/products', GeneralSetupProducts::class)->name('general-setup.products.index');
    Route::get('/general-setup/products/create', GeneralSetupProducts::class)->name('general-setup.products.create');
    Route::get('/general-setup/products/{product}/edit', GeneralSetupProducts::class)->name('general-setup.products.edit');
    Route::get('/general-setup/product-categories', GeneralSetupProductCategories::class)->name('general-setup.product-categories.index');
    Route::get('/general-setup/payment-methods', GeneralSetupPaymentMethods::class)->name('general-setup.payment-methods.index');
    Route::get('/general-setup/roles', GeneralSetupRoles::class)->name('general-setup.roles.index');
    Route::get('/general-setup/permissions', GeneralSetupPermissions::class)->name('general-setup.permissions.index');
    Route::get('/general-setup/settings', GeneralSetupSettings::class)->name('general-setup.settings.index');
    Route::get('/general-setup/activity-logs', GeneralSetupActivityLogs::class)->name('general-setup.activity-logs.index');
    Route::get('/hr', HrPortal::class)->name('hr.portal');
    Route::get('/hr/employees', HrEmployeeIndex::class)->name('hr.employees');
    Route::get('/hr/employees/create', HrEmployeeCreate::class)->name('hr.employees.create');
    Route::get('/hr/employees/{employee}/edit', HrEmployeeEdit::class)->name('hr.employees.edit');
    Route::get('/hr/employments', HrEmploymentIndex::class)->name('hr.employments');
    Route::get('/hr/employments/create', HrEmploymentCreate::class)->name('hr.employments.create');
    Route::get('/hr/departments', HrDepartmentIndex::class)->name('hr.departments');
    Route::get('/hr/departments/create', HrDepartmentCreate::class)->name('hr.departments.create');
    Route::get('/hr/positions', HrPositionIndex::class)->name('hr.positions');
    Route::get('/hr/positions/create', HrPositionCreate::class)->name('hr.positions.create');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/locale', function (Request $request) {
        $request->validate([
            'locale' => ['required', 'string', 'in:'.collect(config('locale.available'))->pluck('code')->implode(',')],
        ]);

        session(['locale' => $request->string('locale')->toString()]);

        return back();
    })->name('locale.switch');

    Route::post('/branches/switch', function (Request $request) {
        $branchId = $request->input('branch_id');

        if ($branchId === 'all') {
            session()->forget('active_branch_id');
            return back();
        }

        $request->merge(['branch_id' => (int) $branchId]);
        $request->validate([
            'branch_id' => ['required', 'integer', 'exists:branches,id'],
        ]);

        session(['active_branch_id' => (int) $branchId]);

        return back();
    })->name('branch.switch');
});

require __DIR__.'/auth.php';
