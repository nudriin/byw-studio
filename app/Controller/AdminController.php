<?php

namespace Sheilla\NailArt\Controller;

use Sheilla\NailArt\App\ViewRender;
use Sheilla\NailArt\Config\Database;
use Sheilla\NailArt\Exception\ValidationException;
use Sheilla\NailArt\Model\Admin\AdminDeleteRequest;
use Sheilla\NailArt\Model\Admin\AdminDisplayByUsernameRequest;
use Sheilla\NailArt\Model\Admin\AdminLoginRequest;
use Sheilla\NailArt\Model\Admin\AdminPasswordRequest;
use Sheilla\NailArt\Model\Admin\AdminProfileRequest;
use Sheilla\NailArt\Model\Admin\AdminRegisterRequest;
use Sheilla\NailArt\Repository\AdminRepository;
use Sheilla\NailArt\Repository\AdminSessionsRepository;
use Sheilla\NailArt\Repository\NailistRepository;
use Sheilla\NailArt\Service\AdminService;
use Sheilla\NailArt\Service\AdminSessionsService;
use Sheilla\NailArt\Service\NailistService;

class AdminController
{
    private AdminService $adminService;
    private AdminSessionsService $adminSessionsService;
    private NailistService $nailistService;

    public function __construct()
    {
        $connection = Database::getConnect();
        $adminRepository = new AdminRepository($connection);
        $adminSessionsRepository = new AdminSessionsRepository($connection);
        $nailistRepository = new NailistRepository($connection);
        $this->adminService = new AdminService($adminRepository);
        $this->adminSessionsService = new AdminSessionsService($adminSessionsRepository, $adminRepository);
        $this->nailistService = new NailistService($nailistRepository);
    }

    public function register()
    {
        try {
            $admin = $this->adminSessionsService->current();
            ViewRender::adminRender("Admin/admin-register", [
                "title" => "Admin Register",
                "admin" => $admin
            ]);
        } catch (ValidationException $e) {
        }
    }

    public function postRegister()
    {
        $request = new AdminRegisterRequest();
        $request->username = htmlspecialchars($_POST['username']);
        $request->name = htmlspecialchars($_POST['name']);
        $request->password = htmlspecialchars($_POST['password']);
        try {
            $admin = $this->adminSessionsService->current();
            $this->adminService->adminRegister($request);

            ViewRender::redirect("/admin/booking");
        } catch (ValidationException $e) {
            ViewRender::adminRender("Admin/admin-register", [
                "title" => "Admin Register",
                "admin" => $admin,
                "error" => $e->getMessage()
            ]);
        }
    }

    public function login(): void
    {
        $model = [
            "title" => "Login"
        ];

        ViewRender::render("Admin/admin-login", $model);
    }

    public function postLogin()
    {
        $request = new AdminLoginRequest();
        $request->username = htmlspecialchars($_POST['username']);
        $request->password = htmlspecialchars($_POST['password']);
        $displayRequest = new AdminDisplayByUsernameRequest();
        $displayRequest->username = $request->username;
        try {
            $this->adminService->login($request);
            $admin = $this->adminService->displayByUsername($displayRequest);
            $this->adminSessionsService->createSession($admin->admin->id);
            ViewRender::redirect("/admin/booking");
        } catch (ValidationException $e) {
            $model = [
                "title" => "Login",
                "admin" => $admin,
                "error" => $e->getMessage()
            ];
            ViewRender::render("Admin/admin-login", $model);
        }
    }

    public function profileUpdate()
    {
        try {
            $admin = $this->adminSessionsService->current();
            ViewRender::adminRender("Admin/admin-profile", [
                "title" => "Profile",
                "admin" => $admin
            ]);
        } catch (ValidationException $e) {
            ViewRender::adminRender("Admin/admin-profile", [
                "title" => "Profile",
                "admin" => $admin,
                "error" => $e->getMessage()
            ]);
        }
    }

    public function postProfileUpdate()
    {
        try {
            $admin = $this->adminSessionsService->current();
            $request = new AdminProfileRequest();
            $request->id = $admin->id;
            $request->name = htmlspecialchars($_POST['name']);
            $this->adminService->adminProfile($request);
            ViewRender::redirect("/admin/profile");
        } catch (ValidationException $e) {
            ViewRender::adminRender("Admin/admin-profile", [
                "title" => "Profile",
                "admin" => $admin,
                "post_name" => $request->name,
                "error" => $e->getMessage()
            ]);
        }
    }

    public function passwordUpdate()
    {
        $admin = $this->adminSessionsService->current();
        ViewRender::adminRender("Admin/admin-password", [
            "title" => "Password",
            "admin" => $admin
        ]);
    }

    public function postPasswordUpdate()
    {
        try {
            $admin = $this->adminSessionsService->current();
            $request = new AdminPasswordRequest();
            $request->id = $admin->id;
            $request->oldPassword = htmlspecialchars($_POST['old_password']);
            $request->newPassword = htmlspecialchars($_POST['new_password']);

            $this->adminService->adminPassword($request);
            ViewRender::redirect("/admin/password");
        } catch (ValidationException $e) {
            $admin = $this->adminSessionsService->current();
            ViewRender::adminRender("Admin/admin-password", [
                "title" => "Password",
                "admin" => $admin,
                "error" => $e->getMessage()
            ]);
        }
    }

    public function logout()
    {
        try {
            $this->adminSessionsService->destroy();
            ViewRender::redirect("/admin/login");
        } catch (ValidationException $th) {

        }
    }

    public function displayAllNailist()
    {
        try {
            $admin = $this->adminSessionsService->current();
            $nailist = $this->nailistService->displayAllNailist();
            ViewRender::adminRender("Admin/nailist-display-all", [
                "title" => "Nailist",
                "nailist" => $nailist->nailist,
                "admin" => $admin
            ]);
        } catch (ValidationException $e) {
            ViewRender::adminRender("Admin/nailist-display-all", [
                "title" => "Nailist",
                "admin" => $admin,
                "error" => $e->getMessage()
            ]);
        }
    }

    public function adminAccount()
    {
        try {
            $admin = $this->adminSessionsService->current();
            $allAdmin = $this->adminService->displayAll();

            ViewRender::adminRender("Admin/admin-account", [
                "title" => "Akun Admin",
                "allAdmin" => $allAdmin->admin,
                "admin" => $admin
            ]);
        } catch (ValidationException $e) {
            ViewRender::adminRender("Admin/admin-account", [
                "title" => "Akun Admin",
                "admin" => $admin,
                "error" => $e->getMessage()
            ]);
        }
    }

    public function deleteAdminById(string $id)
    {
        try {
            $request = new AdminDeleteRequest();
            $request->id = $id;

            if($request->id != "ADM655f5c3699272"){
                $this->adminService->deleteAdmin($request);
            }
            ViewRender::redirect("/admin/admin");
        } catch (ValidationException $th) {
            ViewRender::redirect("/admin/admin");
        }
    }
}
