<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\AdminMiddleware;
use App\Middleware\CsrfMiddleware;

$router->get('/', 'App\Controllers\LandingController@index');
$router->get('/login', 'App\Controllers\Auth\LoginController@showLogin');
$router->post('/login', 'App\Controllers\Auth\LoginController@login', [CsrfMiddleware::class]);
$router->get('/logout', 'App\Controllers\Auth\LoginController@logout');

$router->get('/register', 'App\Controllers\Auth\RegisterController@showRegister');
$router->post('/register', 'App\Controllers\Auth\RegisterController@register', [CsrfMiddleware::class]);

$router->get('/forgot-password', 'App\Controllers\Auth\ForgotPasswordController@showForm');
$router->post('/forgot-password', 'App\Controllers\Auth\ForgotPasswordController@sendLink', [CsrfMiddleware::class]);
$router->get('/reset-password/{token}', 'App\Controllers\Auth\ForgotPasswordController@showReset');
$router->post('/reset-password', 'App\Controllers\Auth\ForgotPasswordController@reset', [CsrfMiddleware::class]);

$router->get('/verify-email/{token}', 'App\Controllers\Auth\EmailVerificationController@verify');
$router->get('/resend-verification', 'App\Controllers\Auth\EmailVerificationController@resend', [AuthMiddleware::class]);

$router->group('dashboard', [AuthMiddleware::class], function ($router) {
    $router->get('/', 'App\Controllers\User\DashboardController@index');

    $router->get('/profile', 'App\Controllers\User\ProfileController@index');
    $router->post('/profile', 'App\Controllers\User\ProfileController@update');
    $router->post('/profile/avatar', 'App\Controllers\User\ProfileController@updateAvatar');
    $router->post('/profile/password', 'App\Controllers\User\ProfileController@updatePassword');

    $router->get('/requests', 'App\Controllers\User\RequestController@index');
    $router->get('/requests/create', 'App\Controllers\User\RequestController@create');
    $router->post('/requests/store', 'App\Controllers\User\RequestController@store');
    $router->get('/requests/{id}', 'App\Controllers\User\RequestController@show');
    $router->post('/requests/{id}/upload-revision', 'App\Controllers\User\RequestController@uploadRevision', [CsrfMiddleware::class]);

    $router->post('/messages/send', 'App\Controllers\User\MessageController@send');

    $router->get('/credits', 'App\Controllers\User\CreditController@index');

    $router->get('/fault-codes', 'App\Controllers\User\FaultCodeController@index');
    $router->get('/bosch-ecu', 'App\Controllers\User\BoschEcuLookupController@index');
    $router->get('/ecu-list', 'App\Controllers\User\EcuListController@index');

    $router->get('/notifications', 'App\Controllers\User\NotificationController@index');
    $router->post('/notifications/read/{id}', 'App\Controllers\User\NotificationController@markAsRead');
    $router->post('/notifications/read-all', 'App\Controllers\User\NotificationController@markAllAsRead');
});

$router->group('api', [AuthMiddleware::class], function ($router) {
    $router->get('/vehicles/models/{brand_id}', 'App\Controllers\Api\VehicleApiController@models');
    $router->get('/vehicles/generations/{model_id}', 'App\Controllers\Api\VehicleApiController@generations');
    $router->get('/vehicles/engines/{generation_id}', 'App\Controllers\Api\VehicleApiController@engines');
    $router->get('/vehicles/ecus', 'App\Controllers\Api\VehicleApiController@ecus');
    $router->get('/vehicles/reading-methods', 'App\Controllers\Api\VehicleApiController@readingMethods');

    $router->get('/stages/pricing', 'App\Controllers\Api\StageApiController@pricing');

    $router->post('/files/upload', 'App\Controllers\Api\FileApiController@upload');
    $router->post('/files/delete', 'App\Controllers\Api\FileApiController@delete');

    $router->get('/notifications/unread-count', 'App\Controllers\User\NotificationController@unreadCount');
    $router->get('/notifications/recent', 'App\Controllers\User\NotificationController@recent');
});

$router->group('admin', [AuthMiddleware::class, AdminMiddleware::class], function ($router) {
    $router->get('/', 'App\Controllers\Admin\DashboardController@index');

    $router->get('/users', 'App\Controllers\Admin\UserController@index');
    $router->get('/users/pending-verification', 'App\Controllers\Admin\UserController@pendingVerification');
    $router->post('/users/{id}/approve-verification', 'App\Controllers\Admin\UserController@approveVerification', [CsrfMiddleware::class]);
    $router->get('/users/{id}', 'App\Controllers\Admin\UserController@show');
    $router->get('/users/{id}/edit', 'App\Controllers\Admin\UserController@edit');
    $router->post('/users/{id}/update', 'App\Controllers\Admin\UserController@update', [CsrfMiddleware::class]);
    $router->post('/users/{id}/toggle-status', 'App\Controllers\Admin\UserController@toggleStatus', [CsrfMiddleware::class]);
    $router->post('/users/{id}/delete', 'App\Controllers\Admin\UserController@delete', [CsrfMiddleware::class]);


    $router->get('/requests', 'App\Controllers\Admin\RequestController@index');
    $router->get('/requests/{id}', 'App\Controllers\Admin\RequestController@show');
    $router->post('/requests/{id}/status', 'App\Controllers\Admin\RequestController@updateStatus');
    $router->post('/requests/{id}/upload-file', 'App\Controllers\Admin\RequestController@uploadFile', [CsrfMiddleware::class]);
    $router->post('/requests/{id}/message', 'App\Controllers\Admin\RequestController@sendMessage');
    $router->post('/requests/{id}/add-service', 'App\Controllers\Admin\RequestController@addService');

    $router->get('/credits', 'App\Controllers\Admin\CreditController@index');
    $router->post('/credits/add', 'App\Controllers\Admin\CreditController@addCredit');
    $router->post('/credits/deduct', 'App\Controllers\Admin\CreditController@deductCredit');
    $router->post('/credits/debt', 'App\Controllers\Admin\CreditController@addDebtCredit');
    $router->post('/credits/refund/{id}', 'App\Controllers\Admin\CreditController@refund');

    $router->get('/vehicles/brands', 'App\Controllers\Admin\VehicleController@brands');
    $router->post('/vehicles/brands/store', 'App\Controllers\Admin\VehicleController@storeBrand');
    $router->post('/vehicles/brands/{id}/update', 'App\Controllers\Admin\VehicleController@updateBrand');
    $router->post('/vehicles/brands/{id}/delete', 'App\Controllers\Admin\VehicleController@deleteBrand');
    $router->get('/vehicles/models', 'App\Controllers\Admin\VehicleController@models');
    $router->post('/vehicles/models/store', 'App\Controllers\Admin\VehicleController@storeModel');
    $router->post('/vehicles/models/{id}/update', 'App\Controllers\Admin\VehicleController@updateModel');
    $router->post('/vehicles/models/{id}/delete', 'App\Controllers\Admin\VehicleController@deleteModel');
    $router->get('/vehicles/generations', 'App\Controllers\Admin\VehicleController@generations');
    $router->post('/vehicles/generations/store', 'App\Controllers\Admin\VehicleController@storeGeneration');
    $router->post('/vehicles/generations/{id}/update', 'App\Controllers\Admin\VehicleController@updateGeneration');
    $router->post('/vehicles/generations/{id}/delete', 'App\Controllers\Admin\VehicleController@deleteGeneration');
    $router->get('/vehicles/engines', 'App\Controllers\Admin\VehicleController@engines');
    $router->post('/vehicles/engines/store', 'App\Controllers\Admin\VehicleController@storeEngine');
    $router->post('/vehicles/engines/{id}/update', 'App\Controllers\Admin\VehicleController@updateEngine');
    $router->post('/vehicles/engines/{id}/delete', 'App\Controllers\Admin\VehicleController@deleteEngine');
    $router->get('/vehicles/ecus', 'App\Controllers\Admin\VehicleController@ecus');
    $router->post('/vehicles/ecus/store', 'App\Controllers\Admin\VehicleController@storeEcu');
    $router->post('/vehicles/ecus/{id}/update', 'App\Controllers\Admin\VehicleController@updateEcu');
    $router->post('/vehicles/ecus/{id}/delete', 'App\Controllers\Admin\VehicleController@deleteEcu');
    $router->get('/vehicles/reading-methods', 'App\Controllers\Admin\VehicleController@readingMethods');
    $router->post('/vehicles/reading-methods/store', 'App\Controllers\Admin\VehicleController@storeReadingMethod');
    $router->post('/vehicles/reading-methods/{id}/update', 'App\Controllers\Admin\VehicleController@updateReadingMethod');
    $router->post('/vehicles/reading-methods/{id}/delete', 'App\Controllers\Admin\VehicleController@deleteReadingMethod');

    $router->get('/fault-codes', 'App\Controllers\Admin\FaultCodeController@index');
    $router->get('/fault-codes/create', 'App\Controllers\Admin\FaultCodeController@create');
    $router->post('/fault-codes/store', 'App\Controllers\Admin\FaultCodeController@store');
    $router->get('/fault-codes/{id}/edit', 'App\Controllers\Admin\FaultCodeController@edit');
    $router->post('/fault-codes/{id}/update', 'App\Controllers\Admin\FaultCodeController@update');
    $router->post('/fault-codes/{id}/delete', 'App\Controllers\Admin\FaultCodeController@delete');

    $router->get('/bosch-ecu', 'App\Controllers\Admin\BoschEcuController@index');
    $router->get('/bosch-ecu/create', 'App\Controllers\Admin\BoschEcuController@create');
    $router->post('/bosch-ecu/store', 'App\Controllers\Admin\BoschEcuController@store');
    $router->get('/bosch-ecu/{id}/edit', 'App\Controllers\Admin\BoschEcuController@edit');
    $router->post('/bosch-ecu/{id}/update', 'App\Controllers\Admin\BoschEcuController@update');
    $router->post('/bosch-ecu/{id}/delete', 'App\Controllers\Admin\BoschEcuController@delete');

    $router->get('/df-fault-codes', 'App\Controllers\Admin\DfFaultCodeController@index');
    $router->get('/df-fault-codes/create', 'App\Controllers\Admin\DfFaultCodeController@create');
    $router->post('/df-fault-codes/store', 'App\Controllers\Admin\DfFaultCodeController@store');
    $router->get('/df-fault-codes/{id}/edit', 'App\Controllers\Admin\DfFaultCodeController@edit');
    $router->post('/df-fault-codes/{id}/update', 'App\Controllers\Admin\DfFaultCodeController@update');
    $router->post('/df-fault-codes/{id}/delete', 'App\Controllers\Admin\DfFaultCodeController@delete');

    $router->get('/pricing', 'App\Controllers\Admin\PricingController@index');
    $router->post('/pricing/{id}/update', 'App\Controllers\Admin\PricingController@update');
    $router->post('/pricing/stage/{id}/update', 'App\Controllers\Admin\PricingController@updateStagePricing');
    $router->post('/pricing/stage/{id}/add-service', 'App\Controllers\Admin\PricingController@addServiceToStage');
    $router->post('/pricing/stage/{id}/remove-service', 'App\Controllers\Admin\PricingController@removeServiceFromStage');

    $router->get('/stripe', 'App\Controllers\Admin\StripeController@index');
    $router->post('/stripe/create-link', 'App\Controllers\Admin\StripeController@createLink');
    $router->post('/stripe/{id}/approve', 'App\Controllers\Admin\StripeController@approve');
    $router->post('/stripe/{id}/cancel', 'App\Controllers\Admin\StripeController@cancel');

    $router->get('/settings', 'App\Controllers\Admin\SettingsController@index');
    $router->post('/settings', 'App\Controllers\Admin\SettingsController@update');
    $router->post('/settings/upload-logo', 'App\Controllers\Admin\SettingsController@uploadLogo');
    $router->post('/settings/delete-logo', 'App\Controllers\Admin\SettingsController@deleteLogo');

    $router->get('/logs', 'App\Controllers\Admin\LogController@index');

    $router->get('/notifications', 'App\Controllers\Admin\NotificationController@index');
    $router->get('/notifications/unread-count', 'App\Controllers\Admin\NotificationController@unreadCount');
    $router->get('/notifications/recent', 'App\Controllers\Admin\NotificationController@recent');
    $router->post('/notifications/read-all', 'App\Controllers\Admin\NotificationController@markAllRead');
    $router->post('/notifications/read/{id}', 'App\Controllers\Admin\NotificationController@markRead');

    $router->get('/landing', 'App\Controllers\Admin\LandingContentController@index');
    $router->post('/landing/save', 'App\Controllers\Admin\LandingContentController@save');
    $router->post('/landing/upload-image', 'App\Controllers\Admin\LandingContentController@uploadImage');
});

$router->get('/fault-codes', 'App\Controllers\User\FaultCodeController@index', [AuthMiddleware::class]);
$router->get('/fault-codes/{slug}', 'App\Controllers\Auth\LoginController@showLogin');
$router->get('/bosch-ecu', 'App\Controllers\Auth\LoginController@showLogin');

$router->get('/download/{id}', 'App\Controllers\Api\FileApiController@download', [AuthMiddleware::class]);
