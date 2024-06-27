<?php
namespace Sheilla\NailArt\Repository;

use PDO;
use Sheilla\NailArt\Domain\Schedule;

class ScheduleRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Schedule $schedule) : Schedule
    {
        $statement = $this->connection->prepare("INSERT INTO schedule (id, date, status, book_time) VALUES(?, ?, ?, ?)");
        $statement->execute([$schedule->id, $schedule->date, $schedule->status, $schedule->book_time]);
        
        return $schedule;
    }
    
    public function update(Schedule $schedule) : Schedule
    {
        $statement = $this->connection->prepare("UPDATE schedule SET date = ?, status = ?, book_time = ? WHERE id = ?");
        $statement->execute([$schedule->date , $schedule->status, $schedule->book_time, $schedule->id]);

        return $schedule;
    }

    public function findById(string $id) : ?Schedule
    {
        $statement = $this->connection->prepare("SELECT * FROM schedule WHERE id = ?");
        $statement->execute([$id]);
        if($row = $statement->fetch()){
            $schedule = new Schedule();
            $schedule->id = $row['id'];
            $schedule->date = $row['date'];
            $schedule->status = $row['status'];
            $schedule->book_time = $row['book_time'];

            return $schedule;
        } else {
            return null;
        }
    }
    public function findByDateAndTime(string $date, string $book_time) : ?Schedule
    {
        $statement = $this->connection->prepare("SELECT * FROM schedule WHERE date = ? AND book_time = ?");
        $statement->execute([$date, $book_time]);
        if($row = $statement->fetch()){
            $schedule = new Schedule();
            $schedule->id = $row['id'];
            $schedule->date = $row['date'];
            $schedule->status = $row['status'];
            $schedule->book_time = $row['book_time'];
            return $schedule;
        } else {
            return null;
        }
    }

    public function findAll() : ?array 
    {
        $statement = $this->connection->prepare("SELECT * FROM schedule");
        $statement->execute();
        if($statement->rowCount() > 0){
            return $statement->fetchAll();
        } else {
            return null;
        }
    }

    public function findAllByDate(string $date) : ?array 
    {
        $statement = $this->connection->prepare("SELECT * FROM schedule WHERE date = ? ORDER BY book_time DESC");
        $statement->execute([$date]);
        if($statement->rowCount() > 0){
            return $statement->fetchAll();
        } else {
            return null;
        }
    }

    public function findAllByWeek(string $today, string $next_week, string $status) : ?array 
    {
        $statement = $this->connection->prepare("SELECT DISTINCT date FROM schedule WHERE date BETWEEN ? AND ? AND status = ? ORDER BY date ASC");
        $statement->execute([$today, $next_week, $status]);
        if($statement->rowCount() > 0){
            return $statement->fetchAll();
        } else {
            return null;
        }
    }

    public function deleteById(string $id) 
    {
        $statement = $this->connection->prepare("DELETE FROM schedule WHERE id = ?");
        $statement->execute();
    }
}