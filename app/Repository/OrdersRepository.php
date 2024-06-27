<?php

namespace Sheilla\NailArt\Repository;

use PDO;
use Sheilla\NailArt\Domain\Orders;

class OrdersRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Orders $orders): Orders
    {
        $sql = <<< SQL
            INSERT INTO orders (id, 
                                customer_id, 
                                nailist_id, 
                                category_id,
                                price,
                                status,
                                date,
                                payment_confirm,
                                nailist_name,
                                category_name)
            VALUES (?,?,?,?,?,?,?,?,?,?)
        SQL;
        $statement = $this->connection->prepare($sql);
        $statement->execute([
            $orders->id,
            $orders->customer_id,
            $orders->nailist_id,
            $orders->category_id,
            $orders->price,
            $orders->status,
            $orders->date,
            $orders->payment_confirm,
            $orders->nailist_name,
            $orders->category_name,
        ]);

        return $orders;
    }

    public function update(Orders $orders): Orders
    {
        $sql = <<< SQL
            UPDATE orders 
            SET 
                customer_id = ?, 
                nailist_id = ?, 
                category_id = ?,
                price = ?,
                status = ?,
                date = ?,
                payment_confirm = ?,
                nailist_name = ?,
                category_name = ?,
                book_time = ?
            WHERE id = ?
        SQL;
        $statement = $this->connection->prepare($sql);
        $statement->execute([
            $orders->customer_id,
            $orders->nailist_id,
            $orders->category_id,
            $orders->price,
            $orders->status,
            $orders->date,
            $orders->payment_confirm,
            $orders->nailist_name,
            $orders->category_name,
            $orders->book_time,
            $orders->id
        ]);

        return $orders;
    }

    public function findById(string $id): ?Orders
    {
        $statement = $this->connection->prepare("SELECT * FROM orders WHERE id = ?");
        $statement->execute([$id]);
        if ($row = $statement->fetch()) {
            $orders = new Orders();
            $orders->id = $row['id'];
            $orders->customer_id = $row['customer_id'];
            $orders->nailist_id = $row['nailist_id'];
            $orders->category_id = $row['category_id'];
            $orders->price = $row['price'];
            $orders->status = $row['status'];
            $orders->date = $row['date'];
            $orders->payment_confirm = $row['payment_confirm'];
            $orders->nailist_name = $row['nailist_name'];
            $orders->category_name = $row['category_name'];
            $orders->book_time = $row['book_time'];

            return $orders;
        } else {
            return null;
        }
    }

    public function findAll(): ?array
    {
        $statement = $this->connection->prepare("SELECT * FROM orders");
        $statement->execute();
        if ($statement->rowCount() > 0) {
            return $statement->fetchAll();
        } else {
            return null;
        }
    }

    public function findAllHistory(): ?array
    {
        $statement = $this->connection->prepare("SELECT  cst.name, cst.phone , ors.* FROM orders as ors JOIN customer as cst ON(ors.customer_id = cst.id)");
        $statement->execute();
        if ($statement->rowCount() > 0) {
            return $statement->fetchAll();
        } else {
            return null;
        }
    }

    public function findByCustomerId(string $id): ?array
    {
        $statement = $this->connection->prepare("SELECT * FROM orders WHERE customer_id = ?");
        $statement->execute([$id]);
        if ($statement->rowCount() > 0) {
            return $statement->fetchAll();
        } else {
            return null;
        }
    }

    public function deleteById(string $id)
    {
        $statement = $this->connection->prepare("DELETE FROM order WHERE id = ?");
        $statement->execute([$id]);
    }
}
