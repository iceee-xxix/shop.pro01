<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ใบเสร็จรับเงิน</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px 0;
            color: #2d2d2d;
            background: #ffffff;
            /* ชัดเจนว่าให้พื้นหลังขาว */
        }

        .receipt {
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
            /* จัดให้อยู่กึ่งกลางแนวนอน */
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 30px;
            border-radius: 5px;
        }

        .receipt h2 {
            text-align: center;
            margin-top: 5px;
            margin-bottom: 20px;
            font-weight: 600;
            color: #1e293b;
        }

        .receipt span {
            font-weight: 700;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 1px;
        }

        .header .info,
        .header .tax-label {
            display: table-cell;
            vertical-align: top;
        }

        .header .info {
            text-align: left;
        }

        .header .tax-label {
            text-align: right;
            font-weight: 600;
            color: #475569;
        }

        .info p {
            margin: 4px 0;
            font-size: 14px;
        }

        .detail p {
            margin: 4px 0;
            font-size: 14px;
            text-align: end;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 10px;
            font-size: 14px;
            border-bottom: 1px solid #e2e8f0;
        }

        th:nth-child(1),
        td:nth-child(1) {
            text-align: left;
            width: 60%;
        }

        th:nth-child(2),
        td:nth-child(2) {
            text-align: center;
            width: 10%;
        }

        th:nth-child(3),
        td:nth-child(3) {
            text-align: right;
            width: 30%;
        }

        .total {
            text-align: right;
            font-weight: 700;
            color: #1e293b;
            border-top: 2px solid #000;
            margin-top: 20px;
            padding-top: 12px;
            font-size: 16px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 13px;
            color: #64748b;
            line-height: 1.6;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #print-area,
            #print-area * {
                visibility: visible;
            }

            #print-area {
                position: absolute;
                top: 20;
                left: 0;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div id="print-area">
        <div class="receipt">
            <h2><span>{{$config->name}}</span></h2>
            <div class="header">
                <div class="info">
                    <p><strong>เลขที่ใบเสร็จ #{{$pay->payment_number}}</strong></p>
                    <p>วันที่: {{$pay->created_at}}</p>
                </div>
                <div class="detail">
                    <p><strong>ชื่อ: {{$get['name']}}</strong></p>
                    <p>เบอร์โทรศัพท์: {{$get['tel']}}</p>
                    <p>เลขประจำตัวผู้เสียภาษี: {{$get['tax_id']}}</p>
                    <p>ที่อยู่: {{$get['address']}}</p>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order as $rs)
                    <tr>
                        <td><?= $rs['menu']->name . ' (' . $rs['option']->type . ')' ?></td>
                        <td><?= $rs->quantity ?></td>
                        <td><?= number_format(($rs->quantity * $rs->price), '2') ?> ฿</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <p class="total">Total: <?= number_format($pay->total, '2') ?> ฿</p>
        </div>
    </div>
</body>

</html>
<script>
    window.print();
</script>