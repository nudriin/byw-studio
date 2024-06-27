<?php
namespace Sheilla\NailArt\Controller;

use Sheilla\NailArt\App\ViewRender;
use Sheilla\NailArt\Config\Database;
use Sheilla\NailArt\Exception\ValidationException;
use Sheilla\NailArt\Repository\AdminRepository;
use Sheilla\NailArt\Repository\AdminSessionsRepository;
use Sheilla\NailArt\Service\AdminService;
use Sheilla\NailArt\Service\AdminSessionsService;

class AdminHomeController
{
    private AdminService $adminService;
    private AdminSessionsService $adminSessionsService;

    public function __construct()
    {
        $connection = Database::getConnect();
        $adminRepository = new AdminRepository($connection);
        $adminSessionsRepository = new AdminSessionsRepository($connection);
        $this->adminService = new AdminService($adminRepository);
        $this->adminSessionsService = new AdminSessionsService($adminSessionsRepository, $adminRepository);
    }

    public function index()
    {
        try {
            $admin = $this->adminSessionsService->current();
            ViewRender::adminRender("Admin/dashboard", [
                "title"=>"Dashboard",
                "admin"=>$admin
            ]);
        } catch (ValidationException $e) {

        }
    }
}