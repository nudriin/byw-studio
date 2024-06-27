<?php
namespace Sheilla\NailArt\Middleware;

use Sheilla\NailArt\Config\Database;
use Sheilla\NailArt\Repository\AccountRepository;
use Sheilla\NailArt\Repository\SessionsRepository;
use Sheilla\NailArt\Service\SessionsService;
use Sheilla\NailArt\App\ViewRender;
use Sheilla\NailArt\Repository\AdminRepository;
use Sheilla\NailArt\Repository\AdminSessionsRepository;
use Sheilla\NailArt\Service\AdminSessionsService;

class AdminMustLoginMiddleware{
    private AdminSessionsService $adminSessions;

    public function __construct() {
        $connection = Database::getConnect();
        $adminRepository = new AdminRepository($connection);
        $adminSessionsRepository = new AdminSessionsRepository($connection);
        $this->adminSessions = new AdminSessionsService($adminSessionsRepository, $adminRepository);
    }

    public function before(): void
    {
        $admin = $this->adminSessions->current();

        if($admin == null){
            ViewRender::redirect("/admin/login");
        }

    }
}