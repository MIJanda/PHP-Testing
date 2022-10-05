<?php

use Cart;

use PHPUnit\Framework\TestCase;


class CartTest extends TestCase
{
    /** @test */
    public function correct_net_price_is_returned()
    {
        $cart = new Cart();
        $cart->price = 10;
        $netPrice = $cart->getNetPrice();

        $this->assertEquals(12, $netPrice);
    }

    
    /** @test */
    public function the_cart_tax_value_can_be_changed_statically()
    {
        Cart::$tax = 1.5;

        $cart = new Cart();
        $cart->price =10;
        $netPrice = $cart->getNetPrice();

        $this->assertEquals(15, $netPrice);
    }
    
    

}
