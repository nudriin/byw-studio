<?php

use Sheilla\NailArt\App\Router;
use Sheilla\NailArt\Controller\AdminHomeController;
use Sheilla\NailArt\Controller\HomeController;
use Sheilla\NailArt\Controller\AdminController;
use Sheilla\NailArt\Controller\CategoryController;
use Sheilla\NailArt\Controller\CustomerController;
use Sheilla\NailArt\Controller\CustomerHomeController;
use Sheilla\NailArt\Controller\NailistController;
use Sheilla\NailArt\Controller\OrdersController;
use Sheilla\NailArt\Controller\ScheduleController;
use Sheilla\NailArt\Middleware\AdminMustLoginMiddleware;
use Sheilla\NailArt\Middleware\AdminMustNotLoginMiddleware;
use Sheilla\NailArt\Middleware\CustomerMustLoginMiddleware;
use Sheilla\NailArt\Middleware\CustomerMustNotLoginMiddleware;
use Sheilla\NailArt\Middleware\OwnerMustLoginMiddleware;

require_once "../vendor/autoload.php";
require_once "../app/App/Router.php";


// Router::add("GET", "/", HomeController::class, "index");
// Router::add("GET", "/shop", HomeController::class, "shop");

Router::add("GET", "/" , HomeController::class, "index");
Router::add("GET", "/about-us" , HomeController::class, "aboutUs");
Router::add("GET", "/catalogue" , HomeController::class, "catalogue");
Router::add("GET", "/admin", OrdersController::class, "ordersAdmin", [AdminMustLoginMiddleware::class]);

// Router::add("GET", "/admin", AdminHomeController::class, "index", [AdminMustLoginMiddleware::class]);
Router::add("GET", "/admin/login", AdminController::class, "login", [AdminMustNotLoginMiddleware::class]);
Router::add("POST", "/admin/login", AdminController::class, "postLogin", [AdminMustNotLoginMiddleware::class]);
Router::add("GET", "/admin/register", AdminController::class, "register", [OwnerMustLoginMiddleware::class]);
Router::add("POST", "/admin/register", AdminController::class, "postRegister", [OwnerMustLoginMiddleware::class]);
Router::add("GET", "/admin/profile", AdminController::class, "profileUpdate", [AdminMustLoginMiddleware::class]);
Router::add("POST", "/admin/profile", AdminController::class, "postProfileUpdate", [AdminMustLoginMiddleware::class]);
Router::add("GET", "/admin/password", AdminController::class, "passwordUpdate", [AdminMustLoginMiddleware::class]);
Router::add("POST", "/admin/password", AdminController::class, "postPasswordUpdate", [AdminMustLoginMiddleware::class]);
Router::add("GET", "/admin/logout", AdminController::class, "logout", [AdminMustLoginMiddleware::class]);
Router::add("GET", "/admin/nailist", AdminController::class, "displayAllNailist", [AdminMustLoginMiddleware::class]);


Router::add("GET", "/admin/category", CategoryController::class, "displayCategory", [AdminMustLoginMiddleware::class]);
Router::add("GET", "/admin/add-category", CategoryController::class, "addCategory", [AdminMustLoginMiddleware::class]);
Router::add("POST", "/admin/add-category", CategoryController::class, "postAddCategory", [AdminMustLoginMiddleware::class]);
Router::add("GET", "/admin/update-category/([a-z0-9A-Z]+)", CategoryController::class, "updateCategory", [AdminMustLoginMiddleware::class]);
Router::add("POST", "/admin/update-category/([a-z0-9A-Z]+)", CategoryController::class, "postUpdateCategory", [AdminMustLoginMiddleware::class]);
Router::add("GET", "/admin/delete-category/([a-z0-9A-Z]+)", CategoryController::class, "deleteCategory", [AdminMustLoginMiddleware::class]);
Router::add("GET", "/admin/add-nailist", NailistController::class, "addNailist", [AdminMustLoginMiddleware::class]);
Router::add("POST", "/admin/add-nailist", NailistController::class, "postAddNailist", [AdminMustLoginMiddleware::class]);
Router::add("GET", "/admin/update-nailist/([a-z0-9A-Z]+)", NailistController::class, "updateNailist", [AdminMustLoginMiddleware::class]);
Router::add("POST", "/admin/update-nailist/([a-z0-9A-Z]+)", NailistController::class, "postUpdateNailist", [AdminMustLoginMiddleware::class]);
Router::add("GET", "/admin/delete-nailist/([a-z0-9A-Z]+)", NailistController::class, "deleteNailist", [AdminMustLoginMiddleware::class]);

Router::add("GET", "/customer", CustomerHomeController::class, "index", [CustomerMustLoginMiddleware::class]);
Router::add("GET", "/customer/login", CustomerController::class, "login", [CustomerMustNotLoginMiddleware::class]);
Router::add("POST", "/customer/login", CustomerController::class, "postLogin", [CustomerMustNotLoginMiddleware::class]);
Router::add("GET", "/customer/register", CustomerController::class, "register", [CustomerMustNotLoginMiddleware::class]);
Router::add("POST", "/customer/register", CustomerController::class, "postRegister", [CustomerMustNotLoginMiddleware::class]);
Router::add("GET", "/customer/profile", CustomerController::class, "profileUpdate", [CustomerMustLoginMiddleware::class]);
Router::add("POST", "/customer/profile", CustomerController::class, "postProfileUpdate", [CustomerMustLoginMiddleware::class]);
Router::add("GET", "/customer/password", CustomerController::class, "passwordUpdate", [CustomerMustLoginMiddleware::class]);
Router::add("POST", "/customer/password", CustomerController::class, "postPasswordUpdate", [CustomerMustLoginMiddleware::class]);
Router::add("GET", "/customer/logout", CustomerController::class, "logout", [CustomerMustLoginMiddleware::class]);

Router::add("GET", "/nailist", NailistController::class, "displayAllNailist");

Router::add("GET", "/admin/schedule", ScheduleController::class, "displayScheduleByWeek", [AdminMustLoginMiddleware::class]);
Router::add("GET", "/admin/schedule/([a-zA-Z0-9-]+)", ScheduleController::class, "displayScheduleByDate", [AdminMustLoginMiddleware::class]);
Router::add("POST", "/admin/schedule/([a-zA-Z0-9-]+)", ScheduleController::class, "updateStatusSchedule", [AdminMustLoginMiddleware::class]);
Router::add("GET", "/admin/add-schedule", ScheduleController::class, "addSchedule", [AdminMustLoginMiddleware::class]);
Router::add("POST", "/admin/add-schedule", ScheduleController::class, "postAddSchedule", [AdminMustLoginMiddleware::class]);

Router::add("GET", "/booking/orders", OrdersController::class, "makeOrders", [CustomerMustLoginMiddleware::class]);
Router::add("POST", "/booking/orders", OrdersController::class, "postMakeOrders", [CustomerMustLoginMiddleware::class]);
Router::add("GET", "/customer/booking", OrdersController::class, "ordersByCustomer", [CustomerMustLoginMiddleware::class]);
Router::add("GET", "/customer/booking-history", OrdersController::class, "ordersHistory", [CustomerMustLoginMiddleware::class]);
Router::add("GET", "/customer/booking/([a-z0-9A-Z-]+)/([a-z0-9A-Z-]+)", OrdersController::class, "ordersConfirm", [CustomerMustLoginMiddleware::class]);
Router::add("POST", "/customer/booking/([a-z0-9A-Z-]+)/([a-z0-9A-Z-]+)", OrdersController::class, "postOrdersConfirm", [CustomerMustLoginMiddleware::class]);
Router::add("GET", "/admin/report", OrdersController::class, "ordersAdminReport", [AdminMustLoginMiddleware::class]);
Router::add("GET", "/admin/booking", OrdersController::class, "ordersAdmin", [AdminMustLoginMiddleware::class]);
Router::add("POST", "/admin/booking", OrdersController::class, "postOrdersAdmin", [AdminMustLoginMiddleware::class]);

Router::add("GET", "/admin/admin", AdminController::class, "adminAccount", [OwnerMustLoginMiddleware::class]);
Router::add("GET", "/admin/delete/([a-zA-Z0-9]+)", AdminController::class, "deleteAdminById", [AdminMustLoginMiddleware::class]);

Router::run();


