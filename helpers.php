<?php

function lineTotal($product)
{
    return $product['price'] * $product['qty'];
}

function inventoryValue($products)
{
    $total = 0;

    foreach ($products as $product) {
        $total += lineTotal($product);
    }

    return $total;
}

function findProductBySku($products, $sku)
{
    foreach ($products as $product) {
        if ($product['sku'] == $sku) {
            return $product;
        }
    }

    return null;
}

function countProductsByCategory($products, $categoryId)
{
    $count = 0;

    foreach ($products as $product) {
        if ($product['category_id'] == $categoryId) {
            $count++;
        }
    }

    return $count;
}

function stockLevel($product)
{
    $qty = $product['qty'];

    if ($qty <= 1) {
        return "Can nhap";
    }

    if ($qty <= 4) {
        return "Sap het";
    }

    return "Du";
}

function filterByCategory($products, $categoryId)
{
    if ($categoryId == 0) {
        return $products;
    }

    $result = [];

    foreach ($products as $product) {
        if ($product['category_id'] == $categoryId) {
            $result[] = $product;
        }
    }

    return $result;
}

function storeRank($inventoryValue)
{
    if ($inventoryValue >= 30000000) {
        return "Lon";
    }

    return "Nho";
}

function renderProductRows($products, $categories)
{
    foreach ($products as $product) {

        echo "<tr>";

        echo "<td>{$product['sku']}</td>";

        echo "<td>{$product['name']}</td>";

        echo "<td>{$categories[$product['category_id']]}</td>";

        echo "<td>" . number_format($product['price']) . "</td>";

        echo "<td>{$product['qty']}</td>";

        echo "<td>" . stockLevel($product) . "</td>";

        echo "<td>" . number_format(lineTotal($product)) . "</td>";

        echo "</tr>";
    }
}