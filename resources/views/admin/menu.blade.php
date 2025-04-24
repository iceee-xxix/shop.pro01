<style>
    .bg-menu-theme .menu-header:before {
        width: 0rem !important;
    }
</style>
<?php

use App\Models\Config;

$config = Config::first();
?>
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{route('dashboard')}}" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bolder"><?= $config->name ?></span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
        <li class="menu-item {{ ($function_key == 'dashboard') ? 'active' : '' }}">
            <a href="{{route('dashboard')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-dashboard"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>
        <li class="menu-header small text-uppercase"><span class="menu-header-text">ตั้งค่า</span></li>
        <li class="menu-item {{ ($function_key == 'config') ? 'active' : '' }}">
            <a href="{{route('config')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div data-i18n="Analytics">ตั้งค่าเว็บไซต์</div>
            </a>
        </li>
        <li class="menu-item {{ ($function_key == 'promotion') ? 'active' : '' }}">
            <a href="{{route('promotion')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-megaphone"></i>
                <div data-i18n="Analytics">โปรโมชั่น</div>
            </a>
        </li>
        <li class="menu-item {{ ($function_key == 'table') ? 'active' : '' }}">
            <a href="{{route('table')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-barcode"></i>
                <div data-i18n="Analytics">จัดการโต้ะ</div>
            </a>
        </li>
        <li class="menu-header small text-uppercase"><span class="menu-header-text">อาหาร</span></li>
        <li class="menu-item {{ ($function_key == 'category') ? 'active' : '' }}">
            <a href="{{route('category')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-collection"></i>
                <div data-i18n="Basic">หมวดหมู่</div>
            </a>
        </li>
        <li class="menu-item {{ ($function_key == 'menu') ? 'active' : '' }}">
            <a href="{{route('menu')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-food-menu"></i>
                <div data-i18n="Basic">เมนูอาหาร</div>
            </a>
        </li>
    </ul>
</aside>