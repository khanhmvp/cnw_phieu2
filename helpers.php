<?php
// helpers.php

// 1. Tinh thanh tien 1 san pham
function lineTotal(array $product): int {
    return (int)($product['price'] ?? 0) * (int)($product['qty'] ?? 0);
}

// 2. Tinh tong gia tri kho hang
function inventoryValue(array $products): int {
    $total = 0;
    foreach ($products as $product) {
        $total += lineTotal($product);
    }
    return $total;
}

// 3. Tim san pham theo SKU
function findProductBySku(array $products, string $sku): ?array {
    foreach ($products as $product) {
        if (isset($product['sku']) && $product['sku'] === $sku) {
            return $product;
        }
    }
    return null;
}

// 4. Dem so san pham thuoc danh muc
function countProductsByCategory(array $products, int $categoryId): int {
    $count = 0;
    foreach ($products as $product) {
        if (isset($product['category_id']) && (int)$product['category_id'] === $categoryId) {
            $count++;
        }
    }
    return $count;
}

// 5. Muc ton kho
function stockLevel(array $product): string {
    $qty = (int)($product['qty'] ?? 0);
    if ($qty >= 5) {
        return "Du";
    } elseif ($qty >= 2) {
        return "Sap het";
    } else {
        return "Can nhap";
    }
}

// 6. Loc san pham theo danh muc
function filterByCategory(array $products, ?int $categoryId): array {
    if ($categoryId === null || $categoryId <= 0) {
        return $products;
    }
    $filtered = [];
    foreach ($products as $product) {
        if (isset($product['category_id']) && (int)$product['category_id'] === $categoryId) {
            $filtered[] = $product;
        }
    }
    return $filtered;
}

// 7. Xep hang quy mo kho hang
function storeRank(int $totalValue): string {
    return $totalValue >= 30000000 ? "Lon" : "Nho";
}

// 8. Render dong san pham
function renderProductRows(array $products, array $categories): void {
    foreach ($products as $product) {
        $catId = (int)($product['category_id'] ?? 0);
        $catName = 'Chua phan loai';

        if (isset($categories[$catId])) {
            $catName = is_array($categories[$catId]) ? $categories[$catId]['name'] : $categories[$catId];
        }

        $status = stockLevel($product);
        $totalPrice = lineTotal($product);

        echo "<tr>";
        echo "<td>" . htmlspecialchars($product['sku']) . "</td>";
        echo "<td>" . htmlspecialchars($product['name']) . "</td>";
        echo "<td>" . htmlspecialchars($catName) . "</td>";
        echo "<td>" . number_format($product['price']) . "d</td>";
        echo "<td>" . (int)$product['qty'] . "</td>";
        echo "<td>" . $status . "</td>";
        echo "<td>" . number_format($totalPrice) . "d</td>";
        echo "</tr>";
    }
}