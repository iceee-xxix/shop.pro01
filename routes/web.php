<?php

use App\Http\Controllers\admin\Admin;
use App\Http\Controllers\admin\Category;
use App\Http\Controllers\admin\CategoryExpenses;
use App\Http\Controllers\admin\Expenses;
use App\Http\Controllers\admin\Menu;
use App\Http\Controllers\admin\Promotion;
use App\Http\Controllers\admin\Table;
use App\Http\Controllers\admin\Rider;
use App\Http\Controllers\admin\Stock;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Delivery;
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
//สั่งจากที่ร้าน
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
//สั่ง delivery
Route::get('/delivery', [Delivery::class, 'index'])->name('index');
Route::get('/delivery/login', [Delivery::class, 'login'])->name('delivery.login');
Route::get('/delivery/register', [Delivery::class, 'register'])->name('delivery.register');
Route::post('/delivery/UsersRegister', [Delivery::class, 'UsersRegister'])->name('delivery.UsersRegister');
Route::get('/delivery/detail/{id}', [Delivery::class, 'detail'])->name('delivery.detail');
Route::get('/delivery/order', [Delivery::class, 'order'])->name('delivery.order');
Route::post('/delivery/sendEmp', [Delivery::class, 'sendEmp'])->name('delivery.sendEmp');
Route::post('/delivery/sendorder', [Delivery::class, 'SendOrder'])->name('delivery.SendOrder');

Route::middleware(['role:user'])->group(function () {
    Route::get('/delivery/users', [Delivery::class, 'users'])->name('delivery.users');
    Route::post('/delivery/usersSave', [Delivery::class, 'usersSave'])->name('delivery.usersSave');
    Route::get('/delivery/createaddress', [Delivery::class, 'createaddress'])->name('delivery.createaddress');
    Route::get('/delivery/editaddress/{id}', [Delivery::class, 'editaddress'])->name('delivery.editaddress');
    Route::post('/delivery/addressSave', [Delivery::class, 'addressSave'])->name('delivery.addressSave');
    Route::post('/delivery/change', [Delivery::class, 'change'])->name('delivery.change');
    Route::get('/delivery/listorder', [Delivery::class, 'listorder'])->name('delivery.listorder');
    Route::post('/delivery/listOrderDetail', [Delivery::class, 'listOrderDetail'])->name('delivery.listOrderDetail');
});
//admin
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

