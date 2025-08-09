<!DOCTYPE html>
<html>
<head>
    <title>Print Label Cetak - {{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }}</title>
    <style>
        @page {
            size: 58mm auto; /* Lebar fix 58mm */
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        td {
            font-size: 25px;
            text-align: center;
            height: 35mm; /* Tinggi label tetap */
            box-sizing: border-box;
            border-bottom: 2px solid black; /* Garis bawah tebal dan jelas */
            vertical-align: middle;
            padding: 0 4px;
            word-wrap: break-word;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            td {
                border-color: black !important; /* Pastikan border tidak hilang */
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
