<?php
// index.php

// Dùng require_once để tránh nạp file nhiều lần gây lỗi trùng hàm
require_once 'data.php';
require_once 'helpers.php';

// A — Xử lý Filter GET
$catIdInput = (int)($_GET['category_id'] ?? 0);

$filteredProducts = $products;
if ($catIdInput > 0) {
    $filteredProducts = array_filter($products, function($p) use ($catIdInput) {
        return (int)$p['category_id'] === $catIdInput;
    });
}

// C — Tính toán tổng giá trị kho & Rank
$totalInventoryValue = inventoryValue($products);
$rank = warehouseRank($totalInventoryValue);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Mini Shop 02</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #444; padding: 8px; text-align: left; }
        th { background-color: #222; color: #fff; }
        .filter-links a { margin-right: 15px; text-decoration: none; }
        .filter-links a.active { font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>

    <!-- E — Comment EXPECT -->
    <!-- MS_EXPECT inventory_value=<?= $totalInventoryValue ?> rank=<?= $rank ?> -->

    <h1>Quản lý Mini Shop 02</h1>

    <!-- A — Link HTML Filter -->
    <div class="filter-links">
        <strong>Lọc theo danh mục: </strong>
        <a href="index.php" class="<?= $catIdInput === 0 ? 'active' : '' ?>">Tat ca</a> |
        <a href="index.php?category_id=1" class="<?= $catIdInput === 1 ? 'active' : '' ?>">Ban phim</a> |
        <a href="index.php?category_id=3" class="<?= $catIdInput === 3 ? 'active' : '' ?>">Chuot</a> |
        <a href="index.php?category_id=2" class="<?= $catIdInput === 2 ? 'active' : '' ?>">Man hinh</a>
    </div>

    <br>

    <!-- D — Bảng danh sách sản phẩm chính -->
    <h2>Danh sách sản phẩm</h2>
    <table>
        <thead>
            <tr>
                <th>sku</th>
                <th>Tên SP</th>
                <th>Danh mục</th>
                <th>Giá</th>
                <th>qty</th>
                <th>Muc ton</th>
            </tr>
        </thead>
        <tbody>
            <?php renderProductRows($filteredProducts, $categories); ?>
        </tbody>
    </table>

    <!-- B — Bảng báo cáo 3 Danh mục -->
    <h2>Báo cáo theo danh mục</h2>
    <table>
        <thead>
            <tr>
                <th>ID Danh mục</th>
                <th>Tên Danh mục</th>
                <th>Số lượng SP</th>
                <th>Tổng tồn kho</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($categories as $catId => $catName) {
                $count = 0;
                $totalQty = 0;
                
                foreach ($products as $p) {
                    if ((int)$p['category_id'] === (int)$catId) {
                        $count++;
                        $totalQty += (int)$p['qty'];
                    }
                }
                
                echo "<tr>";
                echo "<td>{$catId}</td>";
                echo "<td>{$catName}</td>";
                echo "<td>{$count}</td>";
                echo "<td>{$totalQty}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- C — Tổng giá trị kho & Quy mô -->
    <p><strong>Tổng giá trị kho:</strong> <?= number_format($totalInventoryValue, 0, ',', '.') ?> VNĐ</p>
    <p><strong>Quy mô kho:</strong> <?= $rank ?></p>

</body>
</html>