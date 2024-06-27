<?php
namespace Sheilla\NailArt\Model\Schedule;

class ScheduleUpdateRequest
{
    public ?string $id = null;
    public ?string $date = null;
    public ?string $status = null;
    public ?string $book_time = null;
}