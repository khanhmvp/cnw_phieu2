<?php
// data.php - Chuẩn 8 SP (P01 / CANONICAL)

$categories = [
    ['id' => 1, 'name' => 'Ban phim', 'description' => 'Cac loai ban phim co va ban phim thuong'],
    ['id' => 2, 'name' => 'Chuot', 'description' => 'Chuot khong day va chuot gaming'],
    ['id' => 3, 'name' => 'Man hinh', 'description' => 'Man hinh do hoa va man hinh tan so quet cao'],
];

$products = [
    // Bàn phím (Category ID = 1)
    ['sku' => 'KB-01', 'name' => 'Akko 3068', 'category_id' => 1, 'price' => 1200000, 'qty' => 3],
    ['sku' => 'KB-02', 'name' => 'Keychron K2', 'category_id' => 1, 'price' => 1800000, 'qty' => 5],
    ['sku' => 'KB-03', 'name' => 'Logitech K120', 'category_id' => 1, 'price' => 150000, 'qty' => 2],

    // Chuột (Category ID = 2)
    ['sku' => 'MS-01', 'name' => 'Logitech G102', 'category_id' => 2, 'price' => 400000, 'qty' => 10],
    ['sku' => 'MS-02', 'name' => 'Razer DeathAdder', 'category_id' => 2, 'price' => 800000, 'qty' => 4],
    ['sku' => 'MS-03', 'name' => 'Logitech MX Master 3S', 'category_id' => 2, 'price' => 2500000, 'qty' => 8],

    // Màn hình (Category ID = 3)
    ['sku' => 'MN-01', 'name' => 'Dell Ultrasharp 24', 'category_id' => 3, 'price' => 5500000, 'qty' => 2],
    ['sku' => 'MN-02', 'name' => 'LG UltraFine', 'category_id' => 3, 'price' => 8500000, 'qty' => 1],
];