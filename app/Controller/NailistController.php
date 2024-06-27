<?php

namespace Sheilla\NailArt\Controller;

require_once __DIR__ . "/../Helper/ImageHelper.php";

use Sheilla\NailArt\App\ViewRender;
use Sheilla\NailArt\Config\Database;
use Sheilla\NailArt\Exception\ValidationException;
use Sheilla\NailArt\Model\Nailist\NailistDeleteRequest;
use Sheilla\NailArt\Model\Nailist\NailistDisplayRequest;
use Sheilla\NailArt\Model\Nailist\NailistSaveRequest;
use Sheilla\NailArt\Model\Nailist\NailistUpdateRequest;
use Sheilla\NailArt\Repository\AdminRepository;
use Sheilla\NailArt\Repository\AdminSessionsRepository;
use Sheilla\NailArt\Repository\CustomerRepository;
use Sheilla\NailArt\Repository\CustomerSessionsRepository;
use Sheilla\NailArt\Repository\NailistRepository;
use Sheilla\NailArt\Service\AdminSessionsService;
use Sheilla\NailArt\Service\CustomerSessionsService;
use Sheilla\NailArt\Service\NailistService;

class NailistController
{
    private NailistService $nailistService;
    private AdminSessionsService $adminSessionsService;
    private CustomerSessionsService $customerSessionsService;
    public function __construct()
    {
        $connection = Database::getConnect();
        $nailistRepository = new NailistRepository($connection);
        $adminSessionsRepository = new AdminSessionsRepository($connection);
        $adminRepository = new AdminRepository($connection);
        $customerSessionsRepository = new CustomerSessionsRepository($connection);
        $customerRepository = new CustomerRepository($connection);
        $this->nailistService = new NailistService($nailistRepository);
        $this->adminSessionsService = new AdminSessionsService($adminSessionsRepository, $adminRepository);
        $this->customerSessionsService = new CustomerSessionsService($customerSessionsRepository, $customerRepository);
    }

    public function addNailist()
    {
        try {
            $admin = $this->adminSessionsService->current();
            ViewRender::adminRender("Nailist/nailist-add", [
                "title" => "Tambah nailist",
                "admin"=>$admin
            ]);
        } catch (ValidationException $e) {
            
        }
    }

    public function postAddNailist()
    {
        try {
            $admin = $this->adminSessionsService->current();
            $request = new NailistSaveRequest();
            $request->name = htmlspecialchars($_POST['name']);
            $request->picture = $_FILES['picture']['name'];

            $this->nailistService->saveNailist($request);
            moveImage($_FILES['picture']['tmp_name'], $request->picture, "nailist");
            ViewRender::redirect("/admin/nailist");
        } catch (ValidationException $e) {
            ViewRender::adminRender("Nailist/nailist-add", [
                "title" => "Tambah nailist",
                "post_name" => $request->name,
                "admin" => $admin,
                "error" => $e->getMessage()
            ]);
        }
    }

    public function updateNailist(string $id)
    {
        try {
            $admin = $this->adminSessionsService->current();

            $request = new NailistDisplayRequest();
            $request->id = $id;
            $nailist = $this->nailistService->displayNailist($request);
            ViewRender::adminRender("Nailist/nailist-update", [
                "title" => "Update nailist",
                "nailist" => $nailist->nailist,
                "admin"=>$admin
            ]);
        } catch (ValidationException $e) {
            ViewRender::adminRender("Nailist/nailist-update", [
                "title" => "Update nailist",
                "admin"=>$admin
            ]);
        }
    }

    public function postUpdateNailist(string $id)
    {
        try {
            $admin = $this->adminSessionsService->current();
            $displayRequest = new NailistDisplayRequest();
            $displayRequest->id = $id;
            $nailist = $this->nailistService->displayNailist($displayRequest);
            $request = new NailistUpdateRequest();
            $request->id = $id;
            $request->name = htmlspecialchars($_POST['name']);
            $request->picture = htmlspecialchars($_FILES['picture']['name']);
            $this->nailistService->updateNailist($request);
            deleteImage($nailist->nailist->picture, "nailist");
            moveImage($_FILES['picture']['tmp_name'], $request->picture, "nailist");
            ViewRender::redirect("/admin/nailist");
        } catch (ValidationException $e) {
            ViewRender::adminRender("Nailist/nailist-update", [
                "title" => "Update nailist",
                "post_name" => $request->name,
                "nailist" => $nailist->nailist,
                "error" => $e->getMessage(),
                "admin"=>$admin
            ]);
        }
    }

    public function deleteNailist(string $id)
    {
        try {
            $request = new NailistDeleteRequest();
            $request->id = $id;

            $this->nailistService->deleteNailist($request);
            ViewRender::redirect("/admin/nailist");
        } catch (ValidationException $e) {
        }
    }

    public function displayAllNailist()
    {
        try {
            $customer = $this->customerSessionsService->current();
            $nailist = $this->nailistService->displayAllNailist();
            ViewRender::render("Nailist/nailist-display-all", [
                "title" => "Nailist",
                "nailist" => $nailist->nailist,
                "customer" => $customer
            ]);
        } catch (ValidationException $e) {
            ViewRender::render("Nailist/nailist-display-all", [
                "title" => "Nailist",
                "error" => $e->getMessage()
            ]);
        }
    }
}
