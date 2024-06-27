<?php
namespace Sheilla\NailArt\Model\Orders;

class OrdersUpdateRequest
{
    public ?string $id;
    public ?string $date;
    public ?string $book_time;
    public ?string $status;
}