<?php

use App\Http\Controllers\admin\Admin;
use App\Http\Controllers\admin\Category;
use App\Http\Controllers\admin\Menu;
use App\Http\Controllers\admin\Promotion;
use App\Http\Controllers\admin\Table;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Main;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [Main::class, 'index'])->name('index');
Route::get('/order', [Main::class, 'order'])->name('order');
Route::post('/sendEmp', [Main::class, 'sendEmp'])->name('sendEmp');
Route::post('/sendorder', [Main::class, 'SendOrder'])->name('SendOrder');
Route::get('/detail/{id}', [Main::class, 'detail'])->name('detail');
Route::get('/detail', function () {
    return redirect()->route('index');
});
Route::get('/buy', function () {
    return view('users.list_page');
});
Route::get('/total', function () {
    return view('index');
});

Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/admin/auth', [AuthController::class, 'login']);
Route::get('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['checkLogin'])->name('admin');

Route::middleware('checkLogin')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('checkLogin')->group(function () {
    Route::get('/admin', [Admin::class, 'dashboard'])->name('dashboard');
    //datatable Order
    Route::post('/admin/order/listData', [Admin::class, 'ListOrder'])->name('ListOrder');
    Route::post('/admin/order/listOrderDetail', [Admin::class, 'listOrderDetail'])->name('listOrderDetail');
    Route::post('/admin/order/generateQr', [Admin::class, 'generateQr'])->name('generateQr');
    Route::post('/admin/order/confirm_pay', [Admin::class, 'confirm_pay'])->name('confirm_pay');
    //ตั้งค่าเว็บไซต์
    Route::get('/admin/config', [Admin::class, 'config'])->name('config');
    Route::post('/admin/config/save', [Admin::class, 'ConfigSave'])->name('ConfigSave');
    //โปรโมชั่น
    Route::get('/admin/promotion', [Promotion::class, 'promotion'])->name('promotion');
    Route::post('/admin/promotion/listData', [Promotion::class, 'promotionlistData'])->name('promotionlistData');
    Route::get('/admin/promotion/create', [Promotion::class, 'promotionCreate'])->name('promotionCreate');
    Route::post('/admin/promotion/save', [Promotion::class, 'promotionSave'])->name('promotionSave');
    Route::post('/admin/promotion/delete', [Promotion::class, 'promotionDelete'])->name('promotionDelete');
    Route::post('/admin/promotion/status', [Promotion::class, 'changeStatusPromotion'])->name('changeStatusPromotion');
    Route::get('/admin/promotion/edit/{id}', [Promotion::class, 'promotionEdit'])->name('promotionEdit');
    //จัดการโต้ะและเพิ่ม Qr code
    Route::get('/admin/table', [Table::class, 'table'])->name('table');
    Route::post('/admin/table/listData', [Table::class, 'tablelistData'])->name('tablelistData');
    Route::post('/admin/table/QRshow', [Table::class, 'QRshow'])->name('QRshow');
    Route::get('/admin/table/create', [Table::class, 'tableCreate'])->name('tableCreate');
    Route::get('/admin/table/edit/{id}', [Table::class, 'tableEdit'])->name('tableEdit');
    Route::post('/admin/table/delete', [Table::class, 'tableDelete'])->name('tableDelete');
    Route::post('/admin/table/save', [Table::class, 'tableSave'])->name('tableSave');
    //หมวดหมู่
    Route::get('/admin/category', [Category::class, 'category'])->name('category');
    Route::post('/admin/category/listData', [Category::class, 'categorylistData'])->name('categorylistData');
    Route::get('/admin/category/create', [Category::class, 'CategoryCreate'])->name('CategoryCreate');
    Route::get('/admin/category/edit/{id}', [Category::class, 'CategoryEdit'])->name('CategoryEdit');
    Route::post('/admin/category/delete', [Category::class, 'CategoryDelete'])->name('CategoryDelete');
    Route::post('/admin/category/save', [Category::class, 'CategorySave'])->name('CategorySave');

    //เมนูอาหาร
    Route::get('/admin/menu', [Menu::class, 'menu'])->name('menu');
    Route::post('/admin/menu/menulistData', [Menu::class, 'menulistData'])->name('menulistData');
    Route::get('/admin/menu/create', [Menu::class, 'MenuCreate'])->name('MenuCreate');
    Route::get('/admin/menu/edit/{id}', [Menu::class, 'menuEdit'])->name('menuEdit');
    Route::post('/admin/menu/delete', [Menu::class, 'menuDelete'])->name('menuDelete');
    Route::post('/admin/menu/save', [Menu::class, 'menuSave'])->name('menuSave');
    //กำหนดราคาอาหาร
    Route::get('/admin/menuOption/{id}', [Menu::class, 'menuOption'])->name('menuOption');
    Route::post('/admin/menu/menulistOption', [Menu::class, 'menulistOption'])->name('menulistOption');
    Route::get('/admin/menu/menulistOptionCreate/{id}', [Menu::class, 'menulistOptionCreate'])->name('menulistOptionCreate');
    Route::post('/admin/menu/menuOptionSave', [Menu::class, 'menuOptionSave'])->name('menuOptionSave');
    Route::post('/admin/menu/menuOptionUpdate', [Menu::class, 'menuOptionUpdate'])->name('menuOptionUpdate');
    Route::get('/admin/menu/menuOptionEdit/{id}', [Menu::class, 'menuOptionEdit'])->name('menuOptionEdit');
});


require __DIR__ . '/auth.php';
