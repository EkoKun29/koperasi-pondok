<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Penjualan Acara</title>
    <style>
        * {
            font-size: 8px;
            font-family: 'Times New Roman';
        }

        /* td,A
        th,
        tr,
        table {
            border-top: 1px solid black;
            border-collapse: collapse;
        } */

        td.description,
        th.description {
            width: 65px;
            padding: 2px 0;
            max-width: 65px;
        }

        td.price,
        th.price {
            width: 85px;
            max-width: 95px;
            text-align: right;
            word-break: break-all;
        }

        .centered {
            text-align: center;
            align-content: center;
        }

        .ticket {
            width: 155px;
            max-width: 155px;
        }
        hr {
            border: none;
            border-top: 2px dashed black; /* Atur lebar dan gaya garis sesuai kebutuhan */
            height: 0;
    }
    </style>
</head>

<body>
    <div class="ticket">
        <p class="centered"><b><strong>PENJUALAN ACARA</strong></b>
            <br>
            <b><strong>NOTA PENJUALAN ACARA</strong></b>
        </p>
        <p style="font-size: 1.2em; ">
            <strong>
            <br>KARYAWAN : {{  $penjualanAcara->nama_personil }}
            <br>NOTA : {{ $penjualanAcara->no_nota }}
            <br>TANGGAL : {{ \Carbon\Carbon::parse($penjualanAcara->tanggal)->format('d-m-Y') }}
            <br>JENIS DATA : PENJUALAN ACARA
            </strong>
            <hr>
        </p>
        <table>
            <tbody>
                @foreach ($details as $item)
                <tr>
                    <td class="description" style="font-size: 1.2em; ">{{ $item->nama_barang }}</td>
                    <td class="description" style="text-align: right; font-size: 1.2em; ">Rp {{number_format($item->subtotal, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="description" style="font-size: 1.2em; ">{{ $item->qty }} {{ $item->keterangan }} x Rp {{number_format($item->harga, 2, ',', '.') }}</td>
                    <td class="description"></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <hr>
        <table>
            <tbody>
                <tr>
                    <td class="description" style="font-weight: bold; font-size: 1.8em;">Total : </td>
                    <td class="description" style="text-align: right; font-weight: bold; font-size: 1.8em;">Rp {{number_format($penjualanAcara->total, 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
        <hr>
        <p class="centered"><b>** TERIMAKASIH **</b>
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

