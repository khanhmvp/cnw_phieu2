<?php
require_once 'data.php';
require_once 'helpers.php';

// Chuan hoa mang danh muc
$categoryMap = [];
if (isset($categories[0]) && is_array($categories[0])) {
    foreach ($categories as $cat) {
        $categoryMap[(int)$cat['id']] = $cat['name'];
    }
} else {
    foreach ($categories as $id => $val) {
        $categoryMap[(int)$id] = is_array($val) ? $val['name'] : $val;
    }
}

// Doc $_GET['category_id']
$selectedCategoryId = (int)($_GET['category_id'] ?? 0);

// Loc san pham
$filteredProducts = filterByCategory($products, $selectedCategoryId);

// Bao cao tong gia tri
$totalInventoryValue = inventoryValue($products);
$warehouseRank = storeRank($totalInventoryValue);
?><!-- MS_EXPECT inventory_value=<?=$totalInventoryValue?> rank=<?=$warehouseRank?> -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quan ly kho hang - Minishop02</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        table { border-collapse: collapse; width: 80%; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .nav-links { margin-bottom: 20px; }
        .nav-links a { margin-right: 10px; text-decoration: none; color: #007BFF; font-weight: bold; }
        .nav-links a.active { color: #d9534f; text-decoration: underline; }
    </style>
</head>
<body>

    <!-- Link loc HTML -->
    <div class="nav-links">
        <strong>Bo loc danh muc:</strong>
        <a href="index.php" class="<?= $selectedCategoryId === 0 ? 'active' : '' ?>">Tat ca</a> |
        <a href="index.php?category_id=1" class="<?= $selectedCategoryId === 1 ? 'active' : '' ?>">Ban phim</a> |
        <a href="index.php?category_id=2" class="<?= $selectedCategoryId === 2 ? 'active' : '' ?>">Chuot</a> |
        <a href="index.php?category_id=3" class="<?= $selectedCategoryId === 3 ? 'active' : '' ?>">Man hinh</a> 
    </div>

    <h1>Quan ly kho hang - Minishop02</h1>
    
    <h2>Danh sach san pham</h2>
    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Ten san pham</th>
                <th>Danh muc</th>
                <th>Gia</th>
                <th>Qty</th>
                <th>Muc ton</th>
                <th>Tong gia tri</th>
            </tr>
        </thead>
        <tbody>
            <?php renderProductRows($filteredProducts, $categoryMap); ?>
        </tbody>
    </table>

    <!-- Bao cao danh muc -->
    <h2>Bao cao tong hop theo danh muc</h2>
    <table>
        <thead>
            <tr>
                <th>Danh muc</th>
                <th>So SP</th>
                <th>Tong so luong</th>
                <th>Tong gia tri dong may</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categoryMap as $catId => $catName): ?>
                <?php 
                    $count = countProductsByCategory($products, (int)$catId);
                    $sumQty = 0;
                    $catValue = 0;
                    foreach ($products as $p) {
                        if (isset($p['category_id']) && (int)$p['category_id'] === (int)$catId) {
                            $sumQty += (int)$p['qty'];
                            $catValue += lineTotal($p);
                        }
                    }
                ?>
                <tr>
                    <td><?= htmlspecialchars($catName) ?></td>
                    <td><?= $count ?></td>
                    <td><?= $sumQty ?></td>
                    <td><?= number_format($catValue) ?> VND</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Quy mo & Tong gia tri kho -->
    <p><strong>Quy mo kho:</strong> <?= $warehouseRank ?></p>
    <h2>Tong gia tri kho hang: <?= number_format($totalInventoryValue) ?> VND</h2>

</body>
</html>