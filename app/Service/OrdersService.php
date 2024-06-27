<?php

namespace Sheilla\NailArt\Service;

use Sheilla\NailArt\Config\Database;
use Sheilla\NailArt\Domain\Orders;
use Sheilla\NailArt\Exception\ValidationException;
use Sheilla\NailArt\Model\Orders\OrdersByCustomerRequest;
use Sheilla\NailArt\Model\Orders\OrdersByCustomerResponse;
use Sheilla\NailArt\Model\Orders\OrdersDisplayAllResponse;
use Sheilla\NailArt\Model\Orders\OrdersSaveRequest;
use Sheilla\NailArt\Model\Orders\OrdersSaveResponse;
use Sheilla\NailArt\Model\Orders\OrdersUpdateRequest;
use Sheilla\NailArt\Model\Orders\OrdersUpdateResponse;
use Sheilla\NailArt\Repository\OrdersRepository;

class OrdersService
{
    private OrdersRepository $ordersRepository;

    public function __construct(OrdersRepository $ordersRepository)
    {
        $this->ordersRepository = $ordersRepository;
    }

    public function saveOrders(OrdersSaveRequest $request): OrdersSaveResponse
    {
        try {
            Database::beginTransaction();
            $orders = new Orders();
            $orders->id = "ORD" . uniqid();
            $orders->customer_id = $request->customer_id;
            $orders->nailist_id = $request->nailist_id;
            $orders->category_id = $request->category_id;
            $orders->price = $request->price;
            $orders->status = "Menunggu";
            $orders->date = $request->date;
            $orders->payment_confirm = $request->payment_confirm;
            $orders->nailist_name = $request->nailist_name;
            $orders->category_name = $request->category_name;

            $this->ordersRepository->save($orders);
            Database::commitTransaction();
            $response = new OrdersSaveResponse();
            $response->orders = $orders;
            return $response;
        } catch (ValidationException $e) {
            Database::rollbackTransaction();
            throw $e;
        }
    }

    public function updateOrders(OrdersUpdateRequest $request, string $action): OrdersUpdateResponse
    {
        try {
            Database::beginTransaction();
            $orders = $this->ordersRepository->findById($request->id);
            if ($orders == null) {
                throw new ValidationException("Pesanan tidak ditemukan");
            }
            if ($action == "book_time") {
                $orders->book_time = $request->book_time;
            } else if ($action == "status") {
                $orders->status = $request->status;
            } else {
                $orders->book_time = $request->book_time;
                $orders->status = $request->status;
            }
            $this->ordersRepository->update($orders);
            Database::commitTransaction();
            $response = new OrdersUpdateResponse();
            $response->orders = $orders;
            return $response;
        } catch (ValidationException $e) {
            Database::rollbackTransaction();
            throw $e;
        }
    }

    public function deleteOrders()
    {
    }

    public function ordersByCustomer(OrdersByCustomerRequest $request): OrdersByCustomerResponse
    {
        try {
            $orders = $this->ordersRepository->findByCustomerId($request->customer_id);
            if ($orders == null) {
                throw new ValidationException("Belum ada pesanan");
            }

            $response = new OrdersByCustomerResponse();
            $response->orders = $orders;

            return $response;
        } catch (ValidationException $e) {
            throw $e;
        }
    }

    public function displayAllOrdersHistory()
    {
        try{
            $orders = $this->ordersRepository->findAllHistory();
            if($orders == null){
                throw new ValidationException("Belum ada order apapun");
            }
            $response = new OrdersDisplayAllResponse();
            $response->orders = $orders;
            return $response;
        } catch (ValidationException $e){
            throw $e;
        }
    }
}
