<?php

namespace Acme\Offers;

use Money\Money;

class BuyOneGetSecondHalfPrice implements OfferInterface
{
    private string $productCode;

    public function __construct(string $productCode)
    {
        $this->productCode = $productCode;
    }

    /**
     * @param array<string, int>   $itemCounts
     * @param array<string, Money> $catalogue
     * @return Money
     */
    public function apply(array $itemCounts, array $catalogue): Money
    {
        if (!isset($itemCounts[$this->productCode])) {
            return new Money(0, $catalogue[array_key_first($catalogue)]->getCurrency());
        }

        $count = $itemCounts[$this->productCode];
        $discountPairs = intdiv($count, 2); // floor($count / 2)

        /** @var Money $unitPrice */
        $unitPrice = $catalogue[$this->productCode];

        // 50% discount on one item per pair
        return $unitPrice->multiply($discountPairs)->divide(2);
    }
}
