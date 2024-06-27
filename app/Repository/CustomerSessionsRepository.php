<?php
namespace Sheilla\NailArt\Repository;

use Sheilla\NailArt\Domain\CustomerSessions;

class CustomerSessionsRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function insertSessions(CustomerSessions $customerSessions) : CustomerSessions
    {
        $statement = $this->connection->prepare("INSERT INTO customer_sessions (id, customer_id) VALUES(?,?)");
        $statement->execute([$customerSessions->id, $customerSessions->customer_id]);

        return $customerSessions;
    }

    public function findById(string $id) : ?CustomerSessions
    {
        $statement = $this->connection->prepare("SELECT id, customer_id FROM customer_sessions WHERE id = ?");
        $statement->execute([$id]);

        try {
            if($row = $statement->fetch()){
                $customerSessions = new CustomerSessions();
                $customerSessions->id = $row['id'];
                $customerSessions->customer_id = $row['customer_id'];
                return $customerSessions;
            } else{
                return null;
            }
        } finally{
            $statement->closeCursor();
        }
    }

    public function deleteById(string $id) : void
    {
        $statement = $this->connection->prepare("DELETE FROM customer_sessions WHERE id = ?");
        $statement->execute([$id]);
    }
}