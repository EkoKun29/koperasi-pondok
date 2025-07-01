<?php
$barang = explode(",", $_GET["BARANG"]);
$jumlah = explode(",", $_GET["JUMLAH"]);
$harga = explode(",", $_GET["HARGA"]);
$subtotal = explode(",", $_GET["TOTAL_HARGA"]);
$result = array();
foreach ($barang as $id => $key) {
    $total += (int) str_replace(['.', ','], '', $subtotal[$id]);

    $result[$key] =array(
        'barang' => $barang[$id],
        'jumlah' => $jumlah[$id],
        'harga' => $harga[$id],
        'subtotal' => $subtotal[$id],
    );
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Penjualan</title>
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
    justify-content: space-between;
    align-items: flex-start;
    gap: 5mm;
    flex-wrap: wrap; /* penting untuk membungkus saat tidak cukup */
}

.item-name {
    flex: 1 1 60%;
    white-space: normal;
    word-break: break-word;
}

.item-price {
    min-width: 35%;
    text-align: right;
    padding-right: 5mm;
    white-space: nowrap;
}

.item-sub {
    margin-top: 1mm;
    font-size: 90%;
    color: #444;
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
        <div>NOTA PENJUALAN</div>
    </div>

    <div class="dashed"></div>

    <div class="nota-section">
        Tanggal: <?= date('d/m/Y', strtotime($_GET["TANGGAL"])) ?><br>
        No Nota: <?= $_GET["NO"] ?><br>
        Nama Pembeli: <?= $_GET["NAMA_PEMBELI"] ?>
    </div>

    <div class="dashed"></div>

   <?php
$total = 0;

foreach ($result as $row) {
    echo '
    <div class="nota-section">
    <div class="item-line">
        <div class="item-name">' . htmlspecialchars($row['barang']) . '</div>
        <div class="item-price">Rp ' . number_format($row['subtotal'], 0, ',', '.') . '</div>
    </div>
    <div class="item-sub"> ' . $row['jumlah'] . ' x Rp ' . number_format($row['harga'], 0, ',', '.') . '</div>
</div>
';
    
    $total += $row['subtotal'];
}
?>


    <div class="dashed"></div>

    <div class="nota-section bold item-line">
        <span>Total :</span>
        <span>Rp <?= number_format($total, 0, ',', '.') ?></span>
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
