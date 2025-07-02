<?php
$setoranList = explode(",", $_GET["SETORAN"]);
$total = 0;

foreach ($setoranList as $val) {
    $total += (int) str_replace(['.', ','], '', $val);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Setoran</title>
    <style>
        @media print {
            @page {
                size: 58mm 3276mm;
                margin: 0;
            }
            body {
                margin: 0;
                padding: 0;
            }
        }

        body {
            width: 48mm;
            font-family: Consolas, 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .center {
            text-align: center;
        }

        .dashed {
            border-top: 2px dashed black;
            margin: 8px 0;
        }

        .bold {
            font-weight: bold;
        }

        .item-line {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .thankyou {
            text-align: center;
            margin-top: 15px;
        }

        .penerima {
            margin-top: 20px;
            text-align: left;
            padding-left: 5mm;
        }
    </style>
</head>
<body>
    <div class="center bold">MQ BAKERY</div>
    <div class="center">NOTA SETORAN</div>

    <div class="dashed"></div>

    <div class="nota-section">
        No. Nota : <?= $_GET["NO"] ?><br>
        Tanggal Transaksi : <?= date('d/m/Y', strtotime($_GET["TANGGAL"])) ?><br>
        Nama Tempat : <?= $_GET["NAMA_UNIT"] ?><br>
        Keterangan : <?= $_GET["KETERANGAN"] ?>
    </div>

    <div class="dashed"></div>

    <div class="item-line">
        <span>Total Setoran :</span>
        <span>Rp <?= number_format($total, 0, ',', '.') ?></span>
    </div>

    <div class="dashed"></div>

    <div class="penerima">
        Penerima,
    </div>
    <br><br>
        <hr>

    <div class="thankyou">
        ** TERIMAKASIH **
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
