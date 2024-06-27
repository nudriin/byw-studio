<?php
namespace Sheilla\NailArt\Service;

use Sheilla\NailArt\Domain\Admin;
use Sheilla\NailArt\Domain\AdminSessions;
use Sheilla\NailArt\Repository\AdminRepository;
use Sheilla\NailArt\Repository\AdminSessionsRepository;

class AdminSessionsService
{
    private static string $SESSION_NAME = "X-ADMIN-SESSION";
    private AdminSessionsRepository $adminSessionsRepository;
    private AdminRepository $adminRepository;
    
    public function __construct(AdminSessionsRepository $adminSessionsRepository, AdminRepository $adminRepository) {
        $this->adminSessionsRepository = $adminSessionsRepository;
        $this->adminRepository = $adminRepository;
    }

    public function createSession(string $admin_id) : AdminSessions
    {
        $adminSessions = new AdminSessions();
        $adminSessions->id = uniqid();
        $adminSessions->admin_id = $admin_id;

        $this->adminSessionsRepository->insertSessions($adminSessions);

        setcookie(self::$SESSION_NAME, $adminSessions->id, time() + (60 * 60 * 24 * 30), "/");

        return $adminSessions;
    }

    public function current() : ?Admin
    {
        $sessionId = $_COOKIE[self::$SESSION_NAME] ?? '';

        $adminSessions = $this->adminSessionsRepository->findById($sessionId);

        if($adminSessions == null){
            return null;
        }

        return $this->adminRepository->findById($adminSessions->admin_id);
    }

    public function destroy()
    {
        $sessionId = $_COOKIE[self::$SESSION_NAME] ?? '';

        $this->adminSessionsRepository->deleteById($sessionId);
        // set session to expired by (1 = masa lampau) 
        setcookie(self::$SESSION_NAME, "", 1, "/");
    }
}