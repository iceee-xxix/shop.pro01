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
<div class="container my-4">
    <div class="d-flex flex-column align-items-center">
        <h2 class="mb-4">🛒 รายการอาหารที่สั่ง</h2>

        <div class="card w-100 shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>ออเดอร์ของคุณ</strong>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @foreach($orderlist as $rs)
                    <div class="list-group-item list-group-item-action mb-3 p-4 rounded border bg-light">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start">
                            <div class="mb-3 mb-md-0">
                                <h5 class="mb-3 fw-bold">เลขออเดอร์ #{{$rs->id}}</h5>
                                <div class="mb-2 d-flex">
                                    <div class="me-2 fw-bold" style="min-width: 110px;">สถานะออเดอร์:</div>
                                    <div class="text-muted"><?php
                                                            switch ($rs->status) {
                                                                case 1:
                                                                    echo 'กำลังทำอาหาร';
                                                                    break;
                                                                case 2:
                                                                    echo 'กำลังจัดส่ง';
                                                                    break;
                                                                case 3:
                                                                    echo 'จัดส่งสำเร็จ';
                                                                    break;

                                                                default:
                                                                    break;
                                                            } ?>
                                    </div>
                                </div>
                                <div class="mb-2 d-flex">
                                    <div class="me-2 fw-bold" style="min-width: 110px;">คนขับ:</div>
                                    <div class="text-muted">{{ $rs->name ?? '-' }}</div>
                                </div>
                                <div class="mb-2 d-flex">
                                    <div class="me-2 fw-bold" style="min-width: 110px;">เบอร์โทร:</div>
                                    <div class="text-muted">
                                        @if(!empty($rs->tel))
                                        <a href="tel:{{ $rs->tel }}" class="text-muted text-decoration-none">{{ $rs->tel }}</a>
                                        @else
                                        -
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="text-end mt-3 mt-md-0">
                                <button data-id="{{$rs->id}}" type="button" class="btn btn-sm btn-success modalShow m-1">
                                    ดูรายละเอียดออเดอร์
                                </button><br>
                                <small class="text-muted">ราคา: {{ number_format($rs->total ?? 0, 2) }} บาท</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($orderlist->isEmpty())
                <div class="text-center text-muted my-4">
                    ไม่มีรายการอาหารที่สั่ง
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).on('click', '.modalShow', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $.ajax({
            type: "post",
            url: "{{ route('delivery.listOrderDetail') }}",
            data: {
                id: id
            },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#modal-detail').modal('show');
                $('#body-html').html(response);
            }
        });
    });
</script>
<div class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" id="modal-detail">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">รายละเอียดออเดอร์</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="body-html">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>
@endsection