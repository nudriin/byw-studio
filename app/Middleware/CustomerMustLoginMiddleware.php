<?php

namespace Sheilla\NailArt\Middleware;

use Sheilla\NailArt\App\ViewRender;
use Sheilla\NailArt\Config\Database;
use Sheilla\NailArt\Repository\CustomerRepository;
use Sheilla\NailArt\Repository\CustomerSessionsRepository;
use Sheilla\NailArt\Service\CustomerSessionsService;


class CustomerMustLoginMiddleware
{
    private CustomerSessionsService $sessionsService;

    public function __construct()
    {
        $connection = Database::getConnect();
        $customerRepository = new CustomerRepository($connection);
        $customerSessionsRepository = new CustomerSessionsRepository($connection);
        $this->sessionsService = new CustomerSessionsService($customerSessionsRepository, $customerRepository);
    }

    public function before(): void
    {
        $user = $this->sessionsService->current();

        if ($user == null) {
            ViewRender::redirect("/customer/login");
        }
    }
}

