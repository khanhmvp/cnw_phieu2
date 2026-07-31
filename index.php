<?php
require_once 'data.php';
require_once 'helpers.php';

// Xử lý Lọc theo category_id từ GET
$categoryId = isset($_GET['category_id']) && $_GET['category_id'] !== '' ? (int)$_GET['category_id'] : 0;

$filteredProducts = $products;
if ($categoryId > 0) {
    $filteredProducts = array_filter($products, function($p) use ($categoryId) {
        return (int)$p['category_id'] === $categoryId;
    });
}

// Tính toán báo cáo
$invValue = inventoryValue($products);
$rank = storeRank($invValue);
?>
<!-- MS_EXPECT inventory_value=<?=$invValue?> rank=<?=$rank?> -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Minishop 02</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f4f4f4; }
        .filter { margin-bottom: 15px; }
        .filter a { margin-right: 10px; text-decoration: none; color: blue; }
        .filter a.active { font-weight: bold; color: red; }
    </style>
</head>
<body>

    <!-- Link lọc danh mục -->
    <div class="filter">
        <a href="index.php" class="<?=$categoryId === 0 ? 'active' : ''?>">Tat ca</a> |
        <a href="index.php?category_id=1" class="<?=$categoryId === 1 ? 'active' : ''?>">Ban phim</a> |
        <a href="index.php?category_id=2" class="<?=$categoryId === 2 ? 'active' : ''?>">Chuot</a> |
        <a href="index.php?category_id=3" class="<?=$categoryId === 3 ? 'active' : ''?>">Man hinh</a>
    </div>

    <h2>Bảng sản phẩm</h2>
    <table>
        <thead>
            <tr>
                <th>sku</th>
                <th>name</th>
                <th>category</th>
                <th>price</th>
                <th>qty</th>
                <th>Muc ton</th>
                <th>total</th>
            </tr>
        </thead>
        <tbody>
            <?php renderProductRows($filteredProducts, $categories); ?>
        </tbody>
    </table>

    <h2>Báo cáo danh mục</h2>
    <table>
        <thead>
            <tr>
                <th>Danh mục</th>
                <th>Số SP</th>
                <th>Tổng tồn kho</th>
                <th>Tổng giá trị</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $cId => $cName): ?>
                <?php
                    $countSp = 0;
                    $sumQty = 0;
                    $sumVal = 0;
                    foreach ($products as $p) {
                        if ((int)$p['category_id'] === (int)$cId) {
                            $countSp++;
                            $sumQty += $p['qty'];
                            $sumVal += $p['price'] * $p['qty'];
                        }
                    }
                ?>
                <tr>
                    <td><?=$cName?></td>
                    <td><?=$countSp?></td>
                    <td><?=$sumQty?></td>
                    <td><?=number_format($sumVal)?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p><strong>Quy mo kho:</strong> <?=$rank?></p>
    <p><strong>Tong gia tri kho:</strong> <?=number_format($invValue)?></p>

</body>
</html>