Route::middleware(['role:admin'])->group(function () {
    Route::get('/admin', [Admin::class, 'dashboard'])->name('dashboard');
    //datatable Order
    Route::get('/admin/order', [Admin::class, 'order'])->name('adminorder');
    Route::post('/admin/order/listData', [Admin::class, 'ListOrder'])->name('ListOrder');
    Route::post('/admin/order/ListOrderPay', [Admin::class, 'ListOrderPay'])->name('ListOrderPay');
    Route::post('/admin/order/ListOrderPayRider', [Admin::class, 'ListOrderPayRider'])->name('ListOrderPayRider');
    Route::post('/admin/order/listOrderDetail', [Admin::class, 'listOrderDetail'])->name('listOrderDetail');
    Route::post('/admin/order/listOrderDetailRider', [Admin::class, 'listOrderDetailRider'])->name('listOrderDetailRider');
    Route::post('/admin/order/listOrderDetailPay', [Admin::class, 'listOrderDetailPay'])->name('listOrderDetailPay');
    Route::post('/admin/order/generateQr', [Admin::class, 'generateQr'])->name('generateQr');
    Route::post('/admin/order/confirm_pay', [Admin::class, 'confirm_pay'])->name('confirm_pay');
    Route::post('/admin/order/confirm_rider', [Admin::class, 'confirm_rider'])->name('confirm_rider');
    Route::get('/admin/order/printReceipt/{id}', [Admin::class, 'printReceipt'])->name('printReceipt');
    Route::get('/admin/order/printReceiptfull/{id}', [Admin::class, 'printReceiptfull'])->name('printReceiptfull');
    Route::get('/admin/order_rider', [Admin::class, 'order_rider'])->name('order_rider');
    Route::post('/admin/order/ListOrderRider', [Admin::class, 'ListOrderRider'])->name('ListOrderRider');
    //Cancel
    Route::post('/admin/order/cancelOrder', [Admin::class, 'cancelOrder'])->name('cancelOrder');
    Route::post('/admin/order/cancelMenu', [Admin::class, 'cancelMenu'])->name('cancelMenu');
    //update-status
    Route::post('/admin/order/updatestatus', [Admin::class, 'updatestatus'])->name('updatestatus');
    Route::post('/admin/order/updatestatusOrder', [Admin::class, 'updatestatusOrder'])->name('updatestatusOrder');
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
    //ไรเดอร์
    Route::get('/admin/rider', [Rider::class, 'rider'])->name('rider');
    Route::post('/admin/rider/listData', [Rider::class, 'riderlistData'])->name('riderlistData');
    Route::get('/admin/rider/create', [Rider::class, 'riderCreate'])->name('riderCreate');
    Route::get('/admin/rider/edit/{id}', [Rider::class, 'riderEdit'])->name('riderEdit');
    Route::post('/admin/rider/delete', [Rider::class, 'riderDelete'])->name('riderDelete');
    Route::post('/admin/rider/save', [Rider::class, 'riderSave'])->name('riderSave');
    Route::post('/admin/order/Riderconfirm_pay', [Rider::class, 'Riderconfirm_pay'])->name('Riderconfirm_pay');
    //จัดการโต้ะและเพิ่ม Qr code
    Route::get('/admin/OrderRider', [Rider::class, 'OrderRider'])->name('OrderRider');
    Route::post('/admin/OrderRider/listData', [Rider::class, 'OrderRiderlistData'])->name('OrderRiderlistData');

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
    Route::post('/admin/menu/menuOptionEdit/delete', [Menu::class, 'menuOptionDelete'])->name('menuOptionDelete');
    //สต็อกสินค้า
    Route::get('/admin/stock', [Stock::class, 'stock'])->name('stock');
    Route::post('/admin/stock/stocklistData', [Stock::class, 'stocklistData'])->name('stocklistData');
    Route::get('/admin/stock/create', [Stock::class, 'stockCreate'])->name('stockCreate');
    Route::post('/admin/stock/save', [Stock::class, 'stockSave'])->name('stockSave');
    Route::get('/admin/stock/edit/{id}', [Stock::class, 'stockEdit'])->name('stockEdit');
    Route::post('/admin/stock/delete', [Stock::class, 'stockDelete'])->name('stockDelete');
    //ผูกสต็อก
    Route::get('/admin/stock/menuOptionStock/{id}', [Stock::class, 'menuOptionStock'])->name('menuOptionStock');
    Route::post('/admin/stock/menustocklistData', [Stock::class, 'menustocklistData'])->name('menustocklistData');
    Route::get('/admin/stock/menustockCreate/{id}', [Stock::class, 'menustockCreate'])->name('menustockCreate');
    Route::get('/admin/stock/menuStockedit/{id}', [Stock::class, 'menuStockedit'])->name('menuStockedit');
    Route::post('/admin/stock/menustockSave', [Stock::class, 'menustockSave'])->name('menustockSave');
    Route::post('/admin/stock/menustockDelete', [Stock::class, 'menustockDelete'])->name('menustockDelete');
    Route::get('/admin/stock/stockDetail/{id}', [Stock::class, 'stockDetail'])->name('stockDetail');
    //หมวดหมู่รายจ่าย
    Route::get('/admin/category_expenses', [CategoryExpenses::class, 'category_expenses'])->name('category_expenses');
    Route::post('/admin/category_expenses/categoryexpenseslistData', [CategoryExpenses::class, 'categoryexpenseslistData'])->name('categoryexpenseslistData');
    Route::get('/admin/category_expenses/create', [CategoryExpenses::class, 'CategoryExpensesCreate'])->name('CategoryExpensesCreate');
    Route::get('/admin/category_expenses/edit/{id}', [CategoryExpenses::class, 'CategoryExpensesEdit'])->name('CategoryExpensesEdit');
    Route::post('/admin/category_expenses/delete', [CategoryExpenses::class, 'CategoryExpensesDelete'])->name('CategoryExpensesDelete');
    Route::post('/admin/category_expenses/save', [CategoryExpenses::class, 'CategoryExpensesSave'])->name('CategoryExpensesSave');
    //รายจ่าย
    Route::get('/admin/expenses', [Expenses::class, 'expenses'])->name('expenses');
    Route::post('/admin/expenses/expenseslistData', [Expenses::class, 'expenseslistData'])->name('expenseslistData');
    Route::get('/admin/expenses/create', [Expenses::class, 'ExpensesCreate'])->name('ExpensesCreate');
    Route::get('/admin/expenses/edit/{id}', [Expenses::class, 'ExpensesEdit'])->name('ExpensesEdit');
    Route::post('/admin/expenses/save', [Expenses::class, 'ExpensesSave'])->name('ExpensesSave');
    Route::post('/admin/expenses/delete', [Expenses::class, 'ExpensesDelete'])->name('ExpensesDelete');
});


require __DIR__ . '/auth.php';
