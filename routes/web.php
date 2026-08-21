<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChairController;
use App\Http\Controllers\FacultyLoginController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RoomReassignmentController;
use App\Http\Controllers\RoomSwapRequestController;


/*
|--------------------------------------------------------------------------
| Welcome Page
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');


/*
|--------------------------------------------------------------------------
| Admin Page
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {


    Route::get('/admin/view-schedule/{room}', [AdminController::class, 'viewSchedule'])
    ->name('admin.view.schedule');

    // sidebarroute
    Route::get('/admin/schedules', [AdminController::class, 'schedules'])
    ->name('admin.schedules');

    Route::get('/admin/overriderequest', [AdminController::class, 'overrideRequest'])
    ->name('admin.overriderequest');

    // dashboard
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // manage buildings
    Route::get('/admin/buildings', [AdminController::class, 'buildings'])
        ->name('admin.buildings');

    Route::post('/admin/buildings', [AdminController::class, 'storeBuilding'])
        ->name('admin.buildings.store');

    Route::put('/admin/buildings/{id}', [AdminController::class, 'updateBuilding'])
        ->name('admin.buildings.update');

    Route::delete('/admin/buildings/{id}', [AdminController::class, 'destroyBuilding'])
        ->name('admin.buildings.delete');

    // manage rooms
    Route::post('/admin/rooms', [AdminController::class, 'storeRoom'])
        ->name('admin.rooms.store');

    Route::put('/admin/rooms/{id}', [AdminController::class, 'updateRoom'])
        ->name('admin.rooms.update');

    Route::delete('/admin/rooms/{id}', [AdminController::class, 'destroyRoom'])
        ->name('admin.rooms.delete');

    // Show Room Reassignment page
    Route::get('/admin/room-reassignment', [
        RoomReassignmentController::class,
        'index'
    ])->name('admin.roomreassignment');

    // Process Room Reassignment
    Route::post('/admin/room-reassignment/update', [
        RoomReassignmentController::class,
        'update'
    ])->name('roomreassignment.update');

    // manage users
    Route::get('/admin/manageusers', [
        ManageUserController::class,
        'index'
    ])->name('admin.manageusers');

    Route::get('/admin/manageusers/create', [
        ManageUserController::class,
        'create'
    ])->name('admin.manageusers.create');

    Route::post('/admin/manageusers/store', [
        ManageUserController::class,
        'store'
    ])->name('admin.manageusers.store');

    Route::get('/admin/manageusers/{id}/edit', [
        ManageUserController::class,
        'edit'
    ])->name('admin.manageusers.edit');

    Route::put('/admin/manageusers/{id}', [
        ManageUserController::class,
        'update'
    ])->name('admin.manageusers.update');

    Route::delete('/admin/manageusers/{id}', [
        ManageUserController::class,
        'destroy'
    ])->name('admin.manageusers.delete');

    Route::get('/admin/manageusers/{id}/reset-password', [
        ManageUserController::class,
        'showResetPassword'
    ])->name('admin.manageusers.reset');

    Route::post('/admin/manageusers/{id}/reset-password', [
        ManageUserController::class,
        'resetPassword'
    ])->name('admin.manageusers.reset.save');

});



/*
|--------------------------------------------------------------------------
| STUDENT
|--------------------------------------------------------------------------
*/

    Route::get('/student', function () {
        return view('student.choosedep');
    })->name('student.choosedep');

    Route::get('/student/cat', function () {
        return view('student.catdash');
    })->name('student.catdash');

    Route::get('/student/ced', function () {
        return view('student.ced');
    })->name('student.ced');

    Route::get('/student/ccjepa', function () {
        return view('student.ccjepadash');
    })->name('student.ccjepa');


/*
|--------------------------------------------------------------------------
| FACULTY
|--------------------------------------------------------------------------
*/

    // Reserve room
    Route::post('/faculty/reserve-room/{room}', [
        FacultyController::class,
        'reserveRoom'
    ])->name('faculty.reserve.room');

    // Faculty redirect
    Route::get('/faculty', function () {
        return redirect()->route('faculty.login');
    });

    // Faculty Login Page
    Route::get('/faculty/login', [
        FacultyLoginController::class,
        'showLogin'
    ])->name('faculty.login');

    // Login redirect
    Route::get('/login', function () {
        return redirect()->route('faculty.login');
    })->name('login');

    // Process Faculty Login
    Route::post('/faculty/login', [
        FacultyLoginController::class,
        'login'
    ])->name('faculty.login.submit');

    // Faculty Logout
    Route::post('/faculty/logout', [
        FacultyLoginController::class,
        'logout'
    ])->name('faculty.logout');



/*
|--------------------------------------------------------------------------
| AUTHENTICATED FACULTY ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // dashboard
    Route::get('/faculty/dashboard', [
        FacultyLoginController::class,
        'dashboard'
    ])->name('faculty.dashboard');

    // schedules
    Route::get('/faculty/schedules', [
        ChairController::class,
        'facultySchedules'
    ])->name('faculty.schedules');

    // view vacant rooms
    Route::get('/faculty/view-schedule/{room}', [
        ChairController::class,
        'viewSchedule'
    ])->name('faculty.view.schedule');

    // vacantrooms
    Route::get('/faculty/vacant-rooms', [
        ChairController::class,
        'vacantRooms'
    ])->name('faculty.vacant');

    // mybookings
    Route::get('/faculty/my-bookings', function () {
        return view('faculty.my-bookings');
    })->name('faculty.bookings');

    // roomSwap page
    Route::get('/faculty/room-swap', [
        RoomSwapRequestController::class,
        'create'
    ])->name('faculty.room-swap');

    // Send Room Swap Request
    Route::post('/faculty/room-swap', [
        RoomSwapRequestController::class,
        'store'
    ])->name('faculty.room.swap.store');

    // View received swap requests
    Route::get('/faculty/room-swap-requests', [
        RoomSwapRequestController::class,
        'receivedRequests'
    ])->name('faculty.room.swap.requests');

    // Approve request
    Route::post('/faculty/room-swap/{id}/approve', [
        RoomSwapRequestController::class,
        'approve'
    ])->name('faculty.room.swap.approve');

    //  Decline request
    Route::post('/faculty/room-swap/{id}/decline', [
        RoomSwapRequestController::class,
        'decline'
    ])->name('faculty.room.swap.decline');

});



/*
|--------------------------------------------------------------------------
| CHAIR
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Chair Dashboard
    Route::get('/chair', [
        ChairController::class,
        'dashboard'
    ])->name('chair.dashboard');

    Route::get('/chair/dashboard', [
        ChairController::class,
        'dashboard'
    ]);

    // Room Reservations
    Route::get('/chair/reservations', [
        ChairController::class,
        'reservations'
    ])->name('chair.reservations');

    // Approve Reservation
    Route::post('/chair/reservations/{reservation}/approve', [
        ChairController::class,
        'approveReservation'
    ])->name('chair.reservation.approve');

    // Decline Reservation
    Route::post('/chair/reservations/{reservation}/decline', [
        ChairController::class,
        'declineReservation'
    ])->name('chair.reservation.decline');

    // Set Schedule
    Route::get('/chair/setschedule', [
        ChairController::class,
        'setschedule'
    ])->name('chair.setschedule');

    // Schedule Editor
    Route::get('/chair/excel/{room}', [
        ChairController::class,
        'index'
    ])->name('chair.excel');

    // Save Schedule
    Route::post('/chair/save-schedule', [
        ChairController::class,
        'saveSchedule'
    ])->name('chair.save.schedule');

    // Delete Schedule
    Route::delete('/chair/schedule/{room}', [
        ChairController::class,
        'deleteSchedule'
    ])->name('chair.schedule.delete');

    // Drafts
    Route::get('/chair/drafts', function () {
        return view('chair.drafts');
    })->name('chair.drafts');

    // Modify Schedules
    Route::get('/chair/modifyschedule', function () {
        return view('chair.modifyschedule');
    })->name('chair.modifyschedule');

});