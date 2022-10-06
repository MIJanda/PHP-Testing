<?php

use PHPUnit\Framework\TestCase;
use App\ProductsRepo;

/**
 * MockProductsTest
 */
class MockProductsTest extends TestCase
{
    /** @test */
    public function mock_products_are_returned_test()
    {
        // mock instance inherits from ProductRepo
        $mockRepo = $this->createMock(ProductsRepo::class);

        // test array
        $mockProducts = [
            ['id' => 1, 'name' => 'Burna'],
            ['id' => 2, 'name' => 'Damini'],
            ['id' => 3, 'name' => 'African Giant'],
        ];
        

        // anticipate $mockProductsy as respective function return
        $mockRepo->method('fetchProducts')->willReturn($mockProducts);

        // products
        $products = $mockRepo->fetchProducts();

        // make assertion between $mockProducts and $products
        $this->assertSame('Damini', $products[1]['name']);
    }
}