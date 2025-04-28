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
        <div class="title-buy">
            คำสั่งซื้อ
        </div>
        @if(Session::get('user'))
        <div class="card">
            <div class="card-header">ข้อมูลที่อยู่</div>
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        @if(count($address) > 0)
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
                            </label>
                        </div>
                        @endforeach
                        @else
                        <div class="col-md-6 mb-3 d-flex">
                            <label class="card border-success p-3 position-relative w-100">
                                <div class="flex-grow-1 d-flex flex-column justify-content-center">
                                    <a href="{{route('delivery.createaddress')}}" style="text-decoration: none;">
                                        <span class="fw-bold text-success"><i class="fa fa-plus"></i> เพิ่มที่อยู่ใหม่</span>
                                    </a>
                                </div>
                            </label>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="bg-white px-2 pt-3 shadow-lg d-flex flex-column aling-items-center justify-content-center"
            style="border-radius: 10px;">
            <div class="title-list-buy">
                รายการอาหารที่สั่ง
            </div>
            <div id="order-summary" class="mt-2"></div>
            <div class="fw-bold fs-5 mt-5 " style="border-top:2px solid #7e7e7e; margin-bottom:-10px;">
                ยอดชำระ
            </div>
            <div class="fw-bold text-center" style="font-size:45px; ">
                <span id="total-price" style="color: #0d9700"></span><span class="text-dark ms-2">บาท</span>
            </div>
        </div>
        <div class="bg-white p-2 shadow-lg mt-3" style="border-radius:20px;">
            <textarea class="form-control fw-bold text-center bg-white p-2" style="border-radius: 20px;" rows="4"
                id="remark" placeholder="หมายเหตุ(ความต้องการเพิ่มเติม)">
</textarea>
        </div>
        @if(Session::get('user'))
        <a href="javascript:void(0);" class="btn-aprove mt-3" id="confirm-order-btn"
            style="display: none;">ยืนยันคำสั่งซื้อ</a>
        @endif
    </div>
</div>
<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let orderData = JSON.parse(localStorage.getItem('orderData')) || {};
        const container = document.getElementById('order-summary');
        const totalPriceEl = document.getElementById('total-price');

        function renderOrderList() {
            container.innerHTML = '';
            let total = 0;

            if (Object.keys(orderData).length === 0) {
                const noItemsMessage = document.createElement('div');
                noItemsMessage.textContent = "ท่านยังไม่ได้เลือกสินค้า";
                container.appendChild(noItemsMessage);
            } else {
                for (const name in orderData) {
                    for (const type in orderData[name]) {
                        const item = orderData[name][type];
                        if (item.qty > 0) {
                            const el = document.createElement('div');
                            el.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'mb-1');

                            const itemText = document.createElement('div');
                            itemText.textContent = `${name} (${type}) x${item.qty}`;

                            const rightSide = document.createElement('div');
                            rightSide.classList.add('d-flex', 'align-items-center', 'gap-2');

                            const priceText = document.createElement('div');
                            priceText.textContent = `${item.qty * item.price}`;

                            const deleteBtn = document.createElement('button');
                            deleteBtn.classList.add('btn-delete');
                            deleteBtn.textContent = 'ลบ';
                            deleteBtn.onclick = () => {
                                item.qty -= 1;

                                if (item.qty <= 0) {
                                    delete orderData[name][type];
                                    if (Object.keys(orderData[name]).length === 0) {
                                        delete orderData[name];
                                    }
                                }

                                localStorage.setItem('orderData', JSON.stringify(orderData));
                                renderOrderList();
                            };

                            const plusBtn = document.createElement('button');
                            plusBtn.classList.add('btn-plus');
                            plusBtn.textContent = 'เพิ่ม';
                            plusBtn.onclick = () => {
                                item.qty += 1;
                                localStorage.setItem('orderData', JSON.stringify(orderData));
                                renderOrderList();
                            };

                            rightSide.appendChild(priceText);
                            rightSide.appendChild(plusBtn);
                            rightSide.appendChild(deleteBtn);

                            el.appendChild(itemText);
                            el.appendChild(rightSide);
                            container.appendChild(el);

                            total += item.qty * item.price;
                        }
                    }
                }
            }

            totalPriceEl.textContent = `${total}`;
        }

        renderOrderList();
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const orderData = JSON.parse(localStorage.getItem('orderData')) || {};

        const confirmButton = document.getElementById('confirm-order-btn');

        if (Object.keys(orderData).length > 0) {
            confirmButton.style.display = 'inline-block';
        }

        confirmButton.addEventListener('click', function(event) {
            event.preventDefault();
            if (Object.keys(orderData).length > 0) {
                $.ajax({
                    type: "post",
                    url: "{{ route('delivery.SendOrder') }}",
                    data: {
                        orderData: orderData,
                        remark: $('#remark').val()
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status == true) {
                            Swal.fire(response.message, "", "success");
                            localStorage.removeItem('orderData');
                            setTimeout(() => {
                                location.reload();
                            }, 3000);
                        } else {
                            Swal.fire(response.message, "", "error");
                        }
                    }
                });
            }
        });
    });
</script>
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