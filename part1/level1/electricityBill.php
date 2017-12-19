<?php

/* 
 * electricityBill
 */

$strContent = file_get_contents('data.json');

$json = json_decode($strContent, true);

$providers = $json['providers'];
$users = $json['users'];

$bills = [];

foreach ($users as $user) {
    $key = array_search($user['provider_id'], array_column($providers, 'id'));
    $priceProvider = $providers[$key]['price_per_kwh'];
    $price = $priceProvider  * $user['yearly_consumption'];
    $currentBill = array('id' => count($bills)+1, 'price' => $price, 'user_id' => $user['id']);
    $bills[] = $currentBill;
}

echo '<pre>' . print_r($bills, true) . '</pre>';