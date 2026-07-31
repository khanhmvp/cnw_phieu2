<?php
// helpers.php

// 1. Mức tồn kho (Check >= 5 trước, sau đó >= 2)
function stockLevel(array $product): string {
    $qty = $product['qty'] ?? 0;
    if ($qty >= 5) {
        return 'Du';
    } elseif ($qty >= 2) {
        return 'Sap het';
    } else {
        return 'Can nhap';
    }
}

// 2. Tổng giá trị kho (Phải ra đúng 41380000)
function inventoryValue(array $products): int {
    $total = 0;
    foreach ($products as $p) {
        $total += ($p['price'] ?? 0) * ($p['qty'] ?? 0);
    }
    return $total;
}

// 3. Tìm sản phẩm theo SKU
function findProductBySku(array $products, string $sku): ?array {
    foreach ($products as $p) {
        if (($p['sku'] ?? '') === $sku) {
            return $p;
        }
    }
    return null;
}

// 4. Xếp hạng quy mô kho
function storeRank(int $inventoryValue): string {
    return $inventoryValue >= 30000000 ? 'Lon' : 'Nho';
}

// 5. Render trực tiếp các thẻ <tr> bằng echo (BẮT BUỘC KHÔNG RETURN)
function renderProductRows(array $products, array $categories): void {
    foreach ($products as $p) {
        $catId = $p['category_id'] ?? 0;
        $catName = $categories[$catId] ?? (is_array($categories) && isset($categories[$catId]['name']) ? $categories[$catId]['name'] : 'N/A');
        $stock = stockLevel($p);
        $totalVal = ($p['price'] ?? 0) * ($p['qty'] ?? 0);

        echo "<tr>";
        echo "<td>{$p['sku']}</td>";
        echo "<td>{$p['name']}</td>";
        echo "<td>{$catName}</td>";
        echo "<td>" . number_format($p['price']) . "</td>";
        echo "<td>{$p['qty']}</td>";
        echo "<td>{$stock}</td>";
        echo "<td>" . number_format($totalVal) . "</td>";
        echo "</tr>";
    }
}