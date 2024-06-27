<?php
namespace Sheilla\NailArt\Service;

use Sheilla\NailArt\Domain\Customer;
use Sheilla\NailArt\Domain\CustomerSessions;
use Sheilla\NailArt\Repository\CustomerRepository;
use Sheilla\NailArt\Repository\CustomerSessionsRepository;

class CustomerSessionsService
{
    private static string $SESSION_NAME = "X-CUSTOMER-SESSION";
    private CustomerSessionsRepository $customerSessionsRepository;
    private CustomerRepository $customerRepository;
    
    public function __construct(CustomerSessionsRepository $customerSessionsRepository, CustomerRepository $customerRepository) {
        $this->customerSessionsRepository = $customerSessionsRepository;
        $this->customerRepository = $customerRepository;
    }

    public function createSession(string $customer_id) : CustomerSessions
    {
        $customer = new CustomerSessions();
        $customer->id = uniqid();
        $customer->customer_id = $customer_id;

        $this->customerSessionsRepository->insertSessions($customer);

        setcookie(self::$SESSION_NAME, $customer->id, time() + (60 * 60 * 24 * 30), "/");

        return $customer;
    }

    public function current() : ?Customer
    {
        $sessionId = $_COOKIE[self::$SESSION_NAME] ?? '';

        $customer = $this->customerSessionsRepository->findById($sessionId);

        if($customer == null){
            return null;
        }

        return $this->customerRepository->findById($customer->customer_id);
    }

    public function destroy()
    {
        $sessionId = $_COOKIE[self::$SESSION_NAME] ?? '';

        $this->customerSessionsRepository->deleteById($sessionId);
        // set session to expired by (1 = masa lampau) 
        setcookie(self::$SESSION_NAME, "", 1, "/");
    }
}