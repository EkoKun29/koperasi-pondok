<!DOCTYPE html>
<html>
<head>
    <title>Print Label Cetak - {{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }}</title>
    <style>
        @media print {
            body {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

    <table border="1" cellpadding="10" cellspacing="0">
        <tbody>
            @foreach($labels as $index => $label)
                <tr>
    <td style="padding-top: 38px; padding-bottom: 38px; font-size: 39px; text-align:center;">
        {{ $label->label }}
    </td>
</tr>


            @endforeach
        </tbody>
    </table>
    <script>
        if (window.Android) {
            window.Android.printPage();
        } else {
            window.print();
        }
    </script>
</body>
</html>
