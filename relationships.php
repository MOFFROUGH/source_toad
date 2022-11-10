<?php

class Item
{
    public function __construct(public int    $id,
                                public string $name,
                                public int    $quantity,
                                public float  $price)
    {
    }

    public function cart()
    {
        // this table need to have a pivot table relationship with cart and item ids
        return $this->belongsToMany(Cart::class, 'cart_items_table', 'cart_id', 'item_id');
    }

}

class Customer
{
    public string $first_name;
    public string $last_name;
    public array $addresses;

    function getFullName(): string
    {
        return $this->first_name . " " . $this->last_name;
    }

    function getAddresses(): array
    {
        return $this->addresses;
    }
}

class Address
{
    public function __construct(public string $line_1,
                                public string $line_2,
                                public string $city,
                                public string $state,
                                public string $zip)
    {
    }

    public function customer()
    {
        // this table need to have a customer_id field
        return $this->belongsTo(Customer::class);
    }
}

class Cart
{
    public Customer $customer;
    public array $items;
    public float $tax_rate;
    public float $shipping_cost;

    public function getShippingCost(): void
    {
        $this->shipping_cost = (new ShippingApi())->getCost($this->customer->addresses);
    }

    public function getItemsCost(): float|int
    {
        $total = 0.0;
        foreach ($this->items as $item) {
            $total += $item['quantity'] * $item['price'];
        }
        return $total;
    }

    public function getTotalCost(): float|int
    {
        $itemCost = $this->getItemsCost();
        $this->getShippingCost();
        $taxes = ($this->getItemsCost()) * $this->tax_rate;
        return $itemCost + $taxes + $this->shipping_cost;
    }

    public function setItem(Item $item): void
    {
        $this->items[] = $item;
    }

    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    //******* Laravel style relationship
    public function customer()
    {
        // this table need to have a customer_id field
        return $this->hasOne(Customer::class);
    }

    public function cartItems()
    {
        // this table need to have a pivot table relationship with cart and item ids
       return  $this->belongsToMany(Item::class, 'cart_items_table', 'item_id', 'cart_id');
    }
}
