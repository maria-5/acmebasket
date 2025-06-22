<?php

namespace Acme\Offers;

use Money\Money;
use Money\Currency;

interface OfferInterface
{
    /**
     * Applies the offer logic.
     *
     * @param array<string, int>          $itemCounts   The basket item counts (e.g., ['R01' => 2])
     * @param array<string, Money>        $catalogue    The product catalogue (e.g., ['R01' => Money])
     *
     * @return Money                      The total discount amount to subtract
     */
    public function apply(array $itemCounts, array $catalogue): Money;
}
