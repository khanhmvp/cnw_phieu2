<?php
require_once 'data.php';
require_once 'helpers.php';

// Ánh xạ danh mục sản phẩm theo id
$categoryMap = [];
if (isset($categories[0]) && is_array($categories[0])) {
    foreach ($categories as $category) {
        $categoryMap[$category['id']] = $category['name'];
    }
} else {
    $categoryMap = $categories; // Trường hợp data.php khai báo [1 => 'Ban phim', ...]
}

// Lấy tham số lọc $_GET['category_id']
$selectedCategoryId = isset($_GET['category_id']) && trim($_GET['category_id']) !== '' ? (int)$_GET['category_id'] : 0;

// Lọc sản phẩm
$filteredProducts = $products;
if ($selectedCategoryId > 0) {
    $filteredProducts = array_filter($products, function($p) use ($selectedCategoryId) {
        return $p['category_id'] === $selectedCategoryId;
    });
}

// Tính toán tổng giá trị kho hàng và quy mô kho
$totalInventoryValue = inventoryValue($products);
$warehouseRank = storeRank($totalInventoryValue);
?>
<!-- MS_EXPECT inventory_value=<?= $totalInventoryValue ?> rank=<?= $warehouseRank ?> -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý kho hàng - Minishop02</title>
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

    <!-- A — Link HTML Lọc Danh Mục -->
    <div class="nav-links">
        <strong>Bộ lọc danh mục:</strong>
        <a href="index.php" class="<?= $selectedCategoryId === 0 ? 'active' : '' ?>">Tat ca</a> |
        <a href="index.php?category_id=1" class="<?= $selectedCategoryId === 1 ? 'active' : '' ?>">Ban phim</a> |
        <a href="index.php?category_id=2" class="<?= $selectedCategoryId === 2 ? 'active' : '' ?>">Chuot</a> |
        <a href="index.php?category_id=3" class="<?= $selectedCategoryId === 3 ? 'active' : '' ?>">Man hinh</a> 
    </div>

    <h1>Quản lý kho hàng - Minishop02</h1>
    
    <h2>Danh sách sản phẩm</h2>
    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Tên sản phẩm</th>
                <th>Danh mục</th>
                <th>Giá (VNĐ)</th>
                <th>Qty</th>
                <th>Muc ton</th>
                <th>Tổng giá trị</th>
            </tr>
        </thead>
        <tbody>
            <!-- D — In chuỗi HTML trả về từ renderProductRows (do hàm dùng return) -->
            <?= renderProductRows($filteredProducts, $categoryMap) ?>
        </tbody>
    </table>

    <!-- B — Báo cáo tổng hợp theo 3 danh mục -->
    <h2>Báo cáo tổng hợp theo danh mục</h2>
    <table>
        <thead>
            <tr>
                <th>Danh mục</th>
                <th>Số SP</th>
                <th>Tổng số lượng</th>
                <th>Tổng giá trị dòng máy</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categoryMap as $catId => $catName): ?>
                <?php 
                    $count = 0;
                    $sumQty = 0;
                    $catValue = 0;
                    foreach ($products as $p) {
                        if ($p['category_id'] === $catId) {
                            $count++;
                            $sumQty += $p['qty'];
                            $catValue += $p['price'] * $p['qty'];
                        }
                    }
                ?>
                <tr>
                    <td><?= htmlspecialchars($catName) ?></td>
                    <td><?= $count ?></td>
                    <td><?= $sumQty ?></td>
                    <td><?= number_format($catValue) ?> VNĐ</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- C — Quy mô kho & Tổng giá trị -->
    <p><strong>Quy mo kho:</strong> <?= $warehouseRank ?></p>
    <h2>Tổng giá trị kho hàng: <?= number_format($totalInventoryValue) ?> VNĐ</h2>

    <!-- Checkpoint Test -->
    <h3>Checkpoint Test: findProductBySku($products, 'MN-02')</h3>
    <?php
    $testProduct = findProductBySku($products, 'MN-02');
    if ($testProduct) {
        echo "<p style='color: green;'>✅ Tìm thấy: <strong>" . htmlspecialchars($testProduct['name']) . "</strong> (" . number_format($testProduct['price']) . "đ)</p>";
    } else {
        echo "<p style='color: red;'>❌ Không tìm thấy sản phẩm!</p>";
    }
    ?>

</body>
</html>