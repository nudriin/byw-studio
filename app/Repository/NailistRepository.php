<?php
namespace Sheilla\NailArt\Repository;

use PDO;
use Sheilla\NailArt\Domain\Nailist;

class NailistRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Nailist $nailist) : Nailist
    {
        $statement = $this->connection->prepare("INSERT INTO nailist (id, name, picture) VALUES(?,?, ?)");
        $statement->execute([$nailist->id, $nailist->name, $nailist->picture]);

        return $nailist;
    }

    public function update(Nailist $nailist) : Nailist
    {
        $statement = $this->connection->prepare("UPDATE nailist SET name = ?, picture = ? WHERE id = ?");
        $statement->execute([$nailist->name, $nailist->picture, $nailist->id]);

        return $nailist;
    }

    public function findById(string $id) : ?Nailist
    {
        $statement = $this->connection->prepare("SELECT * FROM nailist WHERE id = ?");
        $statement->execute([$id]);

        if($row = $statement->fetch()){
            $nailist = new Nailist();
            $nailist->id = $row['id'];
            $nailist->name = $row['name']; 
            $nailist->picture = $row['picture']; 

            return $nailist;
        } else {
            return null;
        }
    }

    public function findByName(string $name) : ?Nailist
    {
        $statement = $this->connection->prepare("SELECT * FROM nailist WHERE name = ?");
        $statement->execute([$name]);

        if($row = $statement->fetch()){
            $nailist = new Nailist();
            $nailist->id = $row['id'];
            $nailist->name = $row['name']; 
            $nailist->picture = $row['picture']; 

            return $nailist;
        } else {
            return null;
        }
    }

    public function findAll() : ?array
    {
        $statement = $this->connection->prepare("SELECT * FROM nailist");
        $statement->execute();
        if($statement->rowCount() > 0){
            return $statement->fetchAll();
        } else {
            return null;
        }
    }

    public function deleteById(string $id)
    {
        $statement = $this->connection->prepare("DELETE FROM nailist WHERE id = ?");
        $statement->execute([$id]);
    }
    

}