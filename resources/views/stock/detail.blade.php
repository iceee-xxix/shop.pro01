@extends('admin.layout')
@section('style')
@section('style')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
@endsection
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 col-md-12 order-2 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h6>ความเคลื่อนไหวรายการสต็อก ({{$stock->name}})</h6>
                        <hr>
                    </div>
                    <div class="card-body">
                        <div style="width:100%;">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    const data = {
        labels: <?= json_encode($item_key) ?>,
        datasets: [{
            label: 'จำนวนสต็อก (กิโลกรัม)',
            data: <?= json_encode($item_list) ?>,
            borderColor: 'orange',
            backgroundColor: 'transparent',
            fill: false,
            tension: 0.2,
            pointRadius: 8
        }]
    };

    const configChart = {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    ticks: {
                        display: false // <<< ซ่อน A-F
                    },
                    grid: {
                        display: false // <<< ถ้าอยากให้สะอาด ไม่มีเส้นแนวตั้ง
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'จำนวนสต็อก (กก.)'
                    }
                }
            }
        }
    };

    new Chart(document.getElementById('myChart'), configChart);
</script>
@endsection