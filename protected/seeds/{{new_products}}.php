<?php
$newProducts = array();
for ($i = 1; $i <= 15; $i++) {
    $newProducts[] = array(
        'product_id' => $i,
        'order' => $i,
        'language_id' => 1
    );
}
return $newProducts;