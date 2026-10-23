<?php

use App\Http\Controllers\Admin as Admin;
use App\Http\Controllers\Agent as Agent;
use App\Http\Controllers\Auth as Auth;
use App\Http\Controllers\Public as PublicApi;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes (Public & Authenticated)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('/login', Auth\LoginController::class);
    Route::post('/register', Auth\RegisterController::class);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', Auth\LogoutController::class);
        Route::get('/me', Auth\MeController::class);
    });
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/flights', [PublicApi\FlightSearchController::class, 'index']);
Route::get('/flights/{id}', [PublicApi\FlightSearchController::class, 'show']);
Route::get('/airports/search', [PublicApi\AirportController::class, 'search']);
Route::get('/inventory/stats', [PublicApi\InventoryController::class, 'stats']);
Route::get('/site-settings', PublicApi\SiteSettingController::class);
Route::get('/featured-destinations', [Admin\DestinationController::class, 'featuredDestinations']);

/*
|--------------------------------------------------------------------------
| Agent Routes (Sanctum + Active Agent Role + Heartbeat)
|--------------------------------------------------------------------------
*/
Route::prefix('agent')->middleware(['auth:sanctum', 'role.agent', 'heartbeat'])->group(function () {
    Route::get('/dashboard', Agent\DashboardController::class);
    Route::get('/flights', [Agent\FlightController::class, 'index']);

    // Reservations
    Route::get('/reservations', [Agent\ReservationController::class, 'index']);
    Route::post('/reserve', [Agent\ReservationController::class, 'store']);
    Route::post('/reservations/{bookingCode}/cancel', [Agent\ReservationController::class, 'cancel']);

    // Tickets
    Route::get('/tickets', [Agent\TicketController::class, 'index']);
    Route::get('/tickets/{bookingCode}', [Agent\TicketController::class, 'show']);

    // Payments
    Route::get('/payment-methods', [Agent\PaymentController::class, 'methods']);
    Route::post('/payments', [Agent\PaymentController::class, 'submit']);

    // Messages
    Route::get('/messages', [Agent\MessageController::class, 'index']);
    Route::post('/messages', [Agent\MessageController::class, 'store']);
    Route::patch('/messages/{id}/read', [Agent\MessageController::class, 'markRead']);

    // Notifications
    Route::get('/notifications', [Agent\NotificationController::class, 'index']);
    Route::patch('/notifications/{id}/read', [Agent\NotificationController::class, 'markRead']);
    Route::post('/notifications/read-all', [Agent\NotificationController::class, 'markAllRead']);

    // Profile
    Route::get('/profile', [Agent\ProfileController::class, 'show']);
    Route::put('/profile', [Agent\ProfileController::class, 'update']);
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Sanctum + Admin Role + Heartbeat)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth:sanctum', 'role.admin', 'heartbeat'])->group(function () {
    Route::get('/dashboard', Admin\DashboardController::class);

    // Agent Management
    Route::get('/agents', [Admin\AgentController::class, 'index']);
    Route::get('/agents/{id}', [Admin\AgentController::class, 'show']);
    Route::post('/agents/{id}/approve', [Admin\AgentController::class, 'approve']);
    Route::post('/agents/{id}/reject', [Admin\AgentController::class, 'reject']);
    Route::post('/agents/{id}/suspend', [Admin\AgentController::class, 'suspend']);
    Route::post('/agents/{id}/reactivate', [Admin\AgentController::class, 'reactivate']);
    Route::post('/agents/{id}/wallet', [Admin\AgentController::class, 'adjustWallet']);

    // Inventory / Tickets CRUD
    Route::get('/tickets', [Admin\TicketController::class, 'index']);
    Route::post('/tickets', [Admin\TicketController::class, 'store']);
    Route::get('/tickets/{id}', [Admin\TicketController::class, 'show']);
    Route::put('/tickets/{id}', [Admin\TicketController::class, 'update']);
    Route::delete('/tickets/{id}', [Admin\TicketController::class, 'destroy']);

    // Bookings
    Route::get('/bookings', [Admin\BookingController::class, 'index']);
    Route::get('/bookings/{id}', [Admin\BookingController::class, 'show']);

    // Payments Verification
    Route::get('/payments', [Admin\PaymentController::class, 'index']);
    Route::post('/payments/{id}/verify', [Admin\PaymentController::class, 'verify']);

    // Destinations & Airports
    Route::get('/countries', [Admin\DestinationController::class, 'countries']);
    Route::post('/countries', [Admin\DestinationController::class, 'storeCountry']);
    Route::get('/airports', [Admin\DestinationController::class, 'airports']);
    Route::post('/airports', [Admin\DestinationController::class, 'storeAirport']);
    Route::get('/featured-destinations', [Admin\DestinationController::class, 'featuredDestinations']);
    Route::post('/featured-destinations', [Admin\DestinationController::class, 'storeFeaturedDestination']);
    Route::put('/featured-destinations/{id}', [Admin\DestinationController::class, 'updateFeaturedDestination']);
    Route::delete('/featured-destinations/{id}', [Admin\DestinationController::class, 'deleteFeaturedDestination']);
    Route::post('/destinations/upload-image', [Admin\DestinationController::class, 'uploadDestinationImage']);
    Route::post('/featured-destinations/reset', [Admin\DestinationController::class, 'resetFeaturedDestinations']);

    // Airlines
    Route::get('/airlines', [Admin\AirlineController::class, 'index']);
    Route::post('/airlines', [Admin\AirlineController::class, 'store']);

    // Agent Groups / Pricing tiers
    Route::get('/agent-groups', [Admin\AgentGroupController::class, 'index']);
    Route::post('/agent-groups', [Admin\AgentGroupController::class, 'store']);
    Route::put('/agent-groups/{id}', [Admin\AgentGroupController::class, 'update']);

    // Corporate Payment Accounts
    Route::get('/payment-methods', [Admin\PaymentMethodController::class, 'index']);
    Route::post('/payment-methods', [Admin\PaymentMethodController::class, 'store']);
    Route::put('/payment-methods/{id}', [Admin\PaymentMethodController::class, 'update']);

    // Online Agents Live Monitor
    Route::get('/online-agents', Admin\OnlineAgentController::class);

    // Messages
    Route::get('/messages', [Admin\MessageController::class, 'index']);
    Route::post('/messages', [Admin\MessageController::class, 'store']);

    // CSV Reports
    Route::get('/reports/export', [Admin\ReportController::class, 'export']);

    // Audit Logs
    Route::get('/activity-logs', [Admin\ActivityLogController::class, 'index']);

    // Platform Settings
    Route::get('/settings', [Admin\SettingController::class, 'index']);
    Route::put('/settings', [Admin\SettingController::class, 'update']);
    Route::post('/settings/hero-image', [Admin\SettingController::class, 'uploadHeroImage']);
    Route::delete('/settings/hero-image', [Admin\SettingController::class, 'deleteHeroImage']);
    Route::post('/settings/logo', [Admin\SettingController::class, 'uploadLogo']);
    Route::delete('/settings/logo', [Admin\SettingController::class, 'deleteLogo']);
    Route::post('/settings/about-image', [Admin\SettingController::class, 'uploadAboutImage']);
    Route::delete('/settings/about-image', [Admin\SettingController::class, 'deleteAboutImage']);
    Route::post('/settings/about-exp-image', [Admin\SettingController::class, 'uploadAboutExpImage']);
    Route::delete('/settings/about-exp-image', [Admin\SettingController::class, 'deleteAboutExpImage']);
});
