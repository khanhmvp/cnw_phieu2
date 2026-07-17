<?php
//tính tổng tiền của sản phẩm(price*qty)
function lineTotal(array $product): int {
    return $product['price'] * $product['qty'];
}
//tính tổng giá trị toàn bộ kho hàng
function totalInventoryValue(array $products): int {
    $total = 0;
    foreach ($products as $product) {
        $total += lineTotal($product);
    }
    return $total;
}
//tìm sản phẩm theo SKU
function findProductBySku(array $products, string $sku): ?array {
    foreach ($products as $product) {
        if ($product['sku'] === $sku) {
            return $product;
        }
    }
    return null;
}
//đếm số lượng sản phẩm thuộc 1 danh mục
function countProductsByCategory(array $products, int $categoryId): int {
    $count = 0;
    foreach ($products as $product) {
        if ($product['category_id'] === $categoryId) {
            $count++;
        }
    }
    return $count;
}
//xác định mức độ tồn kho của sản phẩm dựa trên số lượng
function stockLevel(array $product): string {
    $qty = $product['qty'];
    if ($qty >=5){
        return "Du";
    }else if ($qty >= 2 && $qty < 5){
        return "Sap het";
    }else{
        return "Can nhap";
    }
}
// Lọc danh sách sản phẩm theo category_id (Bài về nhà - Nhiệm vụ A)
function filterByCategory(array $products, ?int $categoryId): array {
    if ($categoryId === null) {
        return $products;
    }
    $filtered = [];
    foreach ($products as $product) {
        if ($product['category_id'] === $categoryId) {
            $filtered[] = $product;
        }
    }
    return $filtered;
}

// Xếp hạng quy mô kho hàng hàng (Bài về nhà - Nhiệm vụ C)
function rankInventory(int $totalValue): string {
    if ($totalValue < 15000000) {
        return "Nho";
    } elseif ($totalValue < 35000000) {
        return "Trung binh";
    } else {
        return "Lon";
    }
}
