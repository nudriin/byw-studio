<?php
namespace Sheilla\NailArt\Service;

use Sheilla\NailArt\Config\Database;
use Sheilla\NailArt\Domain\Category;
use Sheilla\NailArt\Exception\ValidationException;
use Sheilla\NailArt\Model\Category\CategoryDeleteRequest;
use Sheilla\NailArt\Model\Category\CategoryDisplayAllResponse;
use Sheilla\NailArt\Model\Category\CategoryDisplayByNameRequest;
use Sheilla\NailArt\Model\Category\CategoryDisplayByNameResponse;
use Sheilla\NailArt\Model\Category\CategoryDisplayRequest;
use Sheilla\NailArt\Model\Category\CategoryDisplayResponse;
use Sheilla\NailArt\Model\Category\CategorySaveRequest;
use Sheilla\NailArt\Model\Category\CategorySaveResponse;
use Sheilla\NailArt\Model\Category\CategoryUpdateRequest;
use Sheilla\NailArt\Model\Category\CategoryUpdateResponse;
use Sheilla\NailArt\Repository\CategoryRepository;

class CategoryService
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function saveCategory(CategorySaveRequest $request) : CategorySaveResponse
    {
        $this->validateSaveCategory($request);
        try {
            Database::beginTransaction();
            $category = $this->categoryRepository->findByName($request->name);
            if($category != null){
                throw new ValidationException("Category ini sudah tersedia");
            }

            $category = new Category();
            $category->id = "CTG" . uniqid();
            $category->name = $request->name;

            $this->categoryRepository->save($category);
            Database::commitTransaction();
            $response = new CategorySaveResponse();
            $response->category = $category;
            return $response;
        } catch (ValidationException $e) {
            Database::rollbackTransaction();
            throw $e;
        }
    }

    public function validateSaveCategory(CategorySaveRequest $request)
    {
        if($request->name == null || trim($request->name) == ""){
            throw new ValidationException("Nama tidak boleh kosong");
        }
    }

    
    public function updateCategory(CategoryUpdateRequest $request) : CategoryUpdateResponse
    {
        $this->validateUpdateCategory($request);
        try {
            Database::beginTransaction();
            $category = $this->categoryRepository->findById($request->id);
            if($category == null){
                throw new ValidationException("Category tidak ditemukan");
            }
            $category->name = $request->name;

            $this->categoryRepository->update($category);
            Database::commitTransaction();
            $response = new CategoryUpdateResponse();
            $response->category = $category;
            return $response;
        } catch (ValidationException $e) {
            Database::rollbackTransaction();
            throw $e;
        }
    }

    public function validateUpdateCategory(CategoryUpdateRequest $request)
    {
        if($request->id == null || $request->name == null || trim($request->name) == "" || trim($request->id) == ""){
            throw new ValidationException("Nama tidak boleh kosong");
        }
    }

    public function deleteCategory(CategoryDeleteRequest $request)
    {
        $this->validateDeleteCategory($request);
        try {
            Database::beginTransaction();
            $category = $this->categoryRepository->findById($request->id);
            if($category == null){
                throw new ValidationException("Gagal menghapus category");
            }
            $this->categoryRepository->deleteById($category->id);
            Database::commitTransaction();
        } catch (ValidationException $e) {
            Database::rollbackTransaction();
            throw $e;
        }
    }

    public function validateDeleteCategory(CategoryDeleteRequest $request)
    {
        if($request->id == null || trim($request->id) == ""){
            throw new ValidationException("Gagal menghapus kategori, kategori tidak ditemukan");
        }
    }

    public function displayCategory(CategoryDisplayRequest $request) : CategoryDisplayResponse
    {
        try {
            $category = $this->categoryRepository->findById($request->id);
            if($category == null){
                throw new ValidationException("Kategori tidak ditemukan");
            }

            $response = new CategoryDisplayResponse();
            $response->category = $category;
            return $response;
        } catch (ValidationException $e) {
            throw $e;
        }
    }

    public function displayCategoryByName(CategoryDisplayByNameRequest $request) : CategoryDisplayByNameResponse
    {
        try {
            $category = $this->categoryRepository->findByName($request->name);
            if($category == null){
                throw new ValidationException("Kategori tidak ditemukan");
            }

            $response = new CategoryDisplayByNameResponse();
            $response->category = $category;
            return $response;
        } catch (ValidationException $e) {
            throw $e;
        }
    }

    public function displayAllCategory() : CategoryDisplayAllResponse
    {
        try {
            $category = $this->categoryRepository->findAll();
            if($category == null){
                throw new ValidationException("Kategori tidak ditemukan");
            }

            $response = new CategoryDisplayAllResponse();
            $response->category = $category;
            return $response;
        } catch (ValidationException $e) {
            throw $e;
        }
    }
}