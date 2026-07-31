<?php
// helpers.php

// 1. Tính tổng tiền của 1 sản phẩm (price * qty)
function lineTotal(array $product): int {
    return ($product['price'] ?? 0) * ($product['qty'] ?? 0);
}

// 2. Tính tổng giá trị toàn bộ kho hàng
function inventoryValue(array $products): int {
    $total = 0;
    foreach ($products as $product) {
        $total += lineTotal($product);
    }
    return $total;
}

// 3. Tìm sản phẩm theo SKU
function findProductBySku(array $products, string $sku): ?array {
    foreach ($products as $product) {
        if (($product['sku'] ?? '') === $sku) {
            return $product;
        }
    }
    return null;
}

// 4. Đếm số lượng sản phẩm thuộc 1 danh mục
function countProductsByCategory(array $products, int $categoryId): int {
    $count = 0;
    foreach ($products as $product) {
        if (($product['category_id'] ?? null) === $categoryId) {
            $count++;
        }
    }
    return $count;
}

// 5. Xác định mức độ tồn kho
function stockLevel(array $product): string {
    $qty = $product['qty'] ?? 0;
    if ($qty >= 5) {
        return "Du";
    } elseif ($qty >= 2) {
        return "Sap het";
    } else {
        return "Can nhap";
    }
}

// 6. Lọc danh sách sản phẩm theo category_id
function filterByCategory(array $products, ?int $categoryId): array {
    if ($categoryId === null || $categoryId <= 0) {
        return $products;
    }
    
    $filtered = [];
    foreach ($products as $product) {
        if (($product['category_id'] ?? null) === $categoryId) {
            $filtered[] = $product;
        }
    }
    return $filtered;
}

// 7. Xếp hạng quy mô kho hàng
function storeRank(int $totalValue): string {
    return $totalValue >= 30000000 ? "Lon" : "Nho";
}

/**
 * 8. Nối chuỗi HTML và RETURN thay vì echo
 * @return string Chuỗi HTML chứa toàn bộ các thẻ <tr>...</tr>
 */
function renderProductRows(array $products, array $categories): string {
    $html = '';

    foreach ($products as $product) {
        $catName = 'Chưa phân loại';
        
        if (isset($categories[$product['category_id']])) {
            $catName = is_array($categories[$product['category_id']]) 
                ? $categories[$product['category_id']]['name'] 
                : $categories[$product['category_id']];
        } else {
            foreach ($categories as $cat) {
                if (is_array($cat) && isset($cat['id']) && $cat['id'] === $product['category_id']) {
                    $catName = $cat['name'];
                    break;
                }
            }
        }

        $status = stockLevel($product);
        $totalPrice = lineTotal($product);

        $html .= "<tr>";
        $html .= "<td>" . htmlspecialchars($product['sku']) . "</td>";
        $html .= "<td>" . htmlspecialchars($product['name']) . "</td>";
        $html .= "<td>" . htmlspecialchars($catName) . "</td>";
        $html .= "<td>" . number_format($product['price']) . "đ</td>";
        $html .= "<td>" . $product['qty'] . "</td>";
        $html .= "<td>" . $status . "</td>";
        $html .= "<td>" . number_format($totalPrice) . "đ</td>";
        $html .= "</tr>";
    }

    return $html;
}