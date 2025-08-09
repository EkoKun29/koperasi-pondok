<!DOCTYPE html>
<html>
<head>
    <title>Print Label Cetak - {{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }}</title>
    <style>
        @page {
            size: 58mm auto;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0 3mm; /* beri jarak kiri-kanan */
            font-family: Arial, sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        td {
            font-size: 20px;
            text-align: center;
            height: 35mm;
            box-sizing: border-box;
            border: 2px solid black; /* border kotak */
            vertical-align: middle;
            padding: 0 2px;
            word-break: break-all;
            max-width: 48mm;
            white-space: normal;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            td {
                border-color: black !important;
            }
        }
    </style>
</head>
<body>
<div>
    <table>
        <tbody>
            @foreach($labels as $label)
            <tr>
                <td>{{ $label->label }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
if (window.Android) {
    window.Android.printPage();
} else {
    window.print();
}
</script>

</body>
</html>
