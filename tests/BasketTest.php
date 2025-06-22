<?php

use PHPUnit\Framework\TestCase;
use Acme\Basket;
use Acme\Offers\BuyOneGetSecondHalfPrice;
use Money\Money;
use Money\Currency;

class BasketTest extends TestCase
{
    private Currency $currency;

    protected function setUp(): void
    {
        $this->currency = new Currency('USD');
    }

    public function createBasket(): Basket
    {
        $catalogue = [
            'R01' => new Money(3295, $this->currency),
            'G01' => new Money(2495, $this->currency),
            'B01' => new Money(795,  $this->currency)
        ];

        $deliveryRules = [
            5000  => new Money(495, $this->currency),
            9000  => new Money(295, $this->currency),
            PHP_INT_MAX => new Money(0, $this->currency)
        ];

        $offers = [new BuyOneGetSecondHalfPrice('R01')];

        return new Basket($catalogue, $deliveryRules, $offers);
    }

    public function assertTotalEquals(float $expectedAmount, Money $actual)
    {
        $expected = new Money((int) round($expectedAmount * 100), $this->currency);
        $this->assertTrue(
            $actual->equals($expected),
            sprintf('Expected total: %s, got: %s', $expected->getAmount(), $actual->getAmount())
        );
    }

    public function testBasketWithB01G01()
    {
        $basket = $this->createBasket();
        $basket->add('B01');
        $basket->add('G01');
        $this->assertTotalEquals(37.85, $basket->total());
    }

    public function testBasketWithTwoR01()
    {
        $basket = $this->createBasket();
        $basket->add('R01');
        $basket->add('R01');
        $this->assertTotalEquals(54.37, $basket->total());
    }

    public function testBasketWithR01G01()
    {
        $basket = $this->createBasket();
        $basket->add('R01');
        $basket->add('G01');
        $this->assertTotalEquals(60.85, $basket->total());
    }

    public function testBasketWithMultipleItems()
    {
        $basket = $this->createBasket();
        $basket->add('B01');
        $basket->add('B01');
        $basket->add('R01');
        $basket->add('R01');
        $basket->add('R01');
        $this->assertTotalEquals(98.27, $basket->total());
    }
}
