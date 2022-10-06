<?php

use PHPUnit\Framework\TestCase;

use App\ProductsRepo;

/**
 * InventoryTest
 * @group db
 */
class InventoryTest extends TestCase
{
    /** @test */
    public function test_products_can_be_set()
    {
        // setup
        $mockRepo = $this->createMock(ProductsRepo::class);

        $inventory = new \App\Inventory($mockRepo);

        // test array
        $mockProducts = [
            ['id' => 1, 'name' => 'Burna'],
            ['id' => 2, 'name' => 'Damini'],
            ['id' => 3, 'name' => 'African Giant'],
        ];
        

        // anticipate $mockProductsy as respective function return
        $mockRepo->method('fetchProducts')->willReturn($mockProducts);

        // do something
        $inventory->setProducts();

        // make assertion(s)
        $this->assertSame('African Giant', $inventory->getProducts()[2]['name']);
        $this->assertSame('Burna', $inventory->getProducts()[0]['name']);

    }

}
