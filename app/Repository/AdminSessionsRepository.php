<?php
namespace Sheilla\NailArt\Repository;

use Sheilla\NailArt\Domain\AdminSessions;

class AdminSessionsRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function insertSessions(AdminSessions $adminSessions) : AdminSessions
    {
        $statement = $this->connection->prepare("INSERT INTO admin_sessions (id, admin_id) VALUES(?,?)");
        $statement->execute([$adminSessions->id, $adminSessions->admin_id]);

        return $adminSessions;
    }

    public function findById(string $id) : ?AdminSessions
    {
        $statement = $this->connection->prepare("SELECT id, admin_id FROM admin_sessions WHERE id = ?");
        $statement->execute([$id]);

        try {
            if($row = $statement->fetch()){
                $adminSessions = new AdminSessions();
                $adminSessions->id = $row['id'];
                $adminSessions->admin_id = $row['admin_id'];
                return $adminSessions;
            } else{
                return null;
            }
        } finally{
            $statement->closeCursor();
        }
    }

    public function deleteById(string $id) : void
    {
        $statement = $this->connection->prepare("DELETE FROM admin_sessions WHERE id = ?");
        $statement->execute([$id]);
    }
}