<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Koperasi Kampus {{ Auth::user()->role }}</title>
    <style>
        * {
            font-size: 8px;
            font-family: 'Times New Roman';
        }

        .signature-container {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .signature-line {
            margin-top: 50px;
            border-bottom: 1px solid black;
            width: 100%;
        }

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
        <p class="centered"><b><strong>KOPERASI KAMPUS {{ Auth::user()->role }}</strong></b>
            <br>
            <b><strong>NOTA SETORAN</strong></b>
        </p>
        <p style="font-size: 1.2em; ">
            <strong>
            <br>PENYETOR : {{  $setoran->penyetor }}
            {{-- <br>PENERIMA : {{ $setoran->penerima }} --}}
            <br>TANGGAL : {{ $setoran->tanggal}}
            <br>JENIS TRANSAKSI : SETORAN
            </strong>
            <hr>
        </p>

        <table>
            <tbody>
                <tr>
                    <td class="description" style="font-weight: bold; font-size: 1.8em;">Nominal Setoran: </td>
                    <td class="description" style="text-align: right; font-weight: bold; font-size: 1.8em;">Rp. {{number_format($setoran->nominal, 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
        <hr>
        <body>
    <div class="signature-container">
        <div class="signature-box">
            <p><strong>Penerima,</strong></p>
            <div class="signature-line"></div>
            <p></p>
        </div>
    </div>
</body>
        <p class="centered"><b>** TERIMAKASIH **</b>
    </div>
    <script>
        window.print();
    </script>
</body>

</html>

