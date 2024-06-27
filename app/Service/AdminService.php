<?php
namespace Sheilla\NailArt\Service;

use Sheilla\NailArt\Config\Database;
use Sheilla\NailArt\Domain\Admin;
use Sheilla\NailArt\Exception\ValidationException;
use Sheilla\NailArt\Model\Admin\AdminDeleteRequest;
use Sheilla\NailArt\Model\Admin\AdminDisplayAllResponse;
use Sheilla\NailArt\Model\Admin\AdminDisplayByUsernameRequest;
use Sheilla\NailArt\Model\Admin\AdminDisplayByUsernameResponse;
use Sheilla\NailArt\Model\Admin\AdminLoginRequest;
use Sheilla\NailArt\Model\Admin\AdminLoginResponse;
use Sheilla\NailArt\Model\Admin\AdminPasswordRequest;
use Sheilla\NailArt\Model\Admin\AdminPasswordResponse;
use Sheilla\NailArt\Model\Admin\AdminProfileRequest;
use Sheilla\NailArt\Model\Admin\AdminProfileResponse;
use Sheilla\NailArt\Model\Admin\AdminRegisterRequest;
use Sheilla\NailArt\Model\Admin\AdminRegisterResponse;
use Sheilla\NailArt\Repository\AdminRepository;

class AdminService
{
    private AdminRepository $adminRepository;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function adminRegister(AdminRegisterRequest $request) : AdminRegisterResponse
    {
        $this->validateRegister($request);
        try {
            Database::beginTransaction();
            $admin = $this->adminRepository->findByUsername($request->username);
            if($admin != null){
                throw new ValidationException("Username sudah terdaftar");
            }

            $admin = new Admin();
            $admin->id = "ADM" . uniqid();
            $admin->username = $request->username;
            $admin->name = $request->name;
            $admin->password = password_hash($request->password, PASSWORD_BCRYPT);

            $this->adminRepository->save($admin);
            Database::commitTransaction();
            $response = new AdminRegisterResponse();
            $response->admin = $admin;

            return $response;
        } catch (ValidationException $e) {
            Database::rollbackTransaction();
            throw $e;
        }
    }

    public function validateRegister(AdminRegisterRequest $request)
    {
        if($request->username == null || $request->name == null || $request->password == null ||
            trim($request->username) == "" || trim($request->name) == "" || trim($request->password) == ""){
                throw new ValidationException("Username, nama dan password tidak boleh kosong");
            }
    }

    public function adminProfile(AdminProfileRequest $request) : AdminProfileResponse 
    {
        $this->validateProfile($request);
        try {
            Database::beginTransaction();
            $admin = $this->adminRepository->findById($request->id);
            if($admin == null){
                throw new ValidationException("Terjadi kesalahan");
            }
            $admin->name = $request->name;
            $this->adminRepository->update($admin);
            Database::commitTransaction();
            $response = new AdminProfileResponse();
            $response->admin = $admin;
            return $response;
        } catch (ValidationException $e) {
            Database::beginTransaction();
            throw $e;
        }
    }

    public function validateProfile(AdminProfileRequest $request)
    {
        if($request->name == null || trim($request->name) == ""){
                throw new ValidationException("Nama tidak boleh kosong");
            }
    }

    public function login(AdminLoginRequest $request) : AdminLoginResponse
    {
        $this->validateLogin($request);
        $admin = $this->adminRepository->findByUsername($request->username);
        if($admin == null){
            throw new ValidationException("Username atau password anda salah");
        }

        if(!password_verify($request->password, $admin->password)){
            throw new ValidationException("Username atau password anda salah");
        }
        
        $response = new AdminLoginResponse();
        $response->admin = $admin;
        return $response;
    }

    public function validateLogin(AdminLoginRequest $request)
    {
        if( $request->username == null || $request->password == null ||
            trim($request->username) == "" || trim($request->password) == ""){
                throw new ValidationException("Username dan password tidak boleh kosong");
            }
    }

    public function displayByUsername(AdminDisplayByUsernameRequest $request) : AdminDisplayByUsernameResponse
    {
        try {
            //code...
            $admin = $this->adminRepository->findByUsername($request->username);
            if($admin == null) {
                throw new ValidationException("Admin tidak ditemukan");
            }

            $response = new AdminDisplayByUsernameResponse();
            $response->admin = $admin;

            return $response;
        } catch (ValidationException $e) {
            throw $e;
        }
    }

    public function adminPassword(AdminPasswordRequest $request) : AdminPasswordResponse
    {
        $this->validatePassword($request);
        try {
            $admin = $this->adminRepository->findById($request->id);
            Database::beginTransaction();
            if($admin == null){
                throw new ValidationException("Admin tidak ditemukan");
            }
            
            if(!password_verify($request->oldPassword, $admin->password)){
                throw new ValidationException("Password anda salah!");
            }
    
            $admin->password = password_hash($request->newPassword, PASSWORD_BCRYPT);
            $this->adminRepository->update($admin);
            Database::commitTransaction();
            
            $response = new AdminPasswordResponse();
            $response->admin = $admin;
            return $response;
        } catch (ValidationException $e) {
            Database::rollbackTransaction();
            throw $e;
        }
    }
    
    public function validatePassword(AdminPasswordRequest $request)
    {
        if( $request->newPassword == null|| $request->oldPassword == null || 
            trim($request->newPassword) == "" || trim($request->oldPassword) == ""){
                throw new ValidationException("Password tidak boleh kosong");
            }
    }

    public function displayAll() : AdminDisplayAllResponse
    {
        try {
            $admin = $this->adminRepository->findAll();
            if($admin == null){
                throw new ValidationException("Admin tidak ditemukan");
            }

            $response = new AdminDisplayAllResponse();
            $response->admin = $admin;
            return $response;
        } catch (ValidationException $e) {
            throw $e;
        }
    }

    public function deleteAdmin(AdminDeleteRequest $request)
    {
        try {
            $admin = $this->adminRepository->findById($request->id);
            if($admin == null){
                throw new ValidationException("Gagal menghapus admin");
            }

            $this->adminRepository->deleteById($request->id);
        } catch (ValidationException $e) {
            throw $e;
        }
    }
}