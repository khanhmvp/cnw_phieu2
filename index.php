<?php

declare(strict_types=1);

require_once __DIR__ . '/data.php';
require_once __DIR__ . '/helpers.php';

$categoryMap = [];
foreach ($categories as $category) {
    $categoryMap[$category['id']] = $category['name'];
}

$categoryId = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT);
if ($categoryId === false || !array_key_exists($categoryId, $categoryMap)) {
    $categoryId = null;
}

$visibleProducts = filterByCategory($products, $categoryId);
$totalInventoryValue = inventoryValue($products);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiniShop - Bao cao kho (Buoi 2)</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 32px; color: #1f2937; }
        table { border-collapse: collapse; width: 100%; max-width: 900px; margin-bottom: 24px; }
        th, td { border: 1px solid #d1d5db; padding: 10px; text-align: left; }
        th { background: #f3f4f6; }
        .number { text-align: center; }
        nav { margin-bottom: 20px; }
        nav a { margin-right: 12px; }
    </style>
</head>
<body>
    <h1>MiniShop - Bao cao kho (Buoi 2)</h1>

    <nav>
        <a href="index.php">Tat ca</a> |
        <a href="?category_id=1">Ban phim</a> |
        <a href="?category_id=2">Chuot</a> |
        <a href="?category_id=3">Man hinh</a>
    </nav>

    <h2>Danh sach san pham</h2>
    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Ten</th>
                <th>Danh muc</th>
                <th>Gia</th>
                <th>So luong</th>
                <th>Thanh tien</th>
                <th>Muc ton</th>
            </tr>
        </thead>
        <tbody>
            <?php renderProductRows($visibleProducts, $categoryMap); ?>
        </tbody>
    </table>

    <p><strong>Tong gia tri kho: <?= number_format($totalInventoryValue, 0, ',', '.') ?></strong></p>
    <p><strong>Quy mo kho: <?= rankInventory($totalInventoryValue) ?></strong></p>

    <h2>Bao cao theo danh muc</h2>
    <table>
        <thead>
            <tr>
                <th>Danh muc</th>
                <th>So SP</th>
                <th>Tong gia tri</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
                <?php $categoryProducts = filterByCategory($products, $category['id']); ?>
                <tr>
                    <td><?= e($category['name']) ?></td>
                    <td class="number"><?= countByCategory($products, $category['id']) ?></td>
                    <td class="number"><?= number_format(inventoryValue($categoryProducts), 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- MS_EXPECT inventory_value=41380000 rank=Lon -->
</body>
</html>