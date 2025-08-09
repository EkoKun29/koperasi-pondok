<!DOCTYPE html>
<html>
<head>
    <title>Print Label Cetak - {{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }}</title>
    <style>
        @media print {
            body {
                font-size: 14px;
            }

            td {
                font-size: 20px; /* Ukuran teks */
                text-align: center;
                height: 35mm; /* Tinggi label tetap */
                border: 1px solid black; /* Bisa dihapus kalau tidak mau garis */
                word-wrap: break-word; /* Pecah teks panjang */
                vertical-align: middle; /* Teks rata tengah secara vertikal */
            }
            @media print {
                body {
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }
                td {
                    border-color: black !important;
                    width: 58mm;
                }
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
    if (window.Android) {
        window.Android.printPage();
    } else {
        window.print();
    }
</script>


</body>
</html>
