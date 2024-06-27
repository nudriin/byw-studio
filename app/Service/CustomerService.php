<?php
namespace Sheilla\NailArt\Service;

use Sheilla\NailArt\Config\Database;
use Sheilla\NailArt\Domain\Customer;
use Sheilla\NailArt\Exception\ValidationException;
use Sheilla\NailArt\Model\Customer\CustomerDisplayByUsernameRequest;
use Sheilla\NailArt\Model\Customer\CustomerDisplayByUsernameResponse;
use Sheilla\NailArt\Model\Customer\CustomerLoginRequest;
use Sheilla\NailArt\Model\Customer\CustomerLoginResponse;
use Sheilla\NailArt\Model\Customer\CustomerPasswordRequest;
use Sheilla\NailArt\Model\Customer\CustomerPasswordResponse;
use Sheilla\NailArt\Model\Customer\CustomerProfileRequest;
use Sheilla\NailArt\Model\Customer\CustomerProfileResponse;
use Sheilla\NailArt\Model\Customer\CustomerRegisterRequest;
use Sheilla\NailArt\Model\Customer\CustomerRegisterResponse;
use Sheilla\NailArt\Repository\CustomerRepository;

class CustomerService
{
    private CustomerRepository $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function customerRegister(CustomerRegisterRequest $request) : CustomerRegisterResponse
    {
        $this->validateRegister($request);
        try {
            Database::beginTransaction();
            $customer = $this->customerRepository->findByUsername($request->username);
            if($customer != null){
                throw new ValidationException("Username sudah terdaftar");
            }

            $customer = new Customer();
            $customer->id = "CST" . uniqid();
            $customer->username = $request->username;
            $customer->name = $request->name;
            $customer->phone = $request->phone;
            $customer->password = password_hash($request->password, PASSWORD_BCRYPT);

            $this->customerRepository->save($customer);
            Database::commitTransaction();
            $response = new CustomerRegisterResponse();
            $response->customer = $customer;

            return $response;
        } catch (ValidationException $e) {
            Database::rollbackTransaction();
            throw $e;
        }
    }

    public function validateRegister(CustomerRegisterRequest $request)
    {
        if($request->username == null || $request->name == null || $request->phone == null ||$request->password == null ||
            trim($request->username) == "" || trim($request->name) == "" || trim($request->phone) == "" || trim($request->password) == ""){
                throw new ValidationException("Username, nama dan password tidak boleh kosong");
            }
    }

    public function customerProfile(CustomerProfileRequest $request) : CustomerProfileResponse 
    {
        $this->validateProfile($request);
        try {
            Database::beginTransaction();
            $customer = $this->customerRepository->findById($request->id);
            if($customer == null){
                throw new ValidationException("Terjadi kesalahan");
            }
            $customer->name = $request->name;
            $customer->phone = $request->phone;
            $this->customerRepository->update($customer);
            Database::commitTransaction();
            $response = new CustomerProfileResponse();
            $response->customer = $customer;
            return $response;
        } catch (ValidationException $e) {
            Database::beginTransaction();
            throw $e;
        }
    }

    public function validateProfile(CustomerProfileRequest $request)
    {
        if($request->name == null || trim($request->name) == ""){
                throw new ValidationException("Nama tidak boleh kosong");
            }
    }

    public function login(CustomerLoginRequest $request) : CustomerLoginResponse
    {
        $this->validateLogin($request);
        $customer = $this->customerRepository->findByUsername($request->username);
        if($customer == null){
            throw new ValidationException("Username atau password anda salah");
        }

        if(!password_verify($request->password, $customer->password)){
            throw new ValidationException("Username atau password anda salah");
        }
        
        $response = new CustomerLoginResponse();
        $response->customer = $customer;
        return $response;
    }

    public function validateLogin(CustomerLoginRequest $request)
    {
        if( $request->username == null || $request->password == null ||
            trim($request->username) == "" || trim($request->password) == ""){
                throw new ValidationException("Username dan password tidak boleh kosong");
            }
    }

    public function displayByUsername(CustomerDisplayByUsernameRequest $request) : CustomerDisplayByUsernameResponse
    {
        try {
            $customer = $this->customerRepository->findByUsername($request->username);
            if($customer == null) {
                throw new ValidationException("Customer tidak ditemukan");
            }

            $response = new CustomerDisplayByUsernameResponse();
            $response->customer = $customer;

            return $response;
        } catch (ValidationException $e) {
            throw $e;
        }
    }

    public function customerPassword(CustomerPasswordRequest $request) : CustomerPasswordResponse
    {
        $this->validatePassword($request);
        try {
            $customer = $this->customerRepository->findById($request->id);
            Database::beginTransaction();
            if($customer == null){
                throw new ValidationException("Customer tidak ditemukan");
            }
            
            if(!password_verify($request->oldPassword, $customer->password)){
                throw new ValidationException("Password anda salah!");
            }
    
            $customer->password = password_hash($request->newPassword, PASSWORD_BCRYPT);
            $this->customerRepository->update($customer);
            Database::commitTransaction();
            
            $response = new CustomerPasswordResponse();
            $response->customer = $customer;
            return $response;
        } catch (ValidationException $e) {
            Database::rollbackTransaction();
            throw $e;
        }
    }
    
    public function validatePassword(CustomerPasswordRequest $request)
    {
        if( $request->newPassword == null|| $request->oldPassword == null || 
            trim($request->newPassword) == "" || trim($request->oldPassword) == ""){
                throw new ValidationException("Password tidak boleh kosong");
            }
    }
}