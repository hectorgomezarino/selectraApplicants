<?php

/* 
 * contracts discounts
 */

$strContent = file_get_contents('data.json');

$json = json_decode($strContent, true);

$providers = $json['providers'];
$users = $json['users'];
$contracts = $json['contracts'];

$bills = [];

foreach ($users as $user) {
    $contractKey = array_search($user['id'], array_column($contracts, 'user_id'));
    $providerKey = array_search($contracts[$contractKey]['provider_id'], array_column($providers, 'id'));
    $priceProvider = $providers[$providerKey]['price_per_kwh'];
    $contractLength = $contracts[$contractKey]['contract_length'];
    $discount = 1;
    if ($contractLength<=1) {
        $discount = 0.9;
    } else if ($contractLength<=3) {
        $discount = 0.8;
    } else if ($contractLength>3) {
        $discount = 0.75;
    }
    echo " <br /> antigüedad: " .$contractLength." precio: ". $priceProvider. " usuario consumición: ". $user['yearly_consumption'] . " descuento: " . $discount; 
    $price = ($priceProvider * $user['yearly_consumption']) * $discount;
    $currentBill = array('id' => count($bills)+1, 'price' => $price, 'user_id' => $user['id']);
    $bills[] = $currentBill;
}


echo "I thik that the first result into output.json is wrong, maybe its a mistake.";
echo '<pre>' . print_r($bills, true) . '</pre>';