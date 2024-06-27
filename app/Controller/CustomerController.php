<?php

namespace Sheilla\NailArt\Controller;

use Sheilla\NailArt\App\ViewRender;
use Sheilla\NailArt\Config\Database;
use Sheilla\NailArt\Exception\ValidationException;
use Sheilla\NailArt\Model\Customer\CustomerDisplayByUsernameRequest;
use Sheilla\NailArt\Model\Customer\CustomerLoginRequest;
use Sheilla\NailArt\Model\Customer\CustomerPasswordRequest;
use Sheilla\NailArt\Model\Customer\CustomerProfileRequest;
use Sheilla\NailArt\Model\Customer\CustomerRegisterRequest;
use Sheilla\NailArt\Repository\CustomerRepository;
use Sheilla\NailArt\Repository\CustomerSessionsRepository;
use Sheilla\NailArt\Service\CustomerService;
use Sheilla\NailArt\Service\CustomerSessionsService;

class CustomerController
{
    private CustomerService $customerService;
    private CustomerSessionsService $customerSessionsService;

    public function __construct()
    {
        $connection = Database::getConnect();
        $customerRepository = new CustomerRepository($connection);
        $customerSessionsRepository = new CustomerSessionsRepository($connection);
        $this->customerService = new CustomerService($customerRepository);
        $this->customerSessionsService = new CustomerSessionsService($customerSessionsRepository, $customerRepository);
    }

    public function register()
    {
        try {
            ViewRender::render("Customer/customer-register", [
                "title" => "Customer Register"
            ]);
        } catch (ValidationException $e) {
        }
    }

    public function postRegister()
    {
        $request = new CustomerRegisterRequest();
        $request->username = htmlspecialchars($_POST['username']);
        $request->name = htmlspecialchars($_POST['name']);
        $request->phone = htmlspecialchars($_POST['phone']);
        $request->password = htmlspecialchars($_POST['password']);
        try {
            $this->customerService->customerRegister($request);

            ViewRender::redirect("/customer/login");
        } catch (ValidationException $e) {
            ViewRender::render("Customer/customer-register", [
                "title" => "Customer Register",
                "error" => $e->getMessage()
            ]);
        }
    }

    public function login()
    {
        ViewRender::render("Customer/customer-login", [
            "title" => "Login"
        ]);
    }

    public function postLogin()
    {
        $request = new CustomerLoginRequest();
        $request->username = htmlspecialchars($_POST['username']);
        $request->password = htmlspecialchars($_POST['password']);
        $displayRequest = new CustomerDisplayByUsernameRequest();
        $displayRequest->username = $request->username;
        try {
            $this->customerService->login($request);
            $customer = $this->customerService->displayByUsername($displayRequest);
            $this->customerSessionsService->createSession($customer->customer->id);
            ViewRender::redirect("/customer/booking");
        } catch (ValidationException $e) {
            ViewRender::render("Customer/customer-login", [
                "title" => "Login",
                "error" => $e->getMessage()
            ]);
        }
    }

    public function profileUpdate()
    {
        try {
            $customer = $this->customerSessionsService->current();
            ViewRender::userRender("Customer/customer-profile", [
                "title" => "Profile",
                "customer" => $customer
            ]);
        } catch (ValidationException $e) {
            ViewRender::userRender("Customer/customer-profile", [
                "title" => "Profile",
                "error" => $e->getMessage()
            ]);
        }
    }

    public function postProfileUpdate()
    {
        try {
            $customer = $this->customerSessionsService->current();
            $request = new CustomerProfileRequest();
            $request->id = $customer->id;
            $request->name = htmlspecialchars($_POST['name']);
            $request->phone = htmlspecialchars($_POST['phone']);
            $this->customerService->customerProfile($request);
            ViewRender::redirect("/customer/profile");
        } catch (ValidationException $e) {
            ViewRender::userRender("Customer/customer-profile", [
                "title" => "Profile",
                "customer" => $customer,
                "post_name" => $request->name,
                "post_phone"=>$request->phone,
                "error" => $e->getMessage()
            ]);
        }
    }

    public function passwordUpdate()
    {
        $customer = $this->customerSessionsService->current();
        ViewRender::userRender("Customer/customer-password", [
            "title" => "Password",
            "customer" => $customer
        ]);
    }

    public function postPasswordUpdate()
    {
        try {
            $customer = $this->customerSessionsService->current();
            $request = new CustomerPasswordRequest();
            $request->id = $customer->id;
            $request->oldPassword = htmlspecialchars($_POST['old_password']);
            $request->newPassword = htmlspecialchars($_POST['new_password']);

            $this->customerService->customerPassword($request);
            ViewRender::redirect("/customer/password");
        } catch (ValidationException $e) {
            $customer = $this->customerSessionsService->current();
            ViewRender::userRender("Customer/customer-password", [
                "title" => "Password",
                "customer" => $customer,
                "error" => $e->getMessage()
            ]);
        }
    }

    public function logout()
    {
        try {
            $this->customerSessionsService->destroy();
            ViewRender::redirect("/customer/login");
        } catch (ValidationException $th) {

        }
    }
}
