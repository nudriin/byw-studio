<?php

namespace Sheilla\NailArt\Controller;

use Sheilla\NailArt\App\ViewRender;
use Sheilla\NailArt\Config\Database;
use Sheilla\NailArt\Exception\ValidationException;
use Sheilla\NailArt\Repository\AccountRepository;
use Sheilla\NailArt\Repository\CustomerRepository;
use Sheilla\NailArt\Repository\CustomerSessionsRepository;
use Sheilla\NailArt\Repository\NailistRepository;
use Sheilla\NailArt\Repository\ProductsRepository;
use Sheilla\NailArt\Repository\SessionsRepository;
use Sheilla\NailArt\Service\CustomerSessionsService;
use Sheilla\NailArt\Service\NailistService;
use Sheilla\NailArt\Service\ProductsService;
use Sheilla\NailArt\Service\SessionsService;

class HomeController
{
    private NailistService $nailistService;
    private CustomerSessionsService $customerSessionsService;
    public function __construct()
    {
        $connection = Database::getConnect();
        $nailistRepository = new NailistRepository($connection);
        $customerSessionsRepository = new CustomerSessionsRepository($connection);
        $customerRepository = new CustomerRepository($connection);
        $this->nailistService = new NailistService($nailistRepository);
        $this->customerSessionsService = new CustomerSessionsService($customerSessionsRepository, $customerRepository);
    }

    public function index()
    {
        try{
            $customer = $this->customerSessionsService->current();
            $nailist = $this->nailistService->displayAllNailist();
            ViewRender::render("Home/index",[
                "title"=>"Nail Art",
                "nailist"=>$nailist->nailist,
                "customer"=>$customer
            ]);
        } catch(ValidationException $e){
            ViewRender::render("Home/index",[
                "title"=>"Nail Art",
                "error"=>$e->getMessage(),
            ]);
            
        }
    }

    public function aboutUs()
    {
        try {
            $customer = $this->customerSessionsService->current();
            $nailist = $this->nailistService->displayAllNailist();
            ViewRender::render("Home/tentang-kami",[
                "title"=>"Tentang Kami",
                "nailist"=>$nailist->nailist,
                "customer"=>$customer
            ]);
        } catch (ValidationException $e) {
            ViewRender::render("Home/tentang-kami",[
                "title"=>"Tentang Kami",
                "error"=>$e->getMessage(),
            ]);
        }
    }

    public function catalogue()
    {
        try {
            $customer = $this->customerSessionsService->current();
            $nailist = $this->nailistService->displayAllNailist();
            ViewRender::render("Home/catalogue",[
                "title"=>"Katalog",
                "nailist"=>$nailist->nailist,
                "customer"=>$customer
            ]);
        } catch (ValidationException $e) {
            ViewRender::render("Home/catalogue",[
                "title"=>"Katalog",
                "error"=>$e->getMessage(),
            ]);
        }
    }
}
