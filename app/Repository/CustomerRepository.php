<?php
namespace Sheilla\NailArt\Repository;

use PDO;
// use Sheilla\NailArt\Domain\Cu
use Sheilla\NailArt\Domain\Customer;

class CustomerRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Customer $customer) : Customer
    {
        $statement = $this->connection->prepare("INSERT INTO customer (id, username, name, phone, password) VALUES(?,?,?,?,?)");
        $statement->execute([$customer->id, $customer->username, $customer->name, $customer->phone, $customer->password]);

        return $customer;
    }

    public function update(Customer $customer) : Customer
    {
        $statement = $this->connection->prepare("UPDATE customer SET username = ?, name = ?, phone = ?, password = ? WHERE id = ?");
        $statement->execute([$customer->username, $customer->name, $customer->phone, $customer->password, $customer->id]);

        return $customer;
    }

    public function findById(string $id) : ?Customer
    {
        $statement = $this->connection->prepare("SELECT * FROM customer WHERE id = ?");    
        $statement->execute([$id]);
        if($row = $statement->fetch()){
            $customer = new Customer();
            $customer->id = $row['id'];
            $customer->username = $row['username'];
            $customer->name = $row['name'];
            $customer->phone = $row['phone'];
            $customer->password = $row['password'];

            return $customer;
        } else {
            return null;
        }
    }

    public function findByUsername(string $username) : ?Customer
    {
        $statement = $this->connection->prepare("SELECT * FROM customer WHERE username = ?");    
        $statement->execute([$username]);
        if($row = $statement->fetch()){
            $customer = new Customer();
            $customer->id = $row['id'];
            $customer->username = $row['username'];
            $customer->name = $row['name'];
            $customer->phone = $row['phone'];
            $customer->password = $row['password'];

            return $customer;
        } else {
            return null;
        }
    }

    public function findAll() : ?array
    {
        $statement = $this->connection->prepare("SELECT * FROM customer");
        $statement->execute();

        if($statement->rowCount() > 0){
            return $statement->fetchAll();
        } else{
            return null;
        }
    }

    public function deleteById(string $id)
    {
        $statement = $this->connection->prepare("DELETE FROM customer WHERE id = ?");
        $statement->execute([$id]);
    }
}