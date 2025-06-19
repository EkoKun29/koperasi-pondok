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
<div>
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
</div>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        setTimeout(() => {
            if (window.Android && typeof window.Android.printPage === "function") {
                window.Android.printPage();
            } else {
                window.print();
            }
        }, 300);
    });
</script>

</body>
</html>
