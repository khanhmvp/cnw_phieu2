<?php

declare(strict_types=1);

/**
 * 1. Tính tổng giá trị của một dòng sản phẩm (Giá * Số lượng)
 */
function lineTotal(array $product): int
{
    return (int)(($product['price'] ?? 0) * ($product['qty'] ?? 0));
}

/**
 * 2. Tính tổng giá trị toàn bộ kho hàng (Giá * Số lượng)
 */
function inventoryValue(array $products): float 
{
    $total = 0;
    foreach ($products as $p) {
        $total += lineTotal($p);
    }
    return (float)$total;
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
 * 4. Đếm số lượng sản phẩm theo danh mục
 */
function countByCategory(array $products, int $categoryId): int
{
    $count = 0;
    foreach ($products as $product) {
        if ((int)($product['category_id'] ?? 0) === $categoryId) {
            $count++;
        }
    }
    return $count;
}

/**
 * 5. Đánh giá mức tồn kho
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
 * 6. Lọc sản phẩm theo danh mục
 */
function filterByCategory(array $products, ?int $categoryId): array
{
    if ($categoryId === null) {
        return $products;
    }

    $filteredProducts = [];
    foreach ($products as $product) {
        if ((int)($product['category_id'] ?? 0) === $categoryId) {
            $filteredProducts[] = $product;
        }
    }

    return $filteredProducts;
}

/**
 * 7. Đánh giá quy mô kho hàng
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

function rankInventory(int $totalValue): string
{
    if ($totalValue < 15000000) {
        return 'Nho';
    }
    if ($totalValue < 35000000) {
        return 'Trung binh';
    }
    return 'Lon';
}

/**
 * 8. Hàm helper escape chống lỗi XSS
 */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * 9. Render các dòng <tr>...</tr> cho bảng sản phẩm
 */
function renderProductRows(array $products, array $categories): void 
{
    foreach ($products as $p) {
        $catId = $p['category_id'] ?? null;
        // Hỗ trợ cả 2 dạng mảng $categories (mảng phẳng hoặc mảng đa chiều)
        $catName = $categories[$catId] ?? ($categories[$catId]['name'] ?? 'Khac');
        
        $stock = stockLevel($p);
        $priceFormatted = number_format((float)($p['price'] ?? 0), 0, ',', '.') . ' VNĐ';
        $lineFormatted = number_format((float)lineTotal($p), 0, ',', '.') . ' VNĐ';
        
        echo "<tr>";
        echo "<td>" . e((string)($p['sku'] ?? '')) . "</td>";
        echo "<td>" . e((string)($p['name'] ?? '')) . "</td>";
        echo "<td>" . e((string)$catName) . "</td>";
        echo "<td class=\"number\">{$priceFormatted}</td>";
        echo "<td class=\"number\">" . (int)($p['qty'] ?? 0) . "</td>";
        echo "<td class=\"number\">{$lineFormatted}</td>";
        echo "<td>" . e($stock) . "</td>";
        echo "</tr>";
    }
}