<?php

namespace App;

use App\ProductsRepo;

class Inventory 
{
    private array $products;

    public function __construct(private ProductsRepo $productsRepo)
    {

    }

    public function setProducts()
    {
        // fetch products 
        $this->products = $this->productsRepo->fetchProducts();
    }

    public function getProducts(): array 
    {
        return $this->products;
    }
}