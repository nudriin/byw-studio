<?php
namespace Sheilla\NailArt\Repository;

use PDO;
use Sheilla\NailArt\Domain\Category;

class CategoryRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Category $category) : Category
    {
        $statement = $this->connection->prepare("INSERT INTO category (id, name) VALUES(?,?)");
        $statement->execute([$category->id, $category->name]);

        return $category;
    }

    public function update(Category $category) : Category
    {
        $statement = $this->connection->prepare("UPDATE category SET name = ? WHERE id = ?");
        $statement->execute([$category->name, $category->id]);

        return $category;
    }

    public function findById(string $id) : ?Category
    {
        $statement = $this->connection->prepare("SELECT * FROM category WHERE id = ?");
        $statement->execute([$id]);

        if($row = $statement->fetch()){
            $category = new Category();
            $category->id = $row['id'];
            $category->name = $row['name']; 

            return $category;
        } else {
            return null;
        }
    }

    public function findByName(string $name) : ?Category
    {
        $statement = $this->connection->prepare("SELECT * FROM category WHERE name = ?");
        $statement->execute([$name]);

        if($row = $statement->fetch()){
            $category = new Category();
            $category->id = $row['id'];
            $category->name = $row['name']; 

            return $category;
        } else {
            return null;
        }
    }

    public function findAll() : ?array
    {
        $statement = $this->connection->prepare("SELECT * FROM category");
        $statement->execute();
        if($statement->rowCount() > 0){
            return $statement->fetchAll();
        } else {
            return null;
        }
    }

    public function deleteById(string $id)
    {
        $statement = $this->connection->prepare("DELETE FROM category WHERE id = ?");
        $statement->execute([$id]);
    }
    

}