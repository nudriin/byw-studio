<?php
namespace Sheilla\NailArt\Controller;

use Sheilla\NailArt\App\ViewRender;
use Sheilla\NailArt\Config\Database;
use Sheilla\NailArt\Exception\ValidationException;
use Sheilla\NailArt\Repository\CustomerRepository;
use Sheilla\NailArt\Repository\CustomerSessionsRepository;
use Sheilla\NailArt\Service\CustomerService;
use Sheilla\NailArt\Service\CustomerSessionsService;

class CustomerHomeController
{
    private CustomerSessionsService $customerSessionsService;

    public function __construct()
    {
        $connection = Database::getConnect();
        $customerRepository = new CustomerRepository($connection);
        $sessionsRepository = new CustomerSessionsRepository($connection);
        $this->customerSessionsService = new CustomerSessionsService($sessionsRepository, $customerRepository);
    }

    public function index()
    {
        try {
            $customer = $this->customerSessionsService->current();

            ViewRender::userRender("Customer/Dashboard", [
                "title" => "Dashboard",
                "customer" => $customer
            ]);
        } catch (ValidationException $e) {
            ViewRender::userRender("Customer/Dashboard", [
                "title" => "Dashboard"
            ]);
        }
    }
}