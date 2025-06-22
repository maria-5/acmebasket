<?php

namespace Acme;

use Acme\Offers\OfferInterface;
use Money\Money;
use Money\Currency;

class Basket
{
    /** @var string[] */
    private array $products = [];

    /** @var array<string, Money> */
    private array $catalogue;

    /** @var array<int, Money> Delivery rules keyed by minimum subtotal (in cents) */
    private array $deliveryRules;

    /** @var OfferInterface[] */
    private array $offers;

    private Currency $currency;

    /**
     * @param array<string, Money> $catalogue
     * @param array<int, Money>    $deliveryRules
     * @param OfferInterface[]     $offers
     */
    public function __construct(array $catalogue, array $deliveryRules, array $offers = [])
    {
        $this->catalogue = $catalogue;
        $this->deliveryRules = $deliveryRules;
        $this->offers = $offers;

        // Assume all catalogue items share the same currency
        $this->currency = $catalogue[array_key_first($catalogue)]->getCurrency();
    }

    public function add(string $productCode): void
    {
        if (!isset($this->catalogue[$productCode])) {
            throw new \InvalidArgumentException("Invalid product: $productCode");
        }
        $this->products[] = $productCode;
    }

    public function total(): Money
    {
        $itemCounts = array_count_values($this->products);
        $subtotal = new Money(0, $this->currency);

        // Calculate subtotal
        foreach ($itemCounts as $code => $count) {
            $unitPrice = $this->catalogue[$code];
            $subtotal = $subtotal->add($unitPrice->multiply($count));
        }

        // Apply discounts
        foreach ($this->offers as $offer) {
            $discount = $offer->apply($itemCounts, $this->catalogue);
            $subtotal = $subtotal->subtract($discount);
        }

        // Apply delivery charge
        ksort($this->deliveryRules);
        foreach ($this->deliveryRules as $threshold => $cost) {
            if ($subtotal->lessThan(new Money($threshold, $this->currency))) {
                $subtotal = $subtotal->add($cost);
                break;
            }
        }

        return $subtotal;
    }
}
