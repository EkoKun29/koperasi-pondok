<?php
$setoran = explode(",", $_GET["SETORAN"]);
$result = array();
foreach ($setoran as $id => $key) {
    $setoran += (int) str_replace(['.', ','], '', $setoran[$id]);
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
            font-family: 'Courier New', monospace;
            font-size: 11px;
            margin: 0;
            padding: 0mm 0 0mm 0mm; /* TOP RIGHT BOTTOM LEFT */
            color: #000;
        }

        .center {
            text-align: center;
        }

        .dashed {
            border-top: 1px dashed black;
            margin: 6px 0;
        }

        .item-line {
    display: flex;
    justify-content: flex-start;
    align-items: flex-start;
    gap: 3mm; /* jarak aman */
}

.item-line span:first-child {
    flex: 1;
    max-width: 60%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.item-line span:last-child {
    min-width: 35%;
    text-align: right;
    padding-right: 5mm;
}

        .nota-section {
            margin-top: 5px;
        }

        .bold {
            font-weight: bold;
        }

        .thankyou {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="center">
        <div class="bold">MQ BAKERY</div>
        <div>NOTA SETORAN</div>
    </div>

    <div class="dashed"></div>

    <div class="nota-section">
        No Nota: <?= $_GET["NO"] ?><br>
        Tanggal: <?= date('d/m/Y', strtotime($_GET["TANGGAL"])) ?><br>
        Nama Tempat: <?= $_GET["NAMA_UNIT"] ?>
        Keterangan: <?= $_GET["KETERANGAN"] ?>
    </div>

    <div class="dashed"></div>


    <div class="nota-section bold item-line">
        <span>Total :</span>
        <span>Rp <?= number_format($setoran, 0, ',', '.') ?></span>
    </div>

    <div class="dashed"></div>

    <div class="thankyou">
        ** TERIMAKASIH **
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
