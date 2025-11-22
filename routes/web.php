<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\GeneralSetup\Portal as GeneralSetupPortal;
use App\Livewire\HumanResource\Departments\Create as HrDepartmentCreate;
use App\Livewire\HumanResource\Departments\Index as HrDepartmentIndex;
use App\Livewire\HumanResource\Employees\Create as HrEmployeeCreate;
use App\Livewire\HumanResource\Employees\Index as HrEmployeeIndex;
use App\Livewire\HumanResource\Employments\Create as HrEmploymentCreate;
use App\Livewire\HumanResource\Employments\Index as HrEmploymentIndex;
use App\Livewire\HumanResource\Positions\Create as HrPositionCreate;
use App\Livewire\HumanResource\Positions\Index as HrPositionIndex;
use App\Livewire\HumanResource\Portal as HrPortal;
use App\Livewire\Inventory\Branches\Create as InventoryBranchCreate;
use App\Livewire\Inventory\Branches\Index as InventoryBranchIndex;
use App\Livewire\Inventory\Portal as InventoryPortal;
use App\Livewire\Pos\Screen as PosScreen;
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
    Route::get('/inventory', InventoryPortal::class)->name('inventory.portal');
    Route::get('/inventory/branches', InventoryBranchIndex::class)->name('inventory.branches.index');
    Route::get('/inventory/branches/create', InventoryBranchCreate::class)->name('inventory.branches.create');
    Route::get('/general-setup', GeneralSetupPortal::class)->name('general-setup.portal');
    Route::get('/hr', HrPortal::class)->name('hr.portal');
    Route::get('/hr/employees', HrEmployeeIndex::class)->name('hr.employees');
    Route::get('/hr/employees/create', HrEmployeeCreate::class)->name('hr.employees.create');
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
