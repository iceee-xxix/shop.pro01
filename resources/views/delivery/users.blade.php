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
            <div class="card-header">ข้อมูลส่วนตัว</div>
            <div class="card-body">
                <form action="{{route('delivery.usersSave')}}" method="post">
                    @csrf
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label for="name" class="form-label d-flex justify-content-start">ชื่อ : </label>
                            <input type="text" class="form-control" id="name" name="name" value="{{Session::get('user')->name}}" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label for="email" class="form-label d-flex justify-content-start">อีเมล : </label>
                            <input type="text" class="form-control" id="email" name="email" value="{{Session::get('user')->email}}" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-outline-primary" type="submit">บันทึก</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header">ข้อมูลที่อยู่</div>
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        @foreach($address as $rs)
                        <div class="col-md-6 mb-3 d-flex">
                            <label class="card p-3 position-relative w-100" style="cursor:pointer;">
                                <div class="d-flex align-items-center gap-3">
                                    <input type="radio" class="form-check-input mt-0" name="address" onclick="change_is_use(this)" value="{{$rs->id}}" {{ ($rs->is_use == 1) ? 'checked' : ''}}>
                                    <div class="flex-grow-1 d-flex flex-column justify-content-center">
                                        <span class="fw-bold">{{$rs->name}}</span>
                                        <small class="text-muted">{{$rs->detail}}</small>
                                    </div>
                                </div>
                                <div class="position-absolute top-0 end-0 m-2">
                                    <a href="{{route('delivery.editaddress',$rs->id)}}" class="btn btn-sm btn-outline-primary">
                                        แก้ไข
                                    </a>
                                </div>
                            </label>
                        </div>
                        @endforeach
                        <div class="col-md-6 mb-3 d-flex">
                            <label class="card border-success p-3 position-relative w-100">
                                <div class="flex-grow-1 d-flex flex-column justify-content-center">
                                    <a href="{{route('delivery.createaddress')}}" style="text-decoration: none;">
                                        <span class="fw-bold text-success"><i class="fa fa-plus"></i> เพิ่มที่อยู่ใหม่</span>
                                    </a>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="{{route('admin.logout')}}" class="btn btn-sm btn-danger" type="button">ออกจากระบบ</a>
    </div>
</div>
<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if ($message = Session::get('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '{{ $message }}',
    })
</script>
@endif
@if($message = Session::get('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: '{{ $message }}',
    })
</script>
@endif
<script>
    function change_is_use(input) {
        var id = $(input).val();
        $.ajax({
            type: "post",
            url: "{{route('delivery.change')}}",
            data: {
                id: id
            },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#modal-qr').modal('show')
                $('#body-html').html(response);
            }
        });
    }
</script>
@endsection