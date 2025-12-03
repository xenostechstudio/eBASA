<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\GeneralSetup\ActivityLogs\Index as GeneralSetupActivityLogs;
use App\Livewire\GeneralSetup\PaymentMethods\Index as GeneralSetupPaymentMethods;
use App\Livewire\GeneralSetup\Permissions\Index as GeneralSetupPermissions;
use App\Livewire\GeneralSetup\Portal as GeneralSetupPortal;
use App\Livewire\GeneralSetup\ProductCategories\Index as GeneralSetupProductCategories;
use App\Livewire\GeneralSetup\ProductCategories\Create as GeneralSetupProductCategoriesCreate;
use App\Livewire\GeneralSetup\ProductCategories\Edit as GeneralSetupProductCategoriesEdit;
use App\Livewire\GeneralSetup\Users\Create as GeneralSetupUsersCreate;
use App\Livewire\GeneralSetup\Users\Edit as GeneralSetupUsersEdit;
use App\Livewire\Inventory\Branches\Edit as GeneralSetupBranchesEdit;
use App\Livewire\GeneralSetup\Products\Index as GeneralSetupProducts;
use App\Livewire\GeneralSetup\Products\Create as GeneralSetupProductsCreate;
use App\Livewire\GeneralSetup\Products\Edit as GeneralSetupProductsEdit;
use App\Livewire\GeneralSetup\Roles\Index as GeneralSetupRoles;
use App\Livewire\GeneralSetup\Roles\Create as GeneralSetupRolesCreate;
use App\Livewire\GeneralSetup\Roles\Edit as GeneralSetupRolesEdit;
use App\Livewire\GeneralSetup\Roles\Permissions as GeneralSetupRolesPermissions;
use App\Livewire\GeneralSetup\Settings\Index as GeneralSetupSettings;
use App\Livewire\GeneralSetup\Users\Index as GeneralSetupUsers;
use App\Livewire\GeneralSetup\Warehouses\Index as GeneralSetupWarehouses;
use App\Livewire\GeneralSetup\Warehouses\Create as GeneralSetupWarehousesCreate;
use App\Livewire\GeneralSetup\Warehouses\Edit as GeneralSetupWarehousesEdit;
use App\Livewire\HumanResource\Departments\Create as HrDepartmentCreate;
use App\Livewire\HumanResource\Departments\Edit as HrDepartmentEdit;
use App\Livewire\HumanResource\Departments\Index as HrDepartmentIndex;
use App\Livewire\HumanResource\Employees\Create as HrEmployeeCreate;
use App\Livewire\HumanResource\Employees\Edit as HrEmployeeEdit;
use App\Livewire\HumanResource\Employees\Index as HrEmployeeIndex;
use App\Livewire\HumanResource\Employments\Create as HrEmploymentCreate;
use App\Livewire\HumanResource\Employments\Edit as HrEmploymentEdit;
use App\Livewire\HumanResource\Employments\Index as HrEmploymentIndex;
use App\Livewire\HumanResource\Positions\Create as HrPositionCreate;
use App\Livewire\HumanResource\Positions\Edit as HrPositionEdit;
use App\Livewire\HumanResource\Positions\Index as HrPositionIndex;
use App\Livewire\HumanResource\Portal as HrPortal;
use App\Livewire\HumanResource\Payroll\PayrollGroups\Index as HrPayrollGroupIndex;
use App\Livewire\HumanResource\Payroll\PayrollGroups\Create as HrPayrollGroupCreate;
use App\Livewire\HumanResource\Payroll\PayrollGroups\Edit as HrPayrollGroupEdit;
use App\Livewire\HumanResource\Payroll\PayrollRuns\Index as HrPayrollRunIndex;
use App\Livewire\HumanResource\Payroll\PayrollRuns\Create as HrPayrollRunCreate;
use App\Livewire\HumanResource\Payroll\PayrollRuns\Edit as HrPayrollRunEdit;
use App\Livewire\HumanResource\Payroll\PayrollAdjustments\Index as HrPayrollAdjustmentIndex;
use App\Livewire\HumanResource\Payroll\PayrollAdjustments\Create as HrPayrollAdjustmentCreate;
use App\Livewire\HumanResource\Payroll\PayrollAdjustments\Edit as HrPayrollAdjustmentEdit;
use App\Livewire\HumanResource\Payroll\PayrollPayouts\Index as HrPayrollPayoutIndex;
use App\Livewire\HumanResource\Payroll\PayrollItems\Index as HrPayrollItemIndex;
use App\Livewire\HumanResource\Payroll\PayrollItems\Create as HrPayrollItemCreate;
use App\Livewire\HumanResource\Payroll\PayrollItems\Edit as HrPayrollItemEdit;
use App\Livewire\HumanResource\Leave\LeaveTypes\Index as HrLeaveTypeIndex;
use App\Livewire\HumanResource\Leave\LeaveTypes\Create as HrLeaveTypeCreate;
use App\Livewire\HumanResource\Leave\LeaveTypes\Edit as HrLeaveTypeEdit;
use App\Livewire\HumanResource\Leave\LeaveRequests\Index as HrLeaveRequestIndex;
use App\Livewire\HumanResource\Leave\LeaveRequests\Create as HrLeaveRequestCreate;
use App\Livewire\HumanResource\Leave\LeaveRequests\Edit as HrLeaveRequestEdit;
use App\Livewire\HumanResource\Attendance\Attendances\Index as HrAttendanceIndex;
use App\Livewire\HumanResource\Attendance\Attendances\Create as HrAttendanceCreate;
use App\Livewire\HumanResource\Attendance\Attendances\Edit as HrAttendanceEdit;
use App\Livewire\HumanResource\Attendance\Shifts\Index as HrShiftIndex;
use App\Livewire\HumanResource\Attendance\Shifts\Create as HrShiftCreate;
use App\Livewire\HumanResource\Attendance\Shifts\Edit as HrShiftEdit;
use App\Livewire\Inventory\Catalog\Bundles\Index as InventoryCatalogBundles;
use App\Livewire\Inventory\Catalog\PriceLists\Index as InventoryCatalogPriceLists;
use App\Livewire\Inventory\Catalog\Products\Index as InventoryCatalogProducts;
use App\Livewire\Inventory\Portal as InventoryPortal;
use App\Livewire\Inventory\Stock\Adjustments\Create as InventoryStockAdjustmentsCreate;
use App\Livewire\Inventory\Stock\Adjustments\Index as InventoryStockAdjustments;
use App\Livewire\Inventory\Stock\Adjustments\Show as InventoryStockAdjustmentsShow;
use App\Livewire\Inventory\Stock\Levels\Index as InventoryStockLevels;
use App\Livewire\Inventory\Stock\Transfers\Create as InventoryStockTransfersCreate;
use App\Livewire\Inventory\Stock\Transfers\Index as InventoryStockTransfers;
use App\Livewire\Inventory\Stock\Transfers\Show as InventoryStockTransfersShow;
use App\Livewire\Inventory\Branches\Create as GeneralSetupBranchesCreate;
use App\Livewire\Inventory\Branches\Index as GeneralSetupBranchesIndex;
use App\Livewire\Procurement\Suppliers\Create as ProcurementSuppliersCreate;
use App\Livewire\Procurement\Suppliers\Edit as ProcurementSuppliersEdit;
use App\Livewire\Pos\Screen as PosScreen;
use App\Livewire\Procurement\Orders\Create as ProcurementOrderCreate;
use App\Livewire\Procurement\Orders\Index as ProcurementOrders;
use App\Livewire\Procurement\Portal as ProcurementPortal;
use App\Livewire\Procurement\Receipts\Create as ProcurementReceiptsCreate;
use App\Livewire\Procurement\Receipts\Index as ProcurementReceipts;
use App\Livewire\Procurement\Returns\Create as ProcurementReturnsCreate;
use App\Livewire\Procurement\Returns\Edit as ProcurementReturnsEdit;
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
use App\Livewire\Transaction\SettlementShow as TransactionSettlementShow;
use App\Livewire\Transaction\Shifts as TransactionShifts;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    // Home & dashboard
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

    // POS
    Route::get('/pos', PosScreen::class)->name('pos');

    // Transactions
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', TransactionIndex::class)->name('index');
        Route::get('/reports', TransactionReports::class)->name('reports');
        Route::get('/settlements', TransactionSettlements::class)->name('settlements');
        Route::get('/settlements/{shift}', TransactionSettlementShow::class)->name('settlements.show');
        Route::get('/refunds', TransactionRefunds::class)->name('refunds');
        Route::get('/shifts', TransactionShifts::class)->name('shifts');
        Route::get('/cash-counts', TransactionCashCounts::class)->name('cash-counts');
    });

    // Inventory
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', InventoryPortal::class)->name('portal');

        Route::prefix('stock')->name('stock.')->group(function () {
            Route::get('/levels', InventoryStockLevels::class)->name('levels');
            Route::get('/adjustments', InventoryStockAdjustments::class)->name('adjustments');
            Route::get('/adjustments/create', InventoryStockAdjustmentsCreate::class)->name('adjustments.create');
            Route::get('/adjustments/{adjustment}', InventoryStockAdjustmentsShow::class)->name('adjustments.show');
            Route::get('/transfers', InventoryStockTransfers::class)->name('transfers');
            Route::get('/transfers/create', InventoryStockTransfersCreate::class)->name('transfers.create');
            Route::get('/transfers/{transfer}', InventoryStockTransfersShow::class)->name('transfers.show');
        });

        Route::prefix('catalog')->name('catalog.')->group(function () {
            Route::get('/products', InventoryCatalogProducts::class)->name('products');
            Route::get('/bundles', InventoryCatalogBundles::class)->name('bundles');
            Route::get('/price-lists', InventoryCatalogPriceLists::class)->name('price-lists');
        });

        // Inventory branches list (reusing General Setup branches component)
        Route::get('/branches', GeneralSetupBranchesIndex::class)->name('branches.index');
    });

    // Procurement
    Route::prefix('procurement')->name('procurement.')->group(function () {
        Route::get('/', ProcurementPortal::class)->name('portal');
        Route::get('/suppliers', ProcurementSuppliers::class)->name('suppliers');
        Route::get('/suppliers/create', ProcurementSuppliersCreate::class)->name('suppliers.create');
        Route::get('/suppliers/{supplier}', ProcurementSuppliersEdit::class)->name('suppliers.edit');
        Route::get('/orders', ProcurementOrders::class)->name('orders');
        Route::get('/orders/create', ProcurementOrderCreate::class)->name('orders.create');
        Route::get('/receipts', ProcurementReceipts::class)->name('receipts');
        Route::get('/receipts/create', ProcurementReceiptsCreate::class)->name('receipts.create');
        Route::get('/returns', ProcurementReturns::class)->name('returns');
        Route::get('/returns/create', ProcurementReturnsCreate::class)->name('returns.create');
        Route::get('/returns/{return}', ProcurementReturnsEdit::class)->name('returns.edit');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', ReportPortal::class)->name('portal');

        Route::prefix('sales')->name('sales.')->group(function () {
            Route::get('/daily', ReportSalesDaily::class)->name('daily');
            Route::get('/products', ReportSalesProducts::class)->name('products');
            Route::get('/branches', ReportSalesBranches::class)->name('branches');
        });

        Route::prefix('inventory')->name('inventory.')->group(function () {
            Route::get('/stock', ReportInventoryStock::class)->name('stock');
            Route::get('/movements', ReportInventoryMovements::class)->name('movements');
        });

        Route::prefix('financial')->name('financial.')->group(function () {
            Route::get('/revenue', ReportFinancialRevenue::class)->name('revenue');
            Route::get('/expenses', ReportFinancialExpenses::class)->name('expenses');
        });
    });

    // General Setup (protected by permission)
    Route::prefix('general-setup')
        ->name('general-setup.')
        ->middleware('permission:system.access-general-setup')
        ->group(function () {
            Route::get('/', GeneralSetupPortal::class)->name('portal');

            Route::prefix('users')->name('users.')->group(function () {
                Route::get('/', GeneralSetupUsers::class)->name('index');
                Route::get('/create', GeneralSetupUsersCreate::class)->name('create');
                Route::get('/{user}/edit', GeneralSetupUsersEdit::class)->name('edit');
            });

            Route::prefix('branches')->name('branches.')->group(function () {
                Route::get('/', GeneralSetupBranchesIndex::class)->name('index');
                Route::get('/create', GeneralSetupBranchesCreate::class)->name('create');
                Route::get('/{branch}/edit', GeneralSetupBranchesEdit::class)->name('edit');
            });

            Route::prefix('warehouses')->name('warehouses.')->group(function () {
                Route::get('/', GeneralSetupWarehouses::class)->name('index');
                Route::get('/create', GeneralSetupWarehousesCreate::class)->name('create');
                Route::get('/{warehouse}/edit', GeneralSetupWarehousesEdit::class)->name('edit');
            });

            Route::prefix('products')->name('products.')->group(function () {
                Route::get('/', GeneralSetupProducts::class)->name('index');
                Route::get('/create', GeneralSetupProductsCreate::class)->name('create');
                Route::get('/{product}/edit', GeneralSetupProductsEdit::class)->name('edit');
            });

            Route::prefix('product-categories')->name('product-categories.')->group(function () {
                Route::get('/', GeneralSetupProductCategories::class)->name('index');
                Route::get('/create', GeneralSetupProductCategoriesCreate::class)->name('create');
                Route::get('/{category}/edit', GeneralSetupProductCategoriesEdit::class)->name('edit');
            });

            Route::get('/payment-methods', GeneralSetupPaymentMethods::class)->name('payment-methods.index');

            Route::prefix('roles')->name('roles.')->group(function () {
                Route::get('/', GeneralSetupRoles::class)->name('index');
                Route::get('/create', GeneralSetupRolesCreate::class)->name('create');
                Route::get('/{role}/edit', GeneralSetupRolesEdit::class)->name('edit');
                Route::get('/{role}/permissions', GeneralSetupRolesPermissions::class)->name('permissions');
            });
            Route::get('/permissions', GeneralSetupPermissions::class)->name('permissions.index');
            Route::get('/settings', GeneralSetupSettings::class)->name('settings.index');
            Route::get('/activity-logs', GeneralSetupActivityLogs::class)->name('activity-logs.index');
        });

    // Human Resources
    Route::prefix('hr')->name('hr.')->group(function () {
        Route::get('/', HrPortal::class)->name('portal');

        // People
        Route::get('/employees', HrEmployeeIndex::class)->name('employees');
        Route::get('/employees/create', HrEmployeeCreate::class)->name('employees.create');
        Route::get('/employees/{employee}/edit', HrEmployeeEdit::class)->name('employees.edit');
        Route::get('/employments', HrEmploymentIndex::class)->name('employments');
        Route::get('/employments/create', HrEmploymentCreate::class)->name('employments.create');
        Route::get('/employments/{employee}/edit', HrEmploymentEdit::class)->name('employments.edit');
        Route::get('/departments', HrDepartmentIndex::class)->name('departments');
        Route::get('/departments/create', HrDepartmentCreate::class)->name('departments.create');
        Route::get('/departments/{department}/edit', HrDepartmentEdit::class)->name('departments.edit');
        Route::get('/positions', HrPositionIndex::class)->name('positions');
        Route::get('/positions/create', HrPositionCreate::class)->name('positions.create');
        Route::get('/positions/{position}/edit', HrPositionEdit::class)->name('positions.edit');

        // Payroll
        Route::get('/payroll-items', HrPayrollItemIndex::class)->name('payroll-items');
        Route::get('/payroll-items/create', HrPayrollItemCreate::class)->name('payroll-items.create');
        Route::get('/payroll-items/{payrollItem}/edit', HrPayrollItemEdit::class)->name('payroll-items.edit');
        Route::get('/payroll-groups', HrPayrollGroupIndex::class)->name('payroll-groups');
        Route::get('/payroll-groups/create', HrPayrollGroupCreate::class)->name('payroll-groups.create');
        Route::get('/payroll-groups/{payrollGroup}/edit', HrPayrollGroupEdit::class)->name('payroll-groups.edit');
        Route::get('/payroll-runs', HrPayrollRunIndex::class)->name('payroll-runs');
        Route::get('/payroll-runs/create', HrPayrollRunCreate::class)->name('payroll-runs.create');
        Route::get('/payroll-runs/{payrollRun}/edit', HrPayrollRunEdit::class)->name('payroll-runs.edit');
        Route::get('/payroll-adjustments', HrPayrollAdjustmentIndex::class)->name('payroll-adjustments');
        Route::get('/payroll-adjustments/create', HrPayrollAdjustmentCreate::class)->name('payroll-adjustments.create');
        Route::get('/payroll-adjustments/{payrollAdjustment}/edit', HrPayrollAdjustmentEdit::class)->name('payroll-adjustments.edit');
        Route::get('/payroll-payouts', HrPayrollPayoutIndex::class)->name('payroll-payouts');

        // Leave Management
        Route::get('/leave-types', HrLeaveTypeIndex::class)->name('leave-types');
        Route::get('/leave-types/create', HrLeaveTypeCreate::class)->name('leave-types.create');
        Route::get('/leave-types/{leaveType}/edit', HrLeaveTypeEdit::class)->name('leave-types.edit');
        Route::get('/leave-requests', HrLeaveRequestIndex::class)->name('leave-requests');
        Route::get('/leave-requests/create', HrLeaveRequestCreate::class)->name('leave-requests.create');
        Route::get('/leave-requests/{leaveRequest}/edit', HrLeaveRequestEdit::class)->name('leave-requests.edit');

        // Attendance
        Route::get('/attendances', HrAttendanceIndex::class)->name('attendances');
        Route::get('/attendances/create', HrAttendanceCreate::class)->name('attendances.create');
        Route::get('/attendances/{attendance}/edit', HrAttendanceEdit::class)->name('attendances.edit');
        Route::get('/shifts', HrShiftIndex::class)->name('shifts');
        Route::get('/shifts/create', HrShiftCreate::class)->name('shifts.create');
        Route::get('/shifts/{shift}/edit', HrShiftEdit::class)->name('shifts.edit');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', \App\Livewire\Profile\Edit::class)->name('profile.edit');

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
