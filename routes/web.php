<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChairController;
use App\Http\Controllers\FacultyLoginController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\AdminController;
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


Route::post(
    '/admin/rooms/move',
    [AdminController::class, 'moveRoom']
)->name('admin.rooms.move');

// View room reservation requests
Route::get('/admin/reservations', [
    AdminController::class,
    'reservations'
])->name('admin.reservations');

// Approve reservation
Route::post('/admin/reservations/{reservation}/approve', [
    AdminController::class,
    'approveReservation'
])->name('admin.reservation.approve');

// Decline reservation
Route::post('/admin/reservations/{reservation}/decline', [
    AdminController::class,
    'declineReservation'
])->name('admin.reservation.decline');

    Route::get('/admin/view-schedule/{room}', [AdminController::class, 'viewSchedule'])
    ->name('admin.view.schedule');

    // sidebarroute
    Route::get('/admin/schedules', [AdminController::class, 'schedules'])
    ->name('admin.schedules');


    // dashboard
    Route::get('/admin/dashboard', [
        AdminController::class,
        'dashboard'
    ])->name('admin.dashboard');

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

    /*


|--------------------------------------------------------------------------
| DEPARTMENT MANAGEMENT
|--------------------------------------------------------------------------
*/

Route::get(
    '/admin/room-reassignment',
    function () {
        return view('admin.roomreassignment');
    }
)->name('admin.roomreassignment');

Route::get(
    '/admin/departments',
    [AdminController::class, 'departments']
)->name('admin.departments');


Route::post(
    '/admin/departments',
    [AdminController::class, 'storeDepartment']
)->name('admin.departments.store');


Route::put(
    '/admin/departments/{id}',
    [AdminController::class, 'updateDepartment']
)->name('admin.departments.update');


Route::delete(
    '/admin/departments/{id}',
    [AdminController::class, 'destroyDepartment']
)->name('admin.departments.delete');



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
    // student dashboard
    Route::get('/student/dashboard', function () {
    return view('student.dashboard');
    })->name('student.dashboard');

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

/*
|--------------------------------------------------------------------------
|  LOGIN
|--------------------------------------------------------------------------
*/

// Faculty redirect
Route::get('/faculty', function () {
    return redirect()->route('login');
});


// Main Login Page
Route::get('/login', [
    FacultyLoginController::class,
    'showLogin'
])->name('login');


// Process Login
Route::post('/login', [
    FacultyLoginController::class,
    'login'
])->name('login.submit');


// Faculty Logout
Route::post('/faculty/logout', [
    FacultyLoginController::class,
    'logout'
])->name('faculty.logout');

// back
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

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

    /*
    |--------------------------------------------------------------------------
    | CHAIR DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/chair', [
        ChairController::class,
        'dashboard'
    ])->name('chair.dashboard');

    Route::get('/chair/dashboard', [
        ChairController::class,
        'dashboard'
    ])->name('chair.dashboard.page');

 Route::put('/chair/room/{room}/permission', [
    ChairController::class,
    'updateRoomPermission'
])->name('chair.room.permission');

    /*
    |--------------------------------------------------------------------------
    | ROOM RESERVATIONS
    |--------------------------------------------------------------------------
    */

    Route::get('/chair/reservations', [
        ChairController::class,
        'reservations'
    ])->name('chair.reservations');


    Route::post('/chair/reservations/{reservation}/approve', [
        ChairController::class,
        'approveReservation'
    ])->name('chair.reservation.approve');


    Route::post('/chair/reservations/{reservation}/decline', [
        ChairController::class,
        'declineReservation'
    ])->name('chair.reservation.decline');


    /*
    |--------------------------------------------------------------------------
    | FACULTY SETUP
    | setFaculty.blade.php
    |--------------------------------------------------------------------------
    */

    Route::get('/chair/set-faculty', [
        ChairController::class,
        'setFaculty'
    ])->name('chair.setFaculty');


    /*
    |--------------------------------------------------------------------------
    | FACULTY SUBJECT MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::get('/chair/faculty/{faculty}/subjects', [
        ChairController::class,
        'facultySubjects'
    ])->name('chair.faculty.subjects');


    Route::post('/chair/faculty/subjects', [
        ChairController::class,
        'storeFacultySubject'
    ])->name('chair.faculty.subjects.store');


    Route::put('/chair/faculty/subjects/{id}', [
        ChairController::class,
        'updateFacultySubject'
    ])->name('chair.faculty.subjects.update');


    Route::delete('/chair/faculty/subjects/{id}', [
        ChairController::class,
        'deleteFacultySubject'
    ])->name('chair.faculty.subjects.delete');


    /*
    |--------------------------------------------------------------------------
    | SET SCHEDULE
    | setSchedule.blade.php
    |--------------------------------------------------------------------------
    */

    Route::get('/chair/setschedule', [
        ChairController::class,
        'setschedule'
    ])->name('chair.setschedule');


    /*
    |--------------------------------------------------------------------------
    | EXCEL SCHEDULE EDITOR
    | excel.blade.php
    |--------------------------------------------------------------------------
    */

    Route::get('/chair/excel/{room}', [
        ChairController::class,
        'index'
    ])->name('chair.excel');


    /*
    |--------------------------------------------------------------------------
    | SAVE / UPLOAD SCHEDULE
    |--------------------------------------------------------------------------
    */

    Route::post('/chair/save-schedule', [
        ChairController::class,
        'saveSchedule'
    ])->name('chair.save.schedule');


    /*
    |--------------------------------------------------------------------------
    | DELETE SCHEDULE
    |--------------------------------------------------------------------------
    */

    Route::delete('/chair/schedule/{room}', [
        ChairController::class,
        'deleteSchedule'
    ])->name('chair.schedule.delete');


    /*
    |--------------------------------------------------------------------------
    | SAVE SCHEDULE AS DRAFT
    |--------------------------------------------------------------------------
    */

    Route::post('/chair/save-draft', [
        ChairController::class,
        'saveDraft'
    ])->name('chair.save.draft');


    /*
    |--------------------------------------------------------------------------
    | VIEW DRAFTS
    |--------------------------------------------------------------------------
    */

    Route::get('/chair/drafts', [
        ChairController::class,
        'drafts'
    ])->name('chair.drafts');


    /*
    |--------------------------------------------------------------------------
    | EDIT DRAFT
    |--------------------------------------------------------------------------
    */

    Route::get('/chair/drafts/{room}/edit', [
        ChairController::class,
        'editDraft'
    ])->name('chair.drafts.edit');


    /*
    |--------------------------------------------------------------------------
    | MODIFY SCHEDULES
    |--------------------------------------------------------------------------
    */

    Route::get('/chair/modifyschedule', function () {

        return view('chair.modifyschedule');

    })->name('chair.modifyschedule');

   
/*
|--------------------------------------------------------------------------
| MODIFY SCHEDULE
|--------------------------------------------------------------------------
*/

Route::get('/chair/modifyschedule', [
    ChairController::class,
    'modifySchedule'
])->name('chair.modifyschedule');


Route::post('/chair/modifyschedule/store', [
    ChairController::class,
    'storeModifiedSchedule'
])->name('chair.modify.schedule.store');
});