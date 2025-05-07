@extends('admin.layout')
@section('style')
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
@endsection
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 col-md-12 order-1">
                <div class="row">
                    <div class="col-lg-4 col-md-12 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <img src="{{asset('assets/img/icons/unicons/chart-success.png')}}" alt="chart success" class="rounded" />
                                    </div>
                                </div>
                                <span class="fw-semibold d-block mb-1">รายจ่ายวันนี้</span>
                                <h3 class="card-title mb-2">{{$expensesday->total}} บาท</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <img src="{{asset('assets/img/icons/unicons/chart-success.png')}}" alt="Credit Card" class="rounded" />
                                    </div>
                                </div>
                                <span class="fw-semibold d-block mb-1">รายจ่ายเดือนนี้</span>
                                <h3 class="card-title mb-2">{{$expensesmouth->total}} บาท</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <img src="{{asset('assets/img/icons/unicons/chart-success.png')}}" alt="Credit Card" class="rounded" />
                                    </div>
                                </div>
                                <span class="fw-semibold d-block mb-1">รายจ่ายปีนี้</span>
                                <h3 class="card-title mb-2">{{$expensesyear->total}} บาท</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 order-2">
                <div class="card">
                    <div class="card-header d-flex justify-content-end">
                        <a href="{{route('ExpensesCreate')}}" class="btn btn-sm btn-outline-success d-flex align-items-center" style="font-size:14px">เพิ่มค่าใช้จ่าย&nbsp;<i class="bx bxs-plus-circle"></i></a>
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-left">รายการค่าใช้จ่าย</th>
                                    <th class="text-left">หมวดหมู่</th>
                                    <th class="text-center">จำนวน (บาท)</th>
                                    <th class="text-center">วันที่จ่ายชำระ</th>
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
            ajax: {
                url: "{{route('expenseslistData')}}",
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },

            columns: [{
                    data: 'name',
                    class: 'text-left',
                    width: '30%'
                },
                {
                    data: 'category',
                    class: 'text-left',
                    width: '15%'
                },
                {
                    data: 'price',
                    class: 'text-center',
                    width: '15%'
                },
                {
                    data: 'date',
                    class: 'text-center',
                    width: '20%'
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
    $(document).on('click', '.deleteCategory', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        Swal.fire({
            title: "ท่านต้องการลบรายการนี้ใช่หรือไม่?",
            icon: "question",
            showDenyButton: true,
            confirmButtonText: "ตกลง",
            denyButtonText: `ยกเลิก`
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{route('ExpensesDelete')}}",
                    type: "post",
                    data: {
                        id: id
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status == true) {
                            Swal.fire(response.message, "", "success");
                            $('#myTable').DataTable().ajax.reload(null, false);
                        } else {
                            Swal.fire(response.message, "", "error");
                        }
                    }
                });
            }
        });
    });
</script>
@endsection