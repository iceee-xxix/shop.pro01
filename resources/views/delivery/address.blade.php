@extends('layouts.delivery')

@section('title', 'หน้ารายละเอียด')

@section('content')
<?php

use App\Models\Config;

$config = Config::first();
?>
<style>
    .title-buy {
        font-size: 30px;
        font-weight: bold;
        color: <?= $config->color_font != '' ? $config->color_font : '#ffffff' ?>;
    }

    .title-list-buy {
        font-size: 25px;
        font-weight: bold;
    }

    .btn-plus {
        background: none;
        border: none;
        color: rgb(0, 156, 0);
        cursor: pointer;
        padding: 0;
        font-size: 12px;
        text-decoration: none;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .btn-plus:hover {
        color: rgb(185, 185, 185);
    }

    .btn-delete {
        background: none;
        border: none;
        color: rgb(192, 0, 0);
        cursor: pointer;
        padding: 0;
        font-size: 12px;
        text-decoration: none;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .btn-delete:hover {
        color: rgb(185, 185, 185);
    }

    .btn-aprove {
        background: linear-gradient(360deg, var(--primary-color), var(--sub-color));
        border-radius: 20px;
        border: 0px solid #0d9700;
        padding: 5px 0px;
        font-weight: bold;
        text-decoration: none;
        color: rgb(255, 255, 255);
        transition: background 0.3s ease;
    }

    .btn-aprove:hover {
        background: linear-gradient(360deg, var(--sub-color), var(--primary-color));
        cursor: pointer;
    }
</style>
<div class="container">
    <div class="d-flex flex-column justify-content-center gap-2">
        <div class="card">
            <div class="card-header">ข้อมูลที่อยู่</div>
            <div class="card-body">
                <form action="{{route('delivery.addressSave')}}" method="post">
                    @csrf
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <div id="map" style="width: 100%; height: 300px; position: relative; overflow: hidden;border-radius:5px;"></div>
                            <!-- <button type="button" class="btn btn-primary mt-2" onclick="markMyLocation()">ตำแหน่งของคุณ</button> -->
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label for="name" class="form-label d-flex justify-content-start">ชื่อสถานที่ : </label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label for="tel" class="form-label d-flex justify-content-start">เบอร์โทรศัพท์มือถือ : </label>
                            <input type="text" class="form-control" id="tel" name="tel" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="10" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label for="tel" class="form-label d-flex justify-content-start">รายละเอียด : </label>
                            <textarea rows="4" class="form-control" name="detail" id="detail" required></textarea>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-outline-primary" type="submit">บันทึก</button>
                        </div>
                    </div>
                    <input type="hidden" id="lat" name="lat">
                    <input type="hidden" id="lng" name="lng">
                </form>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB525cpMEpjYlG8HiWgBqPCbaZU6HHxprY&callback=loadMapWithLocation&signature=32RwW2GW7neU_vipuV3KKE4KFBw="></script>
<script>
    let map;
    let marker;

    function initMap(position) {
        const userLocation = {
            lat: position.coords.latitude,
            lng: position.coords.longitude,
        };

        map = new google.maps.Map(document.getElementById("map"), {
            center: userLocation,
            zoom: 15,
        });

        // วางมาร์คที่ตำแหน่งตัวเองตอนโหลด
        placeMarker(userLocation);
        loadLatLngInputs(userLocation);

        // ให้สามารถคลิกแผนที่เปลี่ยนจุดได้
        map.addListener("click", function(event) {
            placeMarker(event.latLng);
            updateLatLngInputs(event.latLng);
        });
    }

    function placeMarker(location) {
        if (marker) {
            marker.setPosition(location);
        } else {
            marker = new google.maps.Marker({
                position: location,
                map: map,
            });
        }
    }

    function loadLatLngInputs(latLng) {
        document.getElementById("lat").value = latLng.lat ? latLng.lat : latLng.lat;
        document.getElementById("lng").value = latLng.lng ? latLng.lng : latLng.lng;
    }

    function updateLatLngInputs(latLng) {
        document.getElementById("lat").value = latLng.lat ? latLng.lat() : latLng.lat;
        document.getElementById("lng").value = latLng.lng ? latLng.lng() : latLng.lng;
    }

    function loadMapWithLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                initMap,
                function() {
                    alert("ไม่สามารถดึงตำแหน่งของคุณได้");
                }
            );
        } else {
            alert("เบราว์เซอร์ไม่รองรับ Geolocation");
        }
    }
</script>
@endsection