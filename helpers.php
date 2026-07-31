<?php

declare(strict_types=1);

function lineTotal(array $product): int
{
    return $product['price'] * $product['qty'];
}

function inventoryValue(array $products): int
{
    $total = 0;

    foreach ($products as $product) {
        $total += lineTotal($product);
    }

    return $total;
}

function findProductBySku(array $products, string $sku): ?array
{
    foreach ($products as $product) {
        if ($product['sku'] === $sku) {
            return $product;
        }
    }

    return null;
}

function countByCategory(array $products, int $categoryId): int
{
    $count = 0;

    foreach ($products as $product) {
        if ($product['category_id'] === $categoryId) {
            $count++;
        }
    }

    return $count;
}

function stockLevel(array $product): string
{
    if ($product['qty'] >= 5) {
        return 'Du';
    }

    if ($product['qty'] >= 2) {
        return 'Sap het';
    }

    return 'Can nhap';
}

function filterByCategory(array $products, ?int $categoryId): array
{
    if ($categoryId === null) {
        return $products;
    }

    $filteredProducts = [];
    foreach ($products as $product) {
        if ($product['category_id'] === $categoryId) {
            $filteredProducts[] = $product;
        }
    }

    return $filteredProducts;
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

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function renderProductRows(array $products, array $categoryMap): void
{
    foreach ($products as $product) {
        $categoryName = $categoryMap[$product['category_id']] ?? 'Chua xac dinh';

        echo '<tr>';
        echo '<td>' . e($product['sku']) . '</td>';
        echo '<td>' . e($product['name']) . '</td>';
        echo '<td>' . e($categoryName) . '</td>';
        echo '<td class="number">' . number_format($product['price'], 0, ',', '.') . '</td>';
        echo '<td class="number">' . $product['qty'] . '</td>';
        echo '<td class="number">' . number_format(lineTotal($product), 0, ',', '.') . '</td>';
        echo '<td>' . stockLevel($product) . '</td>';
        echo '</tr>';
    }
}