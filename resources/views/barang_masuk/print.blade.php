<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Print Barang Masuk</title>
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
        <p class="centered"><b><strong>NOTA BARANG MASUK</strong></b></p>
        <p style="font-size: 1.2em; ">
            <strong>
            <br>NOTA : {{ $barangMasuk->nota }}
            <br>TANGGAL MASUK: {{ \Carbon\Carbon::parse($barangMasuk->created_at)->format('d-m-Y') }}
            <br>TANGGAL PEMBELIAN: {{ \Carbon\Carbon::parse($barangMasuk->tanggal)->format('d-m-Y') }}
            <br>PERSONIL : {{ $barangMasuk->nama_personil }}
            <br>MASUK KE- : {{ $barangMasuk->masuk_ke }}
            </strong>
        </p>
        <hr style="border: none; border-top: 2px dashed black; margin: 10px 0;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="border: 1px solid black;">Nama Barang</th>
                    <th style="border: 1px solid black;">Qty</th>
                    <th style="border: 1px solid black;">Satuan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detail as $item)
                <tr>
                    <td style="border: 1px solid black;">{{ $item->nama_barang }}</td>
                    <td style="border: 1px solid black;">{{ $item->qty }}</td>
                    <td style="border: 1px solid black;">{{ $item->satuan }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="3" style="text-align:center;">------------------------------</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align:center;"><b>** TERIMAKASIH **</b></td>
                </tr>
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

