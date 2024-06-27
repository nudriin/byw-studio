<?php
namespace Sheilla\NailArt\Service;

use Sheilla\NailArt\Config\Database;
use Sheilla\NailArt\Domain\Nailist;
use Sheilla\NailArt\Exception\ValidationException;
use Sheilla\NailArt\Model\Nailist\NailistDeleteRequest;
use Sheilla\NailArt\Model\Nailist\NailistDisplayAllResponse;
use Sheilla\NailArt\Model\Nailist\NailistDisplayByNameRequest;
use Sheilla\NailArt\Model\Nailist\NailistDisplayByNameResponse;
use Sheilla\NailArt\Model\Nailist\NailistDisplayRequest;
use Sheilla\NailArt\Model\Nailist\NailistDisplayResponse;
use Sheilla\NailArt\Model\Nailist\NailistSaveRequest;
use Sheilla\NailArt\Model\Nailist\NailistSaveResponse;
use Sheilla\NailArt\Model\Nailist\NailistUpdateRequest;
use Sheilla\NailArt\Model\Nailist\NailistUpdateResponse;
use Sheilla\NailArt\Repository\NailistRepository;

class NailistService
{
    private NailistRepository $nailistRepository;

    public function __construct(NailistRepository $nailistRepository)
    {
        $this->nailistRepository = $nailistRepository;
    }

    public function saveNailist(NailistSaveRequest $request) : NailistSaveResponse
    {
        $this->validateSaveNailist($request);
        try {
            Database::beginTransaction();
            $nailist = new Nailist();
            $nailist->id = "NLS" . uniqid();
            $nailist->name = $request->name;
            $nailist->picture = $request->picture;

            $this->nailistRepository->save($nailist);
            Database::commitTransaction();
            $response = new NailistSaveResponse();
            $response->nailist = $nailist;
            return $response;
        } catch (ValidationException $e) {
            Database::rollbackTransaction();
            throw $e;
        }
    }

    public function validateSaveNailist(NailistSaveRequest $request)
    {
        if($request->name == null || $request->picture == null || trim($request->name) == "" || trim($request->picture) == ""){
            throw new ValidationException("Nama dan poto tidak boleh kosong");
        }
    }

    public function updateNailist(NailistUpdateRequest $request) : NailistUpdateResponse
    {
        $this->validateUpdateNailist($request);
        try {
            Database::beginTransaction();
            $nailist = $this->nailistRepository->findById($request->id);
            if($nailist == null){
                throw new ValidationException("Gagal update nailist");
            }
            $nailist->name = $request->name;
            $nailist->picture = $request->picture;

            $this->nailistRepository->update($nailist);
            Database::commitTransaction();

            $response = new NailistUpdateResponse();
            $response->nailist = $nailist;
            return $response;
        } catch (ValidationException $e) {
            Database::rollbackTransaction();
            throw $e;
        }
    }

    public function validateUpdateNailist(NailistUpdateRequest $request)
    {
        if($request->id == null || $request->name == null || $request->picture == null || trim($request->name) == "" || trim($request->id) == "" || trim($request->picture) == ""){
            throw new ValidationException("Nama dan poto tidak boleh kosong");
        }
    }

    public function deleteNailist(NailistDeleteRequest $request)
    {
        $this->validateDeleteNailist($request);;
        try {
            Database::beginTransaction();
            $nailist = $this->nailistRepository->findById($request->id);
            if($nailist == null){
                throw new ValidationException("Gagal menghapus nailist");
            }
            $this->nailistRepository->deleteById($request->id);
            Database::commitTransaction();
        } catch (ValidationException $e) {
            Database::rollbackTransaction();
            throw $e;
        }
    }

    public function validateDeleteNailist(NailistDeleteRequest $request)
    {
        if($request->id == null || trim($request->id) == ""){
            throw new ValidationException("Gagal menghapus nailist, nailist tidak ditemukan");
        }
    }

    public function displayNailist(NailistDisplayRequest $request) : NailistDisplayResponse
    {
        try{
            $nailist = $this->nailistRepository->findById($request->id);
            if($nailist == null){
                throw new ValidationException("Nailist tidak ditemukan");
            }

            $response = new NailistDisplayResponse();
            $response->nailist = $nailist;
            return $response;
        } catch(ValidationException $e){
            throw $e;
        }
    }

    public function displayNailistByName(NailistDisplayByNameRequest $request) : NailistDisplayByNameResponse
    {
        try{
            $nailist = $this->nailistRepository->findByName($request->name);
            if($nailist == null){
                throw new ValidationException("Nailist tidak ditemukan");
            }

            $response = new NailistDisplayByNameResponse();
            $response->nailist = $nailist;
            return $response;
        } catch(ValidationException $e){
            throw $e;
        }
    }

    public function displayAllNailist() : NailistDisplayAllResponse
    {
        try {
            $nailist = $this->nailistRepository->findAll();
            if($nailist == null){
                throw new ValidationException("Nailist masih kosong");
            }

            $response = new NailistDisplayAllResponse();
            $response->nailist = $nailist;

            return $response;
        } catch (ValidationException $e) {
            throw $e;
        }
    }
}