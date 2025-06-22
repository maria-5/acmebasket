<?php

require __DIR__ . '/vendor/autoload.php';

use Acme\Basket;
use Acme\Offers\BuyOneGetSecondHalfPrice;
use Money\Money;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;

// Currency: USD
$currency = new Currency('USD');

// Prices in cents (MoneyPHP requires integers)
$catalogue = [
    'R01' => new Money(3295, $currency),
    'G01' => new Money(2495, $currency),
    'B01' => new Money(795,  $currency)
];

// Delivery charges in cents
$deliveryRules = [
    5000 => new Money(495, $currency),   // under $50
    9000 => new Money(295, $currency),   // under $90
    PHP_INT_MAX => new Money(0, $currency) // $90 or more
];

$offers = [
    new BuyOneGetSecondHalfPrice('R01')
];

$basket = new Basket($catalogue, $deliveryRules, $offers);
$basket->add('R01');
$basket->add('R01');

// Format output
$currencies = new ISOCurrencies();
$formatter = new DecimalMoneyFormatter($currencies);

echo "Total: $" . $formatter->format($basket->total()) . PHP_EOL;
