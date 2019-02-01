<!doctype html>
<html>
<head>
    <title>Advertisement</title>
    <style>
        .record-table{
            font-size: 16px;
            width: 800px;
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
            <h2>Clique Offers Table</h2>
        </div>
    <table class="table table-bordered" style="width: 740px;">
        <thead>
        <tr>
            <th>App Store Link</th>
            <th>Play Store Link</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><img src="{{base_path().'/storage/app/public/qr-codes/app-store.png'}}" width="100"></td>
            <td><img src="{{base_path().'/storage/app/public/qr-codes/play-store.png'}}" width="100"></td>
        </tr>
        {{--@foreach($advertisements as $index=>$advertisement)
            <tr>
                <td><img src="{{base_path().'/storage/app/public/qr-codes/app-store.png'}}" width="100"></td>
                <td><img src="{{base_path().'/storage/app/public/qr-codes/play-store.png'}}" width="100"></td>
            </tr>
        @endforeach--}}
        </tbody>
    </table>
    <br><br>
        <table class="table table-bordered" style="width: 740px;">
            <thead>
            <tr>
                <th>Sr. Number</th>
                <th>Brand Name</th>
                <th>Brand Image</th>
                <th>Unique Code</th>
                <th>QR Code</th>
            </tr>
            </thead>
            <tbody>
           @foreach($advertisements as $index=>$advertisement)
            <tr>
                <td>{{$index+1}}</td>
                <td>{{$advertisement['brand_name']}}</td>
                <td><img src="{{base_path().'/storage/app/public/advertisement/'.$advertisement['photo']}}" width="80"></td>
                <td>{{$advertisement['code']}}</td>
                <td><img src="{{base_path().'/storage/app/public/qr-codes/'.$advertisement['qr_code'].'.bmp'}}" width="170"></td>
            </tr>
           @endforeach
            </tbody>
        </table>
</div><!-- record-table -->

</body>
</html>
