<!doctype html>
<html>
<head>
    <title>Coupons</title>
    <style>
        .record-table{
            font-size: 16px;
            width: 780px;
            margin: auto;
        }
        .table-heading{
            text-align:center;
        }
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 3px 10px;
            text-align:center;
        }

    </style>
</head>
<body>

<div class="record-table table-responsive">
        <div class="table-heading">
            <h2>Coupon Table</h2>
            <p>Zone Name: {{$zone}}</p>
            <p>Cluster Name: {{$disp_name}}</p>
        </div>
        <table class="table table-bordered" style="width: 760px;">
            <thead>
            <tr>
                <th>Sr. Number</th>
                <th>Dispensary Name</th>
                <th>QR Code</th>
            </tr>
            </thead>
            <tbody>
           @foreach($coupons as $index=>$coupon)
            <tr>
                <td>{{$index+1}}</td>
                <td>{{$coupon['dispensary_name']}}</td>
                <td><img src="{{base_path().'/storage/app/public/qr-codes/'.$coupon['qr_code']}}" width="100"><br>{{$coupon['code']}}</td>
            </tr>
           @endforeach
            </tbody>
        </table>
</div><!-- record-table -->

</body>
</html>
