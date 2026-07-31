<?php
// helpers.php

/**
 * 1. Đánh giá mức tồn kho
 * Logic: >= 5 => 'Du', >= 2 => 'Sap het', còn lại => 'Can nhap'
 */
function stockLevel(array $product): string 
{
    $qty = $product['qty'] ?? 0;
    
    if ($qty >= 5) {
        return 'Du';
    } elseif ($qty >= 2) {
        return 'Sap het';
    } else {
        return 'Can nhap';
    }
}

/**
 * 2. Tính tổng giá trị toàn bộ kho hàng (Giá * Số lượng)
 */
function inventoryValue(array $products): float 
{
    $total = 0;
    foreach ($products as $p) {
        $total += ($p['price'] * $p['qty']);
    }
    return $total;
}

/**
 * 3. Tìm sản phẩm theo SKU
 */
function findProductBySku(array $products, string $sku): ?array 
{
    foreach ($products as $p) {
        if (($p['sku'] ?? '') === $sku) {
            return $p;
        }
    }
    return null;
}

/**
 * 4. Đánh giá quy mô kho hàng
 */
function warehouseRank(float $totalValue): string 
{
    if ($totalValue > 30000000) {
        return 'Lon';
    } elseif ($totalValue >= 10000000) {
        return 'Vua';
    }
    return 'Nho';
}

/**
 * 5. Render các dòng <tr>...</tr> cho bảng sản phẩm
 */
function renderProductRows(array $products, array $categories): void 
{
    foreach ($products as $p) {
        $catName = $categories[$p['category_id']] ?? 'Khac';
        $stock = stockLevel($p);
        $priceFormatted = number_format($p['price'], 0, ',', '.') . ' VNĐ';
        
        echo "<tr>";
        echo "<td>{$p['sku']}</td>";
        echo "<td>{$p['name']}</td>";
        echo "<td>{$catName}</td>";
        echo "<td>{$priceFormatted}</td>";
        echo "<td>{$p['qty']}</td>";
        echo "<td>{$stock}</td>";
        echo "</tr>";
    }
}