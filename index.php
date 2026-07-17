<?php
require_once 'data.php';
require_once 'helpers.php';
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
        </style>
    </head>
    <body>
        <h1>Quản lí kho hàng - Minishop02</h1>
        <h2>Danh sách sản phẩm</h2>
        <table>
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Tên sản phẩm</th>
                    <!-- <th>Danh mục</th> -->
                    <th>Giá (VNĐ)</th>
                    <th>Số lượng</th>
                    <!-- <th>Tổng tiền (VNĐ)</th> -->
                    <th>Mức độ tồn kho</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <!-- <?php
                        $categoryName = '';
                        foreach ($categories as $category) {
                            if ($category['id'] === $product['category_id']) {
                                $categoryName = $category['name'];
                                break;
                            }
                        }
                    ?> -->
                    <tr>
                        <td><?= htmlspecialchars($product['sku']) ?></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars($categoryName) ?></td>
                        <td><?= number_format($product['price'], 0, ',', '.') ?></td>
                        <td><?= $product['qty'] ?></td><!--                         
                        <td><?= number_format(lineTotal($product), 0, ',', '.') ?></td> -->
                        <td><?= stockLevel($product) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Tổng giá trị kho hàng: <?= number_format(totalInventoryValue($products)) ?> VNĐ</h2>
        <h3>Chekcpoint Test: findProductBySku($products, 'MN-02')</h3>
        <?php
            // Ví dụ tìm kiếm sản phẩm theo SKU
            $testProduct = findProductBySku($products, 'MN-02');
            if ($testProduct) {
                echo "<strong>Sản phẩm tìm thấy: " . htmlspecialchars($testProduct['name']) . "</strong>(Giá: " . number_format($testProduct['price'], ) . " VNĐ " ;
            } else {
                echo "<p>Không tìm thấy sản phẩm với SKU 'MN-02'</p>";
            }
        ?>
        </body>
</html>