<?php
namespace Sheilla\NailArt\Controller;
require_once __DIR__ . "/../Helper/ImageHelper.php";
use DateInterval;
use DateTime;
use Sheilla\NailArt\App\ViewRender;
use Sheilla\NailArt\Config\Database;
use Sheilla\NailArt\Exception\ValidationException;
use Sheilla\NailArt\Model\Category\CategoryDisplayByNameRequest;
use Sheilla\NailArt\Model\Nailist\NailistDisplayByNameRequest;
use Sheilla\NailArt\Model\Orders\OrdersByCustomerRequest;
use Sheilla\NailArt\Model\Orders\OrdersSaveRequest;
use Sheilla\NailArt\Model\Orders\OrdersUpdateRequest;
use Sheilla\NailArt\Model\Schedule\ScheduleDisplayAllBydateRequest;
use Sheilla\NailArt\Model\Schedule\ScheduleDisplayByDateAndTimeRequest;
use Sheilla\NailArt\Model\Schedule\ScheduleDisplayByWeekRequest;
use Sheilla\NailArt\Model\Schedule\ScheduleUpdateStatusRequest;
use Sheilla\NailArt\Repository\AdminRepository;
use Sheilla\NailArt\Repository\AdminSessionsRepository;
use Sheilla\NailArt\Repository\CategoryRepository;
use Sheilla\NailArt\Repository\CustomerRepository;
use Sheilla\NailArt\Repository\CustomerSessionsRepository;
use Sheilla\NailArt\Repository\NailistRepository;
use Sheilla\NailArt\Repository\OrdersRepository;
use Sheilla\NailArt\Repository\ScheduleRepository;
use Sheilla\NailArt\Service\AdminSessionsService;
use Sheilla\NailArt\Service\CategoryService;
use Sheilla\NailArt\Service\CustomerSessionsService;
use Sheilla\NailArt\Service\NailistService;
use Sheilla\NailArt\Service\OrdersService;
use Sheilla\NailArt\Service\ScheduleService;

class OrdersController
{
    private OrdersService $ordersService;
    private CustomerSessionsService $customerSessionsService;
    private ScheduleService $scheduleService;
    private CategoryService $categoryService;
    private NailistService $nailistService;
    private OrdersRepository $ordersRepository;
    private AdminSessionsService $adminSessionsService;

    public function __construct()
    {
        $connection = Database::getConnect();
        $this->ordersRepository = new OrdersRepository($connection);
        $customerSessionsRepository = new CustomerSessionsRepository($connection);
        $customerRepository = new CustomerRepository($connection);
        $scheduleRepository = new ScheduleRepository($connection);
        $categoryRepository = new CategoryRepository($connection);
        $nailistRepository = new NailistRepository($connection);
        $adminSessionsRepository = new AdminSessionsRepository($connection);
        $adminRepository = new AdminRepository($connection);
        $this->ordersService = new OrdersService($this->ordersRepository);
        $this->customerSessionsService = new CustomerSessionsService($customerSessionsRepository, $customerRepository);
        $this->scheduleService = new ScheduleService($scheduleRepository);
        $this->categoryService = new CategoryService($categoryRepository);
        $this->nailistService = new NailistService($nailistRepository);
        $this->adminSessionsService = new AdminSessionsService($adminSessionsRepository, $adminRepository);
    }


    public function makeOrders()
    {
        try {
            // customer
            $customer = $this->customerSessionsService->current();
            // nailist
            $nailist = $this->nailistService->displayAllNailist();

            // schedule date
            $scheduleWeekRequest = new ScheduleDisplayByWeekRequest();
            $today = new DateTime();
            $next_week = new DateTime();
            $next_week->add(new DateInterval("P6D"));
            $scheduleWeekRequest->today = $today->format("Y-m-d");
            $scheduleWeekRequest->next_week = $next_week->format("Y-m-d");
            $scheduleDate = $this->scheduleService->displayByWeek($scheduleWeekRequest);

            // schedule time
            // $scheduleByDateRequest = new ScheduleDisplayAllBydateRequest();
            // $scheduleByDateRequest->date = $scheduleByDateRequest;
            // $scheduleTime = $this->scheduleService->displayAllByDate($scheduleByDateRequest);

            // catgeory
            $category = $this->categoryService->displayAllCategory();

            ViewRender::render("Orders/orders-add", [
                "title"=>"Booking",
                "customer"=>$customer,
                "nailist"=>$nailist->nailist,
                "date"=>$scheduleDate->schedule,
                "category"=>$category->category
            ]);

        } catch (ValidationException $e) {
            ViewRender::render("Orders/orders-add", [
                "title"=>"Booking",
                "error" => "Mohon maaf untuk sekarang belum bisa melakukan booking"
            ]);
        }
    }

    public function postMakeOrders()
    {
        try{
            $customer = $this->customerSessionsService->current();

            $request = new OrdersSaveRequest();
            $request->customer_id = $customer->id;
            $request->price = 50000;
            $request->date = htmlspecialchars($_POST['date']);
            $request->payment_confirm = htmlspecialchars($_FILES['payment_confirm']['name']);
            $request->nailist_name = htmlspecialchars($_POST['nailist']);
            $request->category_name = htmlspecialchars($_POST['category']);

            $nailistNameRequest = new NailistDisplayByNameRequest();
            $nailistNameRequest->name = $request->nailist_name;
            $nailist = $this->nailistService->displayNailistByName($nailistNameRequest);

            $request->nailist_id = $nailist->nailist->id;

            $categoryNameRequest = new CategoryDisplayByNameRequest();
            $categoryNameRequest->name = $request->category_name;
            $category = $this->categoryService->displayCategoryByName($categoryNameRequest);

            $request->category_id = $category->category->id;

            moveImage($_FILES['payment_confirm']['tmp_name'], $request->payment_confirm, "payment");

            $this->ordersService->saveOrders($request);
            ViewRender::redirect("/customer/booking");
        } catch(ValidationException $e){
            echo "<script>alert('{$e->getMessage()}')</script>";
            // ViewRender::redirect("/");
        }
    }

    public function ordersByCustomer()
    {
        try {
            $customer = $this->customerSessionsService->current();
            $request = new OrdersByCustomerRequest();
            $request->customer_id = $customer->id;

            $orders = $this->ordersService->ordersByCustomer($request);
            ViewRender::userRender("Booking/booking-user", [
                "title"=>"Booking",
                "orders"=>$orders->orders
            ]);
        } catch (ValidationException $e) {
            ViewRender::userRender("Booking/booking-user", [
                "title"=>"Booking",
                "error"=>$e->getMessage()
            ]);
        }
    }

    public function ordersConfirm(string $date, string $id)
    {
        try {
            $customer = $this->customerSessionsService->current();
            $request = new OrdersByCustomerRequest();
            $request->customer_id = $customer->id;
            $orders = $this->ordersService->ordersByCustomer($request);

            $scheduleRequest = new ScheduleDisplayAllBydateRequest();
            $scheduleRequest->date = $date;
            $schedule = $this->scheduleService->displayAllByDate($scheduleRequest);

            $ordersById = $this->ordersRepository->findById($id);

            ViewRender::userRender("Booking/booking-confirm", [
                "title"=>"Booking",
                "orders"=>$orders->orders,
                "orders_by_id" => $ordersById,
                "orders_id"=>$id,
                "schedule" => $schedule->schedule
            ]);
        } catch (ValidationException $e) {
            ViewRender::userRender("Booking/booking-confirm", [
                "title"=>"Booking",
                "error"=>$e->getMessage()
            ]);
        }
    }

    public function postOrdersConfirm(string $date, string $id)
    {
        try{
            $dates = $date;
            $request = new OrdersUpdateRequest();
            $request->id = $id;
            $request->book_time = htmlspecialchars($_POST['book_time']);
            $this->ordersService->updateOrders($request, "book_time");
            
            $requestSchedule = new ScheduleDisplayByDateAndTimeRequest();
            $requestSchedule->date = $date;
            $requestSchedule->book_time = $request->book_time;
            $schedule = $this->scheduleService->displayByDateAndTime($requestSchedule);
            
            $requestStatus = new ScheduleUpdateStatusRequest();
            $requestStatus->id = $schedule->schedule->id;
            $requestStatus->status = "Non-aktif";
            $this->scheduleService->updateStatusSchedule($requestStatus);

            ViewRender::redirect("/customer/booking");
        } catch(ValidationException $e){
            ViewRender::redirect("/customer/booking/$date");
        }
    }

    public function ordersHistory()
    {
        try {
            $customer = $this->customerSessionsService->current();
            $request = new OrdersByCustomerRequest();
            $request->customer_id = $customer->id;

            $orders = $this->ordersService->ordersByCustomer($request);
            ViewRender::userRender("Booking/booking-user-history", [
                "title"=>"Riwayat Booking",
                "orders"=>$orders->orders
            ]);
        } catch (ValidationException $e) {
            ViewRender::userRender("Booking/booking-user-history", [
                "title"=>"Riwayat Booking",
                "error"=>$e->getMessage()
            ]);
        }
    }

    public function ordersAdminReport()
    {
        try {
            $admin = $this->adminSessionsService->current();
            $orders = $this->ordersService->displayAllOrdersHistory();
            ViewRender::adminRender("Booking/booking-admin-all", [
                "title"=>"Laporan",
                "orders"=>$orders->orders,
                "admin"=>$admin
            ]);
        } catch (ValidationException $e) {
            ViewRender::adminRender("Booking/booking-admin-all", [
                "title"=>"Laporan",
                "error"=>$e->getMessage(),
                "admin"=>$admin
            ]);
            
        }
    }
    
    public function ordersAdmin()
    {
        try {
            $admin = $this->adminSessionsService->current();
            $orders = $this->ordersService->displayAllOrdersHistory();
            ViewRender::adminRender("Booking/booking-admin", [
                "title"=>"Booking",
                "orders"=>$orders->orders,
                "admin"=>$admin
            ]);
        } catch (ValidationException $e) {
            ViewRender::adminRender("Booking/booking-admin", [
                "title"=>"Booking",
                "error"=>$e->getMessage(),
                "admin"=>$admin
            ]);
            
        }
    }

    public function postOrdersAdmin()
    {
        try {
            $request = new OrdersUpdateRequest();
            $request->id = htmlspecialchars($_POST['orders_id']);
            $request->status = htmlspecialchars($_POST['status']);
            $this->ordersService->updateOrders($request, "status");
            ViewRender::redirect("/admin/booking");
        } catch (ValidationException $e) {
            ViewRender::redirect("/admin/booking");
            
        }
    }


}