<?php
namespace Sheilla\NailArt\Controller;

use DateInterval;
use DateTime;
use Sheilla\NailArt\App\ViewRender;
use Sheilla\NailArt\Config\Database;
use Sheilla\NailArt\Exception\ValidationException;
use Sheilla\NailArt\Model\Schedule\ScheduleDeleteRequest;
use Sheilla\NailArt\Model\Schedule\ScheduleDisplayAllBydateRequest;
use Sheilla\NailArt\Model\Schedule\ScheduleDisplayByWeekRequest;
use Sheilla\NailArt\Model\Schedule\ScheduleDisplayRequest;
use Sheilla\NailArt\Model\Schedule\ScheduleSaveRequest;
use Sheilla\NailArt\Model\Schedule\ScheduleUpdateRequest;
use Sheilla\NailArt\Model\Schedule\ScheduleUpdateStatusRequest;
use Sheilla\NailArt\Repository\AdminRepository;
use Sheilla\NailArt\Repository\AdminSessionsRepository;
use Sheilla\NailArt\Repository\ScheduleRepository;
use Sheilla\NailArt\Service\AdminSessionsService;
use Sheilla\NailArt\Service\ScheduleService;

class ScheduleController
{
    private ScheduleService $scheduleService;
    private AdminSessionsService $adminSessionsService;

    public function __construct()
    {  
        $connection = Database::getConnect();
        $scheduleRepository = new ScheduleRepository($connection);
        $adminSessionsRepository = new AdminSessionsRepository($connection);
        $adminRepository = new AdminRepository($connection);
        $this->scheduleService = new ScheduleService($scheduleRepository);
        $this->adminSessionsService = new AdminSessionsService($adminSessionsRepository, $adminRepository);
    }

    public function addSchedule()
    {
        try {
            $admin = $this->adminSessionsService->current();
            ViewRender::adminRender("Schedule/schedule-add", [
                "title" => "Tambah Jadwal",
                "admin" => $admin
            ]);
        } catch (ValidationException $e) {
            
        }
    }
    
    public function postAddSchedule()
    {
        try {
            $admin = $this->adminSessionsService->current();
            $request = new ScheduleSaveRequest();
            $request->date = htmlspecialchars($_POST['dates']);
            $request->book_time = htmlspecialchars($_POST['book_time']) . ":00";
            
            $this->scheduleService->saveSchedule($request);
            ViewRender::redirect("/admin/schedule");
        } catch (ValidationException $e) {
            ViewRender::adminRender("Schedule/schedule-add", [
                "title" => "Tambah Jadwal",
                "error" => $e->getMessage(),
                "admin" => $admin
            ]);
        }   
    }
    
    public function updateSchedule(string $id)
    {
        try {
            $admin = $this->adminSessionsService->current();
            $request = new ScheduleDisplayRequest();
            $request->id = $id;
            $schedule = $this->scheduleService->displaySchedule($request);
            ViewRender::adminRender("Schedule/schedule-update", [
                "title" => "Update Jadwal",
                "schedule"=> $schedule->schedule,
                "admin" => $admin
            ]);
        } catch (ValidationException $e) {
            ViewRender::adminRender("Schedule/schedule-update", [
                "title" => "Update Jadwal",
                "error" => $e->getMessage(),
                "admin" => $admin
            ]);
        }
    }
    
    public function postUpdateSchedule(string $id)
    {
        try {
            $admin = $this->adminSessionsService->current();
            
            $displayRequest = new ScheduleDisplayRequest();
            $displayRequest->id = $id;
            $schedule = $this->scheduleService->displaySchedule($displayRequest);
            
            $request = new ScheduleUpdateRequest();
            $request->date = htmlspecialchars($_POST['dates']);
            $request->status = htmlspecialchars($_POST['status']);
            $request->book_time = htmlspecialchars($_POST['book_time']);
            
            $this->scheduleService->updateSchedule($request);
            ViewRender::redirect("/admin/schedule");
        } catch (ValidationException $e) {
            ViewRender::adminRender("Schedule/schedule-update", [
                "title" => "Update Jadwal",
                "schedule"=> $schedule->schedule,
                "admin" => $admin
            ]);
        }
    } 
    
    public function deleteSchedule(string $id)
    {
        try {
            $request = new ScheduleDeleteRequest();
            $request->id = $id;
            
            $this->scheduleService->deleteSchedule($request);
            ViewRender::redirect("/admin/schedule");
        } catch (ValidationException $e) {
            
        }
    }
    
    public function displayScheduleByWeek()
    {
        try {
            $admin = $this->adminSessionsService->current();
            $request = new ScheduleDisplayByWeekRequest();
            $today = new DateTime();
            $next_week = new DateTime();
            $next_week->add(new DateInterval("P6D"));
            $request->today = $today->format("Y-m-d");
            $request->next_week = $next_week->format("Y-m-d");
            $schedule = $this->scheduleService->displayByWeek($request);
            
            ViewRender::adminRender("Schedule/schedule-week", [
                "title" => "Schedule",
                "schedule" => $schedule->schedule,
                "admin" => $admin
            ]);
            
        } catch (ValidationException $e) {
            ViewRender::adminRender("Schedule/schedule-week", [
                "title" => "Schedule",
                "error" => $e->getMessage(),
                "admin" => $admin
            ]);
        }
    }
    
    public function displayScheduleByDate(string $date)
    {
        try {
            $admin = $this->adminSessionsService->current();
            $request = new ScheduleDisplayAllBydateRequest();
            $request->date = $date;
            $schedule = $this->scheduleService->displayAllByDate($request);
            ViewRender::adminRender("Schedule/schedule-details", [
                "title" => "Schedule",
                "schedule" => $schedule->schedule,
                "admin" => $admin
            ]);
        } catch (ValidationException $e) {
            ViewRender::adminRender("Schedule/schedule-details", [
                "title" => "Schedule",
                "error" => $e->getMessage(),
                "admin" => $admin
            ]);
        }
    }
    
    public function updateStatusSchedule(string $id)
    {
        try {
            $admin = $this->adminSessionsService->current();
            $request = new ScheduleUpdateStatusRequest();
            $request->id = $id;
            $request->status = htmlspecialchars($_POST['status']);
            
            $this->scheduleService->updateStatusSchedule($request);
            ViewRender::redirect("/admin/schedule");
        } catch (ValidationException $e) {
            ViewRender::adminRender("Schedule/schedule-details", [
                "title" => "Schedule",
                "error" => $e->getMessage(),
                "admin" => $admin
            ]);
        }
    }
}