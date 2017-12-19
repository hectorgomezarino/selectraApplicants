<?php

/* 
 * contracts discounts with comission
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
    $contractLength = $contracts[$contractKey]['contract_length'];
    $contractGreen = $contracts[$contractKey]['green'];
    $discount = 1;
    $greenDiscount = 0;
    if ($contractLength<=1) {
        $discount = 0.9;
    } else if ($contractLength<=3) {
        $discount = 0.8;
    } else if ($contractLength>3) {
        $discount = 0.75;
    }

    $priceProvider = $providers[$providerKey]['price_per_kwh'];

    if ($contractGreen==true) {
        $greenDiscount = $user['yearly_consumption']*0.05;
    }
    $price = (($priceProvider * $user['yearly_consumption']) * $discount) - $greenDiscount;

    $insurance = 0.05*365;
    $provider = $price-$insurance;
    $selectra = round($provider * 0.125, 2);
    $commission = array('insurance_fee' => $insurance, 'provider_fee' => $provider, 'selectra_fee' => $selectra);
    $currentBill = array('commission' => $commission, 'id' => count($bills)+1, 'price' => $price, 'user_id' => $user['id']);
    $bills[] = $currentBill;
}

echo '<pre>' . print_r($bills, true) . '</pre>';