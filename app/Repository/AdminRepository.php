<?php
namespace Sheilla\NailArt\Repository;

use PDO;
use Sheilla\NailArt\Domain\Admin;

class AdminRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Admin $admin) : Admin
    {
        $statement = $this->connection->prepare("INSERT INTO admin (id, username, name, password) VALUES(?,?,?,?)");
        $statement->execute([$admin->id, $admin->username, $admin->name, $admin->password]);

        return $admin;
    }

    public function update(Admin $admin) : Admin
    {
        $statement = $this->connection->prepare("UPDATE admin SET username = ?, name = ?, password = ? WHERE id = ?");
        $statement->execute([$admin->username, $admin->name, $admin->password, $admin->id]);

        return $admin;
    }

    public function findById(string $id) : ?Admin
    {
        $statement = $this->connection->prepare("SELECT * FROM admin WHERE id = ?");    
        $statement->execute([$id]);
        if($row = $statement->fetch()){
            $admin = new Admin();
            $admin->id = $row['id'];
            $admin->username = $row['username'];
            $admin->name = $row['name'];
            $admin->password = $row['password'];

            return $admin;
        } else {
            return null;
        }
    }

    public function findByUsername(string $username) : ?Admin
    {
        $statement = $this->connection->prepare("SELECT * FROM admin WHERE username = ?");    
        $statement->execute([$username]);
        if($row = $statement->fetch()){
            $admin = new Admin();
            $admin->id = $row['id'];
            $admin->username = $row['username'];
            $admin->name = $row['name'];
            $admin->password = $row['password'];

            return $admin;
        } else {
            return null;
        }
    }

    public function findAll() : ?array
    {
        $statement = $this->connection->prepare("SELECT * FROM admin");
        $statement->execute();

        if($statement->rowCount() > 0){
            return $statement->fetchAll();
        } else{
            return null;
        }
    }

    public function deleteById(string $id)
    {
        $statement = $this->connection->prepare("DELETE FROM admin WHERE id = ?");
        $statement->execute([$id]);
    }
}