<?php

require_once "data.php";
require_once "helpers.php";

$categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

$filteredProducts = filterByCategory($products, $categoryId);

$totalInventory = inventoryValue($products);

$rank = storeRank($totalInventory);

?>

<!-- MS_EXPECT inventory_value=41380000 rank=Lon -->

<!DOCTYPE html>
<html lang="vi">

<head>

<meta charset="UTF-8">

<title>Mini Shop</title>

<style>

body{
    font-family:Arial;
    margin:20px;
}

table{
    border-collapse:collapse;
    width:90%;
}

table,th,td{
    border:1px solid #999;
}

th,td{
    padding:8px;
}

a{
    margin-right:10px;
}

.active{
    color:red;
    font-weight:bold;
}

</style>

</head>

<body>

<h2>Bo loc</h2>

<a href="index.php"
class="<?=($categoryId==0)?'active':''?>">
Tat ca
</a>

<?php foreach($categories as $id=>$name): ?>

<a href="index.php?category_id=<?=$id?>"
class="<?=($categoryId==$id)?'active':''?>">
<?=$name?>
</a>

<?php endforeach; ?>

<h2>Danh sach san pham</h2>

<table>

<thead>

<tr>

<th>SKU</th>

<th>Ten</th>

<th>Danh muc</th>

<th>Gia</th>

<th>Qty</th>

<th>Muc ton</th>

<th>Thanh tien</th>

</tr>

</thead>

<tbody>

<?php renderProductRows($filteredProducts,$categories); ?>

</tbody>

</table>

<h2>Bao cao danh muc</h2>

<table>

<tr>

<th>Danh muc</th>

<th>So SP</th>

<th>Tong Qty</th>

<th>Tong gia tri</th>

</tr>

<?php foreach($categories as $id=>$name): ?>

<?php

$qty=0;
$value=0;

foreach($products as $p){

    if($p['category_id']==$id){

        $qty+=$p['qty'];

        $value+=lineTotal($p);

    }

}

?>

<tr>

<td><?=$name?></td>

<td><?=countProductsByCategory($products,$id)?></td>

<td><?=$qty?></td>

<td><?=number_format($value)?></td>

</tr>

<?php endforeach; ?>

</table>

<h3>Tong gia tri kho:
<?=number_format($totalInventory)?> VND
</h3>

<h3>Quy mo kho:
<?=$rank?>
</h3>

</body>

</html>