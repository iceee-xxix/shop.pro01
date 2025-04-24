@extends('admin.layout')
@section('style')
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 col-md-12 order-1">
                <div class="row">
                    <div class="col-lg-3 col-md-12 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <img src="{{asset('assets/img/icons/unicons/chart-success.png')}}" alt="chart success" class="rounded" />
                                    </div>
                                </div>
                                <span class="fw-semibold d-block mb-1">ยอดขายวันนี้</span>
                                <h3 class="card-title mb-2">{{$orderday->total}} บาท</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <img src="{{asset('assets/img/icons/unicons/chart-success.png')}}" alt="Credit Card" class="rounded" />
                                    </div>
                                </div>
                                <span class="fw-semibold d-block mb-1">ยอดขายเดือนนี้</span>
                                <h3 class="card-title mb-2">{{$ordermouth->total}} บาท</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <img src="{{asset('assets/img/icons/unicons/chart-success.png')}}" alt="Credit Card" class="rounded" />
                                    </div>
                                </div>
                                <span class="fw-semibold d-block mb-1">ยอดขายปีนี้</span>
                                <h3 class="card-title mb-2">{{$orderyear->total}} บาท</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <img src="{{asset('assets/img/icons/unicons/chart-success.png')}}" alt="chart success" class="rounded" />
                                    </div>
                                </div>
                                <span class="fw-semibold d-block mb-1">ออเดอร์ทั้งหมด</span>
                                <h3 class="card-title mb-2">{{$ordertotal}} รายการ</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 order-2 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h6>สถิติเมนูขายดี</h6>
                        <hr>
                    </div>
                    <div class="card-body">
                        <div style="width:100%;">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 order-2 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h6>ยอดขายแต่ละเดือน</h6>
                        <hr>
                    </div>
                    <div class="card-body">
                        <div style="width:100%;">
                            <canvas id="myChart2"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 order-3">
                <div class="card">
                    <div class="card-header">
                        <h6>รายการออเดอร์ทั้งหมด</h6>
                        <hr>
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="display table-responsive">
                            <thead>
                                <tr>
                                    <th class="text-center">เลขโต้ะ</th>
                                    <th class="text-center">ยอดราคา</th>
                                    <th class="text-left">หมายเหตุ</th>
                                    <th class="text-left">วันที่สั่ง</th>
                                    <th class="text-center">สถานะ</th>
                                    <th class="text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
<div class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" id="modal-pay">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ชำระเงิน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body d-flex justify-content-center">
                        <div class="row">
                            <div class="col-12 text-center">
                                <h5>ยอดชำระ</h5>
                                <h1 class="text-success" id="totalPay"></h1>
                            </div>
                            <div class="col-12 d-flex justify-content-center mb-3" id="qr_code">
                            </div>
                        </div>
                        <input type="hidden" id="order_id">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirm_pay">ยืนยันชำระเงิน</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script>
    var language = '{{asset("assets/js/datatable-language.js")}}';
    $(document).ready(function() {
        $("#myTable").DataTable({
            language: {
                url: language,
            },
            processing: true,
            scrollX: true,
            order: [
                [3, 'desc']
            ],
            ajax: {
                url: "{{route('ListOrder')}}",
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
            },

            columns: [{
                    data: 'table_id',
                    class: 'text-center',
                    width: '10%'
                },
                {
                    data: 'total',
                    class: 'text-center',
                    width: '15%'
                },
                {
                    data: 'remark',
                    class: 'text-left',
                    width: '20%'
                },
                {
                    data: 'created',
                    class: 'text-center',
                    width: '20%'
                },
                {
                    data: 'status',
                    class: 'text-center',
                    width: '15%'
                },
                {
                    data: 'action',
                    class: 'text-center',
                    width: '20%',
                    orderable: false
                },
            ]
        });
    });
</script>
<script>
    $(document).on('click', '.modalShow', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $.ajax({
            type: "post",
            url: "{{ route('listOrderDetail') }}",
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

    $(document).on('click', '.modalPay', function(e) {
        var total = $(this).data('total');
        Swal.showLoading();
        $.ajax({
            type: "post",
            url: "{{ route('generateQr') }}",
            data: {
                total: total
            },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                Swal.close();
                $('#modal-pay').modal('show');
                $('#totalPay').html(total + ' บาท');
                $('#qr_code').html(response);
                $('#order_id').val($(this).data('id'));
            }
        });
    });
</script>
<script>
    function getRandomColor(opacity = 1) {
        const r = Math.floor(Math.random() * 255);
        const g = Math.floor(Math.random() * 255);
        const b = Math.floor(Math.random() * 255);
        return `rgba(${r}, ${g}, ${b}, ${opacity})`;
    }
    var ctx = document.getElementById('myChart').getContext('2d');

    var labels = <?= json_encode($item_menu) ?>;
    var backgroundColors = labels.map(() => getRandomColor(0.5));
    var borderColors = labels.map(() => getRandomColor(1));
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: '# จำนวนออเดอร์ (จำนวน)',
                data: <?= json_encode($item_order) ?>,
                backgroundColor: backgroundColors,
                borderColor: borderColors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    var ctx = document.getElementById('myChart2').getContext('2d');

    var labels = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
    var backgroundColors = labels.map(() => getRandomColor(0.5));
    var borderColors = labels.map(() => getRandomColor(1));
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: '# ยอดขาย (บาท)',
                data: <?= json_encode($item_mouth) ?>,
                backgroundColor: backgroundColors,
                borderColor: borderColors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    $('#confirm_pay').click(function(e) {
        e.preventDefault();
        var id = $('#order_id').val();
        $.ajax({
            url: "{{route('confirm_pay')}}",
            type: "post",
            data: {
                id: id
            },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#modal-pay').modal('hide')
                if (response.status == true) {
                    Swal.fire(response.message, "", "success");
                    $('#myTable').DataTable().ajax.reload(null, false);
                } else {
                    Swal.fire(response.message, "", "error");
                }
            }
        });
    });

    $('#modal-pay').on('hidden.bs.modal', function() {
        $('#order_id').val('');
    })
</script>
@endsection