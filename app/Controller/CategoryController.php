<?php
namespace Sheilla\NailArt\Controller;

use Sheilla\NailArt\App\ViewRender;
use Sheilla\NailArt\Config\Database;
use Sheilla\NailArt\Exception\ValidationException;
use Sheilla\NailArt\Model\Category\CategoryDeleteRequest;
use Sheilla\NailArt\Model\Category\CategoryDisplayRequest;
use Sheilla\NailArt\Model\Category\CategorySaveRequest;
use Sheilla\NailArt\Model\Category\CategoryUpdateRequest;
use Sheilla\NailArt\Repository\AdminRepository;
use Sheilla\NailArt\Repository\AdminSessionsRepository;
use Sheilla\NailArt\Repository\CategoryRepository;
use Sheilla\NailArt\Service\AdminSessionsService;
use Sheilla\NailArt\Service\CategoryService;

class CategoryController
{
    private CategoryService $categoryService;
    private AdminSessionsService $adminSessionsService;

    public function __construct()
    {   
        $connection = Database::getConnect();
        $categoryRepository = new CategoryRepository($connection);
        $adminSessionsRepository = new AdminSessionsRepository($connection);
        $adminRepository = new AdminRepository($connection);
        $this->categoryService = new CategoryService($categoryRepository);
        $this->adminSessionsService = new AdminSessionsService($adminSessionsRepository, $adminRepository);
    }

    public function addCategory()
    {
        try {
            $admin = $this->adminSessionsService->current();
            ViewRender::adminRender("Category/category-add", [
                "title"=>"Tambah kategori",
                "admin" => $admin
            ]);
        } catch (ValidationException $e) {

        }
    }

    public function postAddCategory()
    {
        try {
            $admin = $this->adminSessionsService->current();
            $request = new CategorySaveRequest();
            $request->name = htmlspecialchars($_POST['name']);

            $this->categoryService->saveCategory($request);
            ViewRender::redirect("/admin/category");
        } catch (ValidationException $e) {
            ViewRender::adminRender("Category/category-add", [
                "title"=>"Tambah kategori",
                "post_name"=>$request->name,
                "error"=>$e->getMessage(),
                "admin"=>$admin
            ]);
        }
    }

    public function updateCategory(string $id)
    {

        try {
            $admin = $this->adminSessionsService->current();
            $request = new CategoryDisplayRequest();
            $request->id = $id;
            $category = $this->categoryService->displayCategory($request);
            ViewRender::adminRender("Category/category-update", [
                "title"=>"Update kategori",
                "category"=>$category->category,
                "admin"=>$admin
            ]);
        } catch (ValidationException $e) {
            ViewRender::adminRender("Category/category-update", [
                "title"=>"Update kategori",
                "error"=>$e->getMessage()
            ]);
            
        }
    }

    public function postUpdateCategory(string $id)
    {
        try {
            $admin = $this->adminSessionsService->current();

            $request = new CategoryUpdateRequest();
            $request->id = $id;
            $request->name = htmlspecialchars($_POST['name']);

            $this->categoryService->updateCategory($request);
            ViewRender::redirect("/admin/category");
        } catch (ValidationException $e) {
            ViewRender::adminRender("Category/category-update", [
                "title"=>"Update kategori",
                "post_name"=>$request->name,
                "admin"=>$admin,
                "error"=>$e->getMessage()
            ]);
        }
    }

    public function deleteCategory(string $id)
    {
        try {
            $request = new CategoryDeleteRequest();
            $request->id = $id;

            $this->categoryService->deleteCategory($request);
            ViewRender::redirect("/admin/category");

        } catch (ValidationException $e) {
            
        }
    }

    public function displayCategory()
    {
        try {
            $admin = $this->adminSessionsService->current();
            $category = $this->categoryService->displayAllCategory();
            
            ViewRender::adminRender("Category/category",[
                "title"=> "Category",
                "category" => $category->category,
                "admin"=>$admin
            ]);
        } catch (ValidationException $e) {
            ViewRender::adminRender("Category/category",[
                "title"=> "Category",
                "error"=>$e->getMessage(),
                "admin"=>$admin
            ]);
        }
    }
}