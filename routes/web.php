<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;

use App\Models\User;


Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/logout', [AuthController::class, 'logout']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route::middleware('auth')->group(function () {
//     Route::view('/', 'homepage')->name('home');

//     Route::get('/acctg', [UserController::class, 'loadAcctgPage'])->middleware('role:admin,bookeeper,auditor,audasst')->name('acctg');
//     Route::get('/prod', [UserController::class, 'loadAssemblePage'])->middleware('role:admin,assembler')->name('prod');

//     Route::get('/admin', [AdminController::class, 'index'])->name('dash')->middleware('role:admin');
//     Route::get('/admin/users', [AdminController::class, 'manageUsers'])->name('usertool')->middleware('role:admin');
//     Route::put('/admin/users/{user}/update-role', [AdminController::class, 'updateUserRole'])->name('updateUserRole')->middleware('role:admin');
//     Route::post('/admin/roles', [AdminController::class, 'createRole'])->name('createRole');

//     Route::get('/acctg/new', [BookController::class, 'newLedgerEntry'])->middleware(['role:admin,bookeeper,auditor'])->name('newledger');
//     Route::post('/acctg/new', [BookController::class, 'saveNewLedgerEntry'])->middleware('role:admin,bookeeper')->name('saveledger');
//     Route::get('/acctg/view/all', [BookController::class, 'showAllLedgers'])->middleware(['role:admin,bookeeper,auditor,audasst'])->name('ledgers');
//     Route::get('/acctg/view/{id}', [BookController::class, 'viewLedgerDetails'])->middleware(['role:admin,auditor,audasst'])->name('ledger');
// });

Route::middleware('auth')->group(function () {
    Route::view('/', 'homepage')->name('home');

    Route::get('/acctg', [UserController::class, 'loadAcctgPage'])
        ->middleware(['permission:can_view_all|can_manage|can_create|can_update'])
        ->name('acctg');
    Route::get('/prod', [UserController::class, 'loadAssemblePage'])->middleware('role:admin')->middleware('permission:can_view_all|can_manage')->name('prod');

    Route::middleware(['role:admin', 'permission:can_manage'])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('dash');
        Route::get('/admin/users', [AdminController::class, 'manageUsers'])->name('usertool');
        Route::put('/admin/users/{user}/update-role', [AdminController::class, 'updateUserRole'])->name('updateUserRole');
        Route::put('/admin/users/{user}/update-permissions', [AdminController::class, 'updateUserPermissions'])->name('updateUserPermissions');
        Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser'])->name('deleteUser');
        Route::post('/admin/roles', [AdminController::class, 'createRole'])->name('createRole');
    });

    Route::get('/acctg/new', [BookController::class, 'newLedgerEntry'])->middleware('role:admin')->middleware('permission:can_create|can_manage')->name('newledger');
    Route::post('/acctg/new', [BookController::class, 'saveNewLedgerEntry'])->middleware('permission:can_create|can_manage')->name('saveledger');
    Route::get('/acctg/view/all', [BookController::class, 'showAllLedgers'])->middleware(['role:admin', 'permission:can_view_all|can_manage'])->name('ledgers');
    Route::get('/acctg/view/{id}', [BookController::class, 'viewLedgerDetails'])->middleware('permission:can_view_detail|can_manage')->name('ledger');
});

// Debug route to check roles and permissions
Route::get('/debug', function () {
    $user = User::find(auth()->id());
    return response()->json([
        'roles' => $user->roles->pluck('name'),
        'permissions' => $user->permissions()->pluck('name')
    ]);
})->middleware('auth');
