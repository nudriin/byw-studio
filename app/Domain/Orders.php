<?php
namespace Sheilla\NailArt\Domain;

class Orders
{
    public string $id;
    public string $customer_id;
    public string $nailist_id;
    public string $category_id;
    public int $price;
    public string $status;
    public string $date;
    public string $payment_confirm;
    public string $nailist_name;
    public string $category_name;
    public ?string $book_time;
}