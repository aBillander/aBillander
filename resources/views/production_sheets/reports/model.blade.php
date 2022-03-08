<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fireguard</title>
    <!--<link href="css/bootstrap.min.css" rel="stylesheet">-->
    <!-- https://github.com/fireguard/report/blob/master/examples/report1.html -->
    <style>
        body {
            font-size: 11px;
        }
        table {
            border-collapse: collapse;
            border-spacing: 0;
            padding: 5px;
            line-height: 1.42857143;
            vertical-align: middle;
            border-top: 1px solid #ddd;
            width: 100%;
        }
        .table>thead>tr>th {
            vertical-align: bottom;
            border-bottom: 2px solid #ddd;
        }
        td, th {
            text-align: right;
        }
        th.identy{
            text-align: left;
        }
        .table>thead>tr>td.active, .table>tbody>tr>td.active, .table>tfoot>tr>td.active,
        .table>thead>tr>th.active, .table>tbody>tr>th.active, .table>tfoot>tr>th.active,
        .table>thead>tr.active>td, .table>tbody>tr.active>td, .table>tfoot>tr.active>td,
        .table>thead>tr.active>th, .table>tbody>tr.active>th, .table>tfoot>tr.active>th {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
<div class="table-responsive small">
    <table class="table table-condensed ">
        <thead>
        <tr>
            <th class="key">Days</th>
            <th>In Sales Office</th>
            <th>Out Sales Office</th>
            <th>In Office Visits</th>
            <th>Out Side Calls</th>
            <th>Others</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row" class="key">Monday</th>
            <td>$14.00</td>
            <td>$23.00</td>
            <td>$4.00</td>
            <td>$45.00</td>
            <td>$10.00</td>
            <td>$96.00</td>
        </tr>
        <tr>
            <th scope="row" class="key">Tuesday</th>
            <td>$14.00</td>
            <td>$23.00</td>
            <td>$4.00</td>
            <td>$45.00</td>
            <td>$10.00</td>
            <td>$96.00</td>
        </tr>
        <tr class="success">
            <th scope="row" class="key">Wednesday</th>
            <td>$14.00</td>
            <td>$23.00</td>
            <td>$4.00</td>
            <td>$45.00</td>
            <td>$10.00</td>
            <td>$96.00</td>
        </tr>
        <tr>
            <th scope="row" class="key">Thursday</th>
            <td>$14.00</td>
            <td>$23.00</td>
            <td>$4.00</td>
            <td>$45.00</td>
            <td>$10.00</td>
            <td>$96.00</td>
        </tr>
        <tr class="info">
            <th scope="row" class="key">Friday</th>
            <td>$14.00</td>
            <td>$23.00</td>
            <td>$4.00</td>
            <td>$45.00</td>
            <td>$10.00</td>
            <td>$96.00</td>
        </tr>
        <tr>
            <th scope="row" class="key">Saturday</th>
            <td>$14.00</td>
            <td>$23.00</td>
            <td>$4.00</td>
            <td>$45.00</td>
            <td>$10.00</td>
            <td>$96.00</td>
        </tr>
        <tr class="warning">
            <th scope="row" class="key">Sunday</th>
            <td>$14.00</td>
            <td>$23.00</td>
            <td>$4.00</td>
            <td>$45.00</td>
            <td>$10.00</td>
            <td>$96.00</td>
        </tr>
        <tr class="active">
            <th scope="row" class="key">Total</th>
            <td>$14.00</td>
            <td>$23.00</td>
            <td>$4.00</td>
            <td>$45.00</td>
            <td>$10.00</td>
            <td>$96.00</td>
        </tr>
        </tbody>
    </table>
</div>
<script>

</script>
</body>
</html>
