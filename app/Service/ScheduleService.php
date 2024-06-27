<?php
namespace Sheilla\NailArt\Service;

use Sheilla\NailArt\Config\Database;
use Sheilla\NailArt\Domain\Schedule;
use Sheilla\NailArt\Exception\ValidationException;
use Sheilla\NailArt\Model\Schedule\ScheduleDeleteRequest;
use Sheilla\NailArt\Model\Schedule\ScheduleDisplayAllBydateRequest;
use Sheilla\NailArt\Model\Schedule\ScheduleDisplayAllBydateResponse;
use Sheilla\NailArt\Model\Schedule\ScheduleDisplayByDateAndTimeRequest;
use Sheilla\NailArt\Model\Schedule\ScheduleDisplayByDateAndTimeResponse;
use Sheilla\NailArt\Model\Schedule\ScheduleDisplayByWeekRequest;
use Sheilla\NailArt\Model\Schedule\ScheduleDisplayByWeekResponse;
use Sheilla\NailArt\Model\Schedule\ScheduleDisplayRequest;
use Sheilla\NailArt\Model\Schedule\ScheduleDisplayResponse;
use Sheilla\NailArt\Model\Schedule\ScheduleSaveRequest;
use Sheilla\NailArt\Model\Schedule\ScheduleSaveResponse;
use Sheilla\NailArt\Model\Schedule\ScheduleUpdateRequest;
use Sheilla\NailArt\Model\Schedule\ScheduleUpdateResponse;
use Sheilla\NailArt\Model\Schedule\ScheduleUpdateStatusRequest;
use Sheilla\NailArt\Model\Schedule\ScheduleUpdateStatusResponse;
use Sheilla\NailArt\Repository\ScheduleRepository;

class ScheduleService
{
    private ScheduleRepository $scheduleRepository;

    public function __construct(ScheduleRepository $scheduleRepository)
    {
        $this->scheduleRepository = $scheduleRepository;
    }

    public function saveSchedule(ScheduleSaveRequest $request) : ScheduleSaveResponse
    {
        $this->validateSaveSchedule($request);
        try {
            Database::beginTransaction();
            $schedule = new Schedule();
            $schedule->id = "SCH" . uniqid();
            $schedule->date = $request->date;
            $schedule->status = "Aktif";
            $schedule->book_time = $request->book_time;
            $this->scheduleRepository->save($schedule);
            Database::commitTransaction();

            $response = new ScheduleSaveResponse();
            $response->schedule = $schedule;
            return $response;
        } catch (ValidationException $e) {
            Database::rollbackTransaction();
            throw $e;
        }
    }

    public function validateSaveSchedule(ScheduleSaveRequest $request)
    {
        if($request->date == null || trim($request->date) == ""){
            throw new ValidationException("Tanggal tidak boleh kosong");
        }
    }

    public function updateSchedule(ScheduleUpdateRequest $request) : ScheduleUpdateResponse
    {
        $this->validateUpdateSchedule($request);
        try {
            Database::beginTransaction();
            $schedule = $this->scheduleRepository->findById($request->id);
            if($schedule == null){
                throw new ValidationException("Gagal update jadwal");
            }
            $schedule->date = $request->date;
            $schedule->status = $request->status;
            $schedule->book_time = $request->book_time;

            $this->scheduleRepository->update($schedule);
            Database::commitTransaction();

            $response = new ScheduleUpdateResponse();
            $response->schedule = $schedule;
            return $response;
        } catch (ValidationException $e) {
            Database::rollbackTransaction();
            throw $e;
        }
    }

    public function validateUpdateSchedule(ScheduleUpdateRequest $request)
    {
        if($request->id == null || $request->status == null || $request->date == null || $request->book_time || trim($request->id) == "" ||trim($request->date) == "" || trim($request->status) == "" || trim($request->book_time) == ""){
            throw new ValidationException("Tanggal, status, dan jam tidak boleh kosong");
        }
    }

    public function updateStatusSchedule(ScheduleUpdateStatusRequest $request) : ScheduleUpdateStatusResponse
    {
        $this->validateUpdateStatusSchedule($request);
        try {
            Database::beginTransaction();
            $schedule = $this->scheduleRepository->findById($request->id);
            if($schedule == null){
                throw new ValidationException("Gagal update status jadwal");
            }
            $schedule->status = $request->status;
            
            $this->scheduleRepository->update($schedule);
            Database::commitTransaction();

            $response = new ScheduleUpdateStatusResponse();
            $response->schedule = $schedule;
            return $response;
        } catch (ValidationException $e) {
            Database::rollbackTransaction();
            throw $e;
        }
    }

    public function validateUpdateStatusSchedule(ScheduleUpdateStatusRequest $request)
    {
        if($request->id == null || $request->status == null || trim($request->id) == "" || trim($request->status) == ""){
            throw new ValidationException("Status tidak boleh kosong");
        }
    }

    public function deleteSchedule(ScheduleDeleteRequest $request)
    {
        try {
            Database::beginTransaction();
            $schedule = $this->scheduleRepository->findById($request->id);
            if($schedule == null){
                throw new ValidationException("Gagal menghapus jadwal");
            }

            $this->scheduleRepository->deleteById($request->id);
            Database::commitTransaction();
        } catch (ValidationException $e) {
            Database::rollbackTransaction();
            throw $e;
        }
    }

    public function validateDeleteSchedule(ScheduleDeleteRequest $request)
    {
        if($request->id == null || trim($request->id) == ""){
            throw new ValidationException("Gagal menghapus jadwal");
        }
    }

    public function displaySchedule(ScheduleDisplayRequest $request) : ScheduleDisplayResponse
    {
        try{
            $schedule = $this->scheduleRepository->findById($request->id);
            if($schedule == null){
                throw new ValidationException("Jadwal tidak ditemukan");
            }

            $response = new ScheduleDisplayResponse();
            $response->$schedule = $schedule;
            return $response;
        } catch(ValidationException $e){
            throw $e;
        }
    }

    public function displayByWeek(ScheduleDisplayByWeekRequest $request) : ScheduleDisplayByWeekResponse 
    {
        try {
            $schedule = $this->scheduleRepository->findAllByWeek($request->today, $request->next_week, "Aktif");
            if($schedule == null){
                throw new ValidationException("Belum ada jadwal apapun");
            }

            $response = new ScheduleDisplayByWeekResponse();
            $response->schedule = $schedule;

            return $response;
        } catch (ValidationException $e) {
            throw $e;
        }
    }

    public function displayAllByDate(ScheduleDisplayAllBydateRequest $request) : ScheduleDisplayAllBydateResponse 
    {
        try {
            $schedule = $this->scheduleRepository->findAllByDate($request->date);
            if($schedule == null){
                throw new ValidationException("Belum ada jadwal apapun");
            }

            $response = new ScheduleDisplayAllBydateResponse();
            $response->schedule = $schedule;

            return $response;
        } catch (ValidationException $e) {
            throw $e;
        }
    }

    public function displayByDateAndTime(ScheduleDisplayByDateAndTimeRequest $request) : ScheduleDisplayByDateAndTimeResponse
    {
        try {
            $schedule = $this->scheduleRepository->findByDateAndTime($request->date,  $request->book_time);
            if($schedule == null){
                throw new ValidationException("Jadwal tidak ditemukan");
            }

            $response = new ScheduleDisplayByDateAndTimeResponse();
            $response->schedule = $schedule;
            return $response;
        } catch (ValidationException $e) {
            throw $e;
        }
    }

}