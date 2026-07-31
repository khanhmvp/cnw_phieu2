<?php

declare(strict_types=1);

require_once __DIR__ . '/data.php';
require_once __DIR__ . '/helpers.php';

// Chuẩn hóa danh mục $categories để hiển thị an toàn
$categoryMap = [];
if (isset($categories) && is_array($categories)) {
    foreach ($categories as $key => $val) {
        if (is_array($val) && isset($val['id'], $val['name'])) {
            $categoryMap[$val['id']] = $val['name'];
        } else {
            $categoryMap[$key] = $val;
        }
    }
}

// A — Xử lý Filter GET
$catIdInput = (int)($_GET['category_id'] ?? 0);

$filteredProducts = $products ?? [];
if ($catIdInput > 0) {
    $filteredProducts = array_filter($products ?? [], function($p) use ($catIdInput) {
        return (int)($p['category_id'] ?? 0) === $catIdInput;
    });
}

// C — Tính toán tổng giá trị kho & Rank
$totalInventoryValue = inventoryValue($products ?? []);
$rank = warehouseRank($totalInventoryValue);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Shop 02</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 32px; color: #1f2937; }
        table { border-collapse: collapse; width: 100%; max-width: 900px; margin-bottom: 20px; }
        th, td { border: 1px solid #444; padding: 8px; text-align: left; }
        th { background-color: #222; color: #fff; }
        .number { text-align: right; }
        .filter-links { margin-bottom: 20px; }
        .filter-links a { margin-right: 15px; text-decoration: none; }
        .filter-links a.active { font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>

    <!-- E — Comment EXPECT -->
    <!-- MS_EXPECT inventory_value=<?= $totalInventoryValue ?> rank=<?= e($rank) ?> -->

    <h1>Quản lý Mini Shop 02</h1>

    <!-- A — Link HTML Filter -->
    <div class="filter-links">
        <strong>Lọc theo danh mục: </strong>
        <a href="index.php" class="<?= $catIdInput === 0 ? 'active' : '' ?>">Tat ca</a> |
        <a href="index.php?category_id=1" class="<?= $catIdInput === 1 ? 'active' : '' ?>">Ban phim</a> |
        <a href="index.php?category_id=2" class="<?= $catIdInput === 2 ? 'active' : '' ?>">Chuot</a> |
        <a href="index.php?category_id=3" class="<?= $catIdInput === 3 ? 'active' : '' ?>">Man hinh</a>
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
                <th class="number">Giá</th>
                <th class="number">qty</th>
                <th class="number">Thành tiền</th>
                <th>Muc ton</th>
            </tr>
        </thead>
        <tbody>
            <?php renderProductRows($filteredProducts, $categoryMap); ?>
        </tbody>
    </table>

    <!-- B — Bảng báo cáo Danh mục -->
    <h2>Báo cáo theo danh mục</h2>
    <table>
        <thead>
            <tr>
                <th>ID Danh mục</th>
                <th>Tên Danh mục</th>
                <th class="number">Số lượng SP</th>
                <th class="number">Tổng tồn kho</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($categoryMap as $catId => $catName) {
                $count = 0;
                $totalQty = 0;
                
                foreach ($products ?? [] as $p) {
                    if ((int)($p['category_id'] ?? 0) === (int)$catId) {
                        $count++;
                        $totalQty += (int)($p['qty'] ?? 0);
                    }
                }
                
                echo "<tr>";
                echo "<td>" . (int)$catId . "</td>";
                echo "<td>" . e((string)$catName) . "</td>";
                echo "<td class=\"number\">{$count}</td>";
                echo "<td class=\"number\">{$totalQty}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- C — Tổng giá trị kho & Quy mô -->
    <p><strong>Tổng giá trị kho:</strong> <?= number_format($totalInventoryValue, 0, ',', '.') ?> VNĐ</p>
    <p><strong>Quy mô kho:</strong> <?= e($rank) ?></p>

</body>
</html>