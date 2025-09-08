<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\InstockController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SupplierReturnController;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\IssueReturnController;
use App\Http\Controllers\UnitController; 
use App\Http\Controllers\BrandController; 
use App\Http\Controllers\CommodityController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ShelfController;
use App\Http\Controllers\ShelfNumberController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdjustmentController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\BarCodeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Auth::routes();
Route::get('/register', function () {
    abort(404);
});

Route::get('cache-clear',function(){
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    return "Cleared!";
});

Route::get('migrate',function(){
   Artisan::call('migrate');
});

Route::get('seed',function(){
   Artisan::call('db:seed');
});

Route::get('migrate-refresh',function(){
   Artisan::call('migrate:fresh');
});

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});

Route::group(['middleware' => 'auth'], function () {
      
        Route::prefix('instocks')->group(function () {
            Route::get('/', [InstockController::class, 'index'])->name('instocks.index');
        });

        Route::prefix('suppliers')->group(function () {
            Route::get('/', [SupplierController::class, 'index'])->name('suppliers.index');
            Route::get('/create', [SupplierController::class ,'create'])->name('suppliers.create');
            Route::post('/store', [SupplierController::class ,'store'])->name('suppliers.store');
            Route::get('/edit', [SupplierController::class ,'edit'])->name('suppliers.edit');
            Route::post('/update', [SupplierController::class ,'update'])->name('suppliers.update');
            Route::get('/delete', [SupplierController::class ,'destroy'])->name('suppliers.delete');
        });
        
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('users.index');
            Route::get('/create', [UserController::class ,'create'])->name('users.create');
            Route::post('/store', [UserController::class ,'store'])->name('users.store');
            Route::get('/edit', [UserController::class ,'edit'])->name('users.edit');
            Route::post('/update', [UserController::class ,'update'])->name('users.update');
            Route::get('/delete', [UserController::class ,'destroy'])->name('users.delete');

            Route::get('/profile', [UserController::class ,'profile'])->name('users.profile');
        
            Route::get('/permissions', [PermissionController::class ,'index'])->name('users.permissions');
            Route::post('/permissions/sotre', [PermissionController::class ,'store'])->name('users.permission_store');
        });
        
        Route::prefix('supplier_returns')->group(function () {
            Route::get('/', [SupplierReturnController::class, 'index'])->name('supplier_returns.index');
            Route::get('/history', [SupplierReturnController::class ,'history'])->name('supplier_returns.history');
            Route::get('/create', [SupplierReturnController::class ,'create'])->name('supplier_returns.create');
            Route::post('/store', [SupplierReturnController::class ,'store'])->name('supplier_returns.store');
            Route::get('/edit', [SupplierReturnController::class ,'edit'])->name('supplier_returns.edit');
            Route::post('/update', [SupplierReturnController::class ,'update'])->name('supplier_returns.update');

            
            Route::get('/supplier-under-shelfnum', [SupplierReturnController::class ,'getSupplier'])->name('supplier_returns.supplier');
        });
        
        
        Route::prefix('customers')->group(function () {
            Route::get('/', [CustomerController::class, 'index'])->name('customers.index');
            Route::get('/create', [CustomerController::class ,'create'])->name('customers.create');
            Route::post('/store', [CustomerController::class ,'store'])->name('customers.store');
            Route::get('/edit', [CustomerController::class ,'edit'])->name('customers.edit');
            Route::post('/update', [CustomerController::class ,'update'])->name('customers.update');
            Route::get('/delete', [CustomerController::class ,'destroy'])->name('customers.delete');
        });
        
        Route::prefix('issues')->group(function () {
            Route::get('/', [IssueController::class, 'index'])->name('issues.index');
            Route::get('/history', [IssueController::class ,'history'])->name('issues.history');
            Route::get('/create', [IssueController::class ,'create'])->name('issues.create');
            Route::post('/store', [IssueController::class ,'store'])->name('issues.store');
            Route::get('/edit', [IssueController::class ,'edit'])->name('issues.edit');
            Route::post('/update', [IssueController::class ,'update'])->name('issues.update');
            Route::get('/delete', [IssueController::class ,'destroy'])->name('issues.delete');
        });
        
        Route::prefix('issue_returns')->group(function () {
            Route::get('/', [IssueReturnController::class, 'index'])->name('issue_returns.index');
            Route::get('/history', [IssueReturnController::class ,'history'])->name('issue_returns.history');
            Route::get('/create', [IssueReturnController::class ,'create'])->name('issue_returns.create');
            Route::post('/store', [IssueReturnController::class ,'store'])->name('issue_returns.store');
            Route::get('/edit', [IssueReturnController::class ,'edit'])->name('issue_returns.edit');
            Route::post('/update', [IssueReturnController::class ,'update'])->name('issue_returns.update');
            Route::get('/delete', [IssueReturnController::class ,'destroy'])->name('issue_returns.delete');
            //just for issues products lists
            Route::get('/code-under-shelfnum', [IssueReturnController::class ,'getCode'])->name('issue_returns.getCode');
            Route::get('/issues-lists', [IssueReturnController::class ,'getVr'])->name('issue_returns.getVr');
        });
        
        Route::prefix('brands')->group(function () {
            Route::get('/', [BrandController::class, 'index'])->name('brands.index');
            Route::post('/store', [BrandController::class ,'store'])->name('brands.store');
            Route::post('/update', [BrandController::class ,'update'])->name('brands.update');
            Route::get('/delete', [BrandController::class ,'destroy'])->name('brands.delete');
            Route::post('/excel', [BrandController::class ,'import'])->name('brands.import');
            Route::get('/sample', [BrandController::class ,'sample'])->name('brands.sample');
        });
        
        Route::prefix('commodities')->group(function () {
            Route::get('/', [CommodityController::class, 'index'])->name('commodities.index');
            Route::post('/store', [CommodityController::class ,'store'])->name('commodities.store');
            Route::post('/update', [CommodityController::class ,'update'])->name('commodities.update');
            Route::get('/delete', [CommodityController::class ,'destroy'])->name('commodities.delete');
            Route::post('/excel', [CommodityController::class ,'import'])->name('commodities.import');
            Route::get('/sample', [CommodityController::class ,'sample'])->name('commodities.sample');
        });
        
        Route::prefix('warehouses')->group(function () {
            Route::get('/', [WarehouseController::class, 'index'])->name('warehouses.index');
            Route::post('/store', [WarehouseController::class ,'store'])->name('warehouses.store');
            Route::post('/update', [WarehouseController::class ,'update'])->name('warehouses.update');
            Route::get('/delete', [WarehouseController::class ,'destroy'])->name('warehouses.delete');
        });
        
        Route::prefix('shelves')->group(function () {
            Route::get('/', [ShelfController::class, 'index'])->name('shelves.index');
            Route::post('/store', [ShelfController::class ,'store'])->name('shelves.store');
            Route::post('/update', [ShelfController::class ,'update'])->name('shelves.update');
            Route::get('/delete', [ShelfController::class ,'destroy'])->name('shelves.delete');
            
            Route::post('/excel', [ShelfController::class ,'import'])->name('shelves.import');
            Route::get('/sample', [ShelfController::class ,'sample'])->name('shelves.sample');
        });
        
        Route::prefix('shelf-nums')->group(function () {
            Route::get('/', [ShelfNumberController::class, 'index'])->name('shelf_nums.index');
            Route::post('/store', [ShelfNumberController::class ,'store'])->name('shelf_nums.store');
            Route::post('/update', [ShelfNumberController::class ,'update'])->name('shelf_nums.update');
            Route::get('/delete', [ShelfNumberController::class ,'destroy'])->name('shelf_nums.delete');
            Route::post('/excel', [ShelfNumberController::class ,'import'])->name('shelf_nums.import');  //transfers(not create)
            Route::get('/sample', [ShelfNumberController::class ,'sample'])->name('shelf_nums.sample');
            Route::get('/shelves-under-warehouses', [ShelfNumberController::class ,'warehouseShelves'])->name('shelf_nums.getShelf');
        });
        
        Route::prefix('codes')->group(function () {
            Route::get('/', [CodeController::class, 'index'])->name('codes.index');
            Route::post('/store', [CodeController::class ,'store'])->name('codes.store');
            Route::post('/update', [CodeController::class ,'update'])->name('codes.update');
            Route::get('/delete', [CodeController::class ,'destroy'])->name('codes.delete');
            Route::post('/excel', [CodeController::class ,'import'])->name('codes.import');
            Route::get('/sample', [CodeController::class ,'sample'])->name('codes.sample');
        });
        
        
        Route::prefix('units')->group(function () {
            Route::get('/', [UnitController::class, 'index'])->name('units.index');
            Route::post('/store', [UnitController::class ,'store'])->name('units.store');
            Route::post('/update', [UnitController::class ,'update'])->name('units.update');
            Route::get('/delete', [UnitController::class ,'destroy'])->name('units.delete');
        });
        
        Route::prefix('transfers')->group(function () {
            Route::get('/', [TransferController::class, 'index'])->name('transfers.index');
            Route::get('/history', [TransferController::class ,'history'])->name('transfers.history');
            Route::get('/create', [TransferController::class, 'create'])->name('transfers.create');
            Route::post('/store', [TransferController::class ,'store'])->name('transfers.store');
            Route::get('/edit', [TransferController::class ,'edit'])->name('transfers.edit');
            Route::post('/update', [TransferController::class ,'update'])->name('transfers.update');
            //issues
            Route::get('/code-under-shelfnum', [TransferController::class ,'getCode'])->name('transfers.getCode');
            Route::get('/voucher-lists', [TransferController::class ,'getVr'])->name('transfers.getVr');
        });
        
        Route::prefix('products')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('products.index');
            Route::get('/history', [ProductController::class ,'history'])->name('products.history');
            Route::get('/create', [ProductController::class ,'create'])->name('products.create');
            Route::post('/store', [ProductController::class ,'store'])->name('products.store');
            Route::get('/edit', [ProductController::class ,'edit'])->name('products.edit');
            Route::post('/update', [ProductController::class ,'update'])->name('products.update');
            Route::get('/printBarcode', [ProductController::class ,'printBarcode'])->name('products.printBarcode');
            //transfer, issues
            Route::get('/shelfno-under-shelf', [ProductController::class ,'getShelfNum'])->name('products.getShelfNum');
            Route::get('/fromCode', [ProductController::class ,'getFromCode'])->name('products.getFromCode');
            Route::get('/fromBrand', [ProductController::class ,'getFromBrand'])->name('products.getFromBrand');
            
            Route::post('/excel', [ProductController::class ,'import'])->name('products.import');
            Route::get('/sample', [ProductController::class, 'sample'])->name('products.sample');

            # backup route
            Route::get('/back-up', [ProductController::class, 'backup'])->name('products.backup');

            //barcode scanner transfer
            Route::get('/barcode-scanner', [BarCodeController::class, 'index'])->name('scanners.index');
            Route::get('/barcode-store', [BarCodeController::class, 'store'])->name('scanners.store');
            Route::get('/barcode-supplier', [BarCodeController::class, 'storeSupplier'])->name('scanners.storeSupplier');
            #issue
            Route::get('/barcode-store-mr', [BarCodeController::class, 'storeMR'])->name('scanners.storeMR');
            #fix
            Route::get('/barcode-store-mrr', [BarCodeController::class, 'storeMRR'])->name('scanners.storeMRR');
            

        });
        
        Route::prefix('adjustments')->group(function () {
            Route::get('/', [AdjustmentController::class, 'index'])->name('adjustments.index');
            Route::get('/history', [AdjustmentController::class ,'history'])->name('adjustments.history');
            Route::get('/create', [AdjustmentController::class ,'create'])->name('adjustments.create');
            Route::post('/store', [AdjustmentController::class ,'store'])->name('adjustments.store');
            Route::get('/edit', [AdjustmentController::class ,'edit'])->name('adjustments.edit');
            Route::post('/update', [AdjustmentController::class ,'update'])->name('adjustments.update');
        });
        
        Route::prefix('departments')->group(function () {
            Route::get('/', [DepartmentController::class, 'index'])->name('departments.index');
            Route::get('/create', [DepartmentController::class ,'create'])->name('departments.create');
            Route::post('/store', [DepartmentController::class ,'store'])->name('departments.store');
            Route::get('/edit', [DepartmentController::class ,'edit'])->name('departments.edit');
            Route::post('/update', [DepartmentController::class ,'update'])->name('departments.update');
            Route::get('/delete', [DepartmentController::class ,'destroy'])->name('departments.delete');
        });
});


Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

