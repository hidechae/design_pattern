<?php

$factory = new IDCardFactory();
$card1 = $factory->create('hideyuki-utsunomiya');
$card2 = $factory->create('koichi-osanai');
$card3 = $factory->create('takeshi-kunimi');

$card1->useProduct();
$card2->useProduct();
$card3->useProduct();

print_r($factory->getOwners());

abstract class Product
{
    abstract function useProduct();
}

abstract class Factory
{
    public final function create($owner)
    {
        $product = $this->createProduct($owner);
        $this->registerProduct($product);
        return $product;
    }
    protected abstract function createProduct($owner);
    protected abstract function registerProduct($product);
}

class IDCard extends Product
{
    private $owner;

    public function __construct($owner)
    {
        echo "Create $owner's card.\n";
        $this->owner = $owner;
    }

    public function useProduct()
    {
        echo "Use " . $this->owner . "'s card.\n";
    }

    public function getOwner()
    {
        return $this->owner;
    }
}

class IDCardFactory extends Factory
{
    // XXX why is array used ??
    private $owners = array();

    protected function createProduct($owner)
    {
        return new IDCard($owner);
    }

    protected function registerProduct($product)
    {
        $this->owners[] = $product->getOwner();
    }

    public function getOwners() {
        return $this->owners;
    }
}
