<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Print Nota Pembelian</title>
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
        <p class="centered"><b><strong>NOTA PEMBELIAN</strong></b></p>
        <p style="font-size: 1.2em; ">
            <strong>
            <br>NOTA : {{ $pembelian->nota }}
            <br>TANGGAL : {{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d-m-Y') }}
            <br>SUPPLIER : {{ $pembelian->nama_supplier }}
            <br>PERSONIL : {{ $pembelian->nama_personil }}
            </strong>
            <hr>
        </p>
        <table>
            <tbody>
                @foreach ($detail as $item)
                <tr>
                    <td class="description" style="font-size: 1.2em; ">{{ $item->nama_barang }}</td>
                    <td class="description" style="text-align: right; font-size: 1.2em; ">Rp {{number_format($item->subtotal, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="description" style="font-size: 1.2em; ">{{ $item->qty }} x Rp {{number_format($item->harga, 2, ',', '.') }}</td>
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
                    <td class="description" style="text-align: right; font-weight: bold; font-size: 1.8em;">Rp {{number_format($pembelian->total, 2, ',', '.') }}</td>
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

