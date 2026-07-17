<?php
require_once 'data.php';
require_once 'helpers.php';
//Ánh xạ danh mục sản phẩm theo id
$categoryMap = [];
foreach ($categories as $category) {
    $categoryMap[$category['id']] = $category['name'];
}
//Lấy tham số lọc $_GET['category_id'] nếu có
$selectedCategoryId = isset($_GET['category_id']) && $_GET['category_id'] !== ' ' ? (int)$_GET['category_id'] : null;
$filteredProducts = filterByCategory($products, $selectedCategoryId);
//Tính toán tổng giá trị và xếp hạng kho theo danh sách gốc
$totalInventoryValue = totalInventoryValue($products);
$warehouseRank= rankInventory($totalInventoryValue);
?>
<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Quản lí kho hàng - Minishop02</title>
        <style>
            table {
                border-collapse: collapse;
                width: 80%;
            }
            th, td {
                border: 1px solid #ddd;
                padding: 8px;
            }
            th {
                background-color: #f2f2f2;
            }
            .nav-links {
                margin-bottom: 20px;
            }
            .nav-links a {
                margin-right: 10px;
                text-decoration: none;
                color: #007BFF;
            }
            .status-du {
                color: green;
            }
            .status-sap-het {
                color: orange;
            }
            .status-can-nhap {
                color: red;
            }
        </style>
    </head>
    <body>
        <div class="nav-links">
           <strong>Bộ lọc danh mục:</strong>
           <a href="index.php" class="<?= $selectedCategoryId === null ? 'active' : ' '?>">Tat ca</a>
           <a href="index.php?category_id=1" class="<?= $selectedCategoryId === 1 ? 'active' : '' ?>">Ban phim</a> |
           <a href="index.php?category_id=2" class="<?= $selectedCategoryId === 2 ? 'active' : '' ?>">Chuot</a> |
           <a href="index.php?category_id=3" class="<?= $selectedCategoryId === 3 ? 'active' : '' ?>">Man hinh</a> 
        </div>
        <h1>Quản lí kho hàng - Minishop02</h1>
        <h2>Danh sách sản phẩm</h2>
        <table>
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá (VNĐ)</th>
                    <th>Số lượng</th>
                    <th>Mức độ tồn kho</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($filteredProducts as $product): ?>
                    <?php
                      $status = stockLevel($product);
                      $statusClass = '';
                      if ($status === 'Du') {
                          $statusClass = 'status-du';
                      } elseif ($status === 'Sap het') {
                          $statusClass = 'status-sap-het';
                      } elseif ($status === 'Can nhap') {
                          $statusClass = 'status-can-nhap';
                      }
                      ?>
                    <tr>
                        <td><?= htmlspecialchars($product['sku']) ?></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                       <td><?= isset($categoryMap[$product['category_id']]) ? htmlspecialchars($categoryMap[$product['category_id']]) : 'Chưa phân loại' ?></td>
                        <td><?= number_format($product['price']) ?></td>
                        <td><?= $product['qty'] ?></td>
                        <td class="<?= $statusClass ?>"><?= $status ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h2>Báo cáo tổng hợp theo danh mục</h2>
    <table>
        <thead>
            <tr>
                <th>Danh mục</th>
                <th>Số SP</th>
                <th>Tổng giá trị dòng máy</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $cat): ?>
                <?php 
                $count = countProductsByCategory($products, $cat['id']);
                $catValue = 0;
                foreach ($products as $p) {
                    if ($p['category_id'] === $cat['id']) {
                        $catValue += lineTotal($p);
                    }
                }
                ?>
                <tr>
                    <td><?= htmlspecialchars($cat['name']) ?></td>
                    <td><?= $count ?></td>
                    <td><?= number_format($catValue) ?>đ</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p><strong>Quy mo kho:</strong> <?= $warehouseRank ?></p>
        <h2>Tổng giá trị kho hàng: <?= number_format(totalInventoryValue($products)) ?> VNĐ</h2>
        <h3>Chekcpoint Test: findProductBySku($products, 'MN-02')</h3>
        <?php
            // Ví dụ tìm kiếm sản phẩm theo SKU
          $testProduct = findProductBySku($products, 'MN-02');
    if ($testProduct) {
        echo "<p style='color: green;'>✅ Tìm thấy: <strong>" . htmlspecialchars($testProduct['name']) . "</strong> (" . number_format($testProduct['price']) . "đ)</p>";
    } else {
        echo "<p style='color: red;'>❌ Không tìm thấy sản phẩm!</p>";
    }
    ?>
        </body>
</html>
