<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\ChairController;
use App\Http\Controllers\FacultyLoginController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RoomSwapRequestController;
use App\Http\Controllers\RoomReassignmentController;
use App\Http\Controllers\UserNotificationController;
use App\Http\Controllers\DepartmentLogoController;
use App\Http\Controllers\ProfilePhotoController;

use App\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| Welcome Page
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/departments/{department}/logo', DepartmentLogoController::class)
    ->whereNumber('department')
    ->name('departments.logo');

Route::get('/users/{user}/profile-photo', ProfilePhotoController::class)
    ->whereNumber('user')
    ->name('profile.photo.show');


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

    // Activate or deactivate a user account.

    Route::patch('/admin/manageusers/{id}/status', [

        ManageUserController::class,

        'toggleStatus'

    ])->name('admin.manageusers.status');

    // Restore an archived user account.

    Route::patch('/admin/manageusers/{id}/restore', [

        ManageUserController::class,

        'restore'

    ])->name('admin.manageusers.restore');


 /*
|--------------------------------------------------------------------------
| ADMIN ROOM REASSIGNMENT
|--------------------------------------------------------------------------
*/


Route::get(
    '/admin/room-reassignment/departments/{department}/buildings',
    [RoomReassignmentController::class, 'buildings']
)->name('admin.roomreassignment.buildings');

Route::get(
    '/admin/room-reassignment/buildings/{building}/rooms',
    [RoomReassignmentController::class, 'rooms']
)->name('admin.roomreassignment.rooms');

Route::get(
    '/admin/room-reassignment/conflicts',
    [RoomReassignmentController::class, 'conflicts']
)->name('admin.roomreassignment.conflicts');

Route::get(
    '/admin/room-reassignment',
    [RoomReassignmentController::class, 'index']
)->name('admin.roomreassignment');

Route::post(
    '/admin/room-reassignment',
    [RoomReassignmentController::class, 'store']
)->name('admin.roomreassignment.store');

Route::delete(
    '/admin/room-reassignment/{scheduleOverride}',
    [RoomReassignmentController::class, 'cancel']
)->name('admin.roomreassignment.cancel');


/*
|--------------------------------------------------------------------------
| ROOM REASSIGNMENT
|--------------------------------------------------------------------------
*/

Route::get(
    '/room-reassignment',
    [RoomReassignmentController::class, 'index']
)->name('roomreassignment');

Route::post(
    '/room-reassignment',
    [RoomReassignmentController::class, 'store']
)->name('roomreassignment.store');

Route::delete(
    '/room-reassignment/{scheduleOverride}',
    [RoomReassignmentController::class, 'cancel']
)->name('roomreassignment.destroy');
});



/*
|--------------------------------------------------------------------------
| STUDENT
|--------------------------------------------------------------------------
*/


Route::get(
    '/student/schedule',
    [StudentController::class, 'schedule']
)->name('student.schedule');


Route::get(
    '/student/schedule/programs',
    [StudentController::class, 'getPrograms']
)->name('student.schedule.programs');


Route::get(
    '/student/schedule/years',
    [StudentController::class, 'getYearLevels']
)->name('student.schedule.years');


Route::get(
    '/student/schedule/data',
    [StudentController::class, 'getSchedules']
)->name('student.schedule.data');

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
// Uses the same shared Blade file as the Welcome Page.
Route::get('/login', function () {
    return view('welcome');
})->name('login');


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

// Welcome route is defined once at the top of this file.

/*
|--------------------------------------------------------------------------
| AUTHENTICATED FACULTY ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/faculty/dashboard', [
    FacultyController::class,
    'dashboard'
])->name('faculty.dashboard');

    // Schedules
    Route::get('/faculty/schedules', [
        FacultyController::class,
        'schedule'
    ])->name('faculty.schedules');


    // Vacant Rooms
   Route::get('/faculty/vacant-rooms', [
    FacultyController::class,
    'vacantRooms'
])->name('faculty.vacant');

Route::get('/faculty/my-reservations', [
    FacultyController::class,
    'myReservations'
])->name('faculty.bookings');

Route::patch('/faculty/my-reservations/{reservation}/cancel', [
    FacultyController::class,
    'cancelReservation'
])->whereNumber('reservation')->name('faculty.bookings.cancel');

Route::delete('/faculty/my-reservations/{reservation}', [
    FacultyController::class,
    'deleteReservation'
])->whereNumber('reservation')->name('faculty.bookings.destroy');


    // =====================================================
    // ROOM SWAP
    // =====================================================

    // Room Swap Page
    Route::get('/faculty/room-swap', [
        RoomSwapRequestController::class,
        'create'
    ])->name('faculty.room-swap');

    // Send Room Swap Request
    Route::post('/faculty/room-swap', [
        RoomSwapRequestController::class,
        'store'
    ])->name('faculty.room.swap.store');

    // Optional separate page for received requests
    Route::get('/faculty/room-swap-requests', [
        RoomSwapRequestController::class,
        'receivedRequests'
    ])->name('faculty.room.swap.requests');

    // Accept Room Swap Request from Faculty Dashboard
    Route::post('/faculty/room-swap/{id}/approve', [
        RoomSwapRequestController::class,
        'approve'
    ])->name('faculty.room-swap.approve');

    // Decline Room Swap Request from Faculty Dashboard
    Route::post('/faculty/room-swap/{id}/decline', [
        RoomSwapRequestController::class,
        'decline'
    ])->name('faculty.room-swap.decline');

    // Cancel Room Swap Request
Route::post('/faculty/room-swap/{id}/cancel', [
    RoomSwapRequestController::class,
    'cancel'
])->name('faculty.room-swap.cancel');


    // =====================================================
    // FACULTY SCHEDULE
    // =====================================================

    Route::get('/faculty/schedule', [
        FacultyController::class,
        'schedule'
    ])->name('faculty.schedule');

    Route::get(
    '/faculty/my-schedules',
    [FacultyController::class, 'mySchedules']
)->name('faculty.myschedules');

  

    // View Specific Room Schedule
    Route::get('/faculty/schedule/room/{room}', [
        FacultyController::class,
        'viewSchedule'
    ])->name('faculty.view.schedule');

    Route::get('/notifications/latest', [UserNotificationController::class, 'latest'])
    ->name('notifications.latest');

Route::get('/notifications/{notificationId}', [UserNotificationController::class, 'open'])
    ->name('notifications.open');
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

    Route::get('/chair/requests', [
        ChairController::class,
        'requests'
    ])->name('chair.requests');

 Route::put('/chair/room/{room}/permission', [
    ChairController::class,
    'updateRoomPermission'
])->name('chair.room.permission');


 /*
    |--------------------------------------------------------------------------
    | merge classes
    |--------------------------------------------------------------------------
    */


Route::get(
    '/chair/search-merge-classes',
    [ChairController::class, 'searchMergeClasses']
)->name('chair.search.merge.classes');

Route::get(
    '/chair/merge-departments',
    [ChairController::class, 'mergeDepartments']
)->name('chair.merge.departments');

Route::get(
    '/chair/merge-room/{roomId}',
    [ChairController::class, 'mergeRoomSchedule']
)->name('chair.merge.room');

Route::get(
    '/chair/merge-programs',
    [ChairController::class, 'mergePrograms']
)->name('chair.merge.programs');

Route::get(
    '/chair/merge-subjects',
    [ChairController::class, 'mergeSubjects']
)->name('chair.merge.subjects');

Route::post(
    '/chair/merge-request',
    [ChairController::class, 'createCourseMergeRequest']
)->name('chair.merge.request');

Route::post(
    '/chair/merge-local-course',
    [ChairController::class, 'mergeLocalCourse']
)->name('chair.merge.local');


Route::post(
    '/chair/merge-request/{merge}/approve',
    [ChairController::class, 'approveCourseMergeRequest']
)->name('chair.merge.approve');

Route::post(
    '/chair/merge-request/{merge}/reject',
    [ChairController::class, 'rejectCourseMergeRequest']
)->name('chair.merge.reject');

Route::delete(
    '/chair/merge-request/{merge}/cancel',
    [ChairController::class, 'cancelCourseMergeRequest']
)->name('chair.merge.cancel');

Route::delete(
    '/chair/merge-request/{merge}/remove',
    [ChairController::class, 'removeCourseMergeRequest']
)->name('chair.merge.remove');


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

    
Route::post('/chair/import-faculty', [
    ChairController::class,
    'importFaculty'
])->name('chair.faculty.import');

Route::delete('/chair/import-faculty/{facultyId}', [
    ChairController::class,
    'removeImportedFaculty'
])->name('chair.faculty.import.remove');



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

    Route::get('/chair/subjects/lookup', [
        ChairController::class,
        'lookupCatalogSubjects'
    ])->name('chair.subjects.lookup');

    Route::get('/chair/manage-subjects', [
        ChairController::class,
        'manageSubjects'
    ])->name('chair.managesubjects');

    Route::get('/chair/manage-subjects/programs/{program}', [
        ChairController::class,
        'manageSubjects'
    ])->whereNumber('program')->name('chair.managesubjects.program');

    Route::get('/chair/manage-subjects/programs/{program}/memoranda/{memorandum}', [
        ChairController::class,
        'manageSubjects'
    ])->whereNumber(['program', 'memorandum'])->name('chair.managesubjects.memorandum');

    Route::post('/chair/manage-subjects/programs', [
        ChairController::class,
        'storeProgram'
    ])->name('chair.managesubjects.program.store');

    Route::post('/chair/manage-subjects/programs/{program}/memoranda', [
        ChairController::class,
        'storeProgramMemorandum'
    ])->whereNumber('program')->name('chair.managesubjects.memorandum.store');

    Route::put('/chair/manage-subjects/programs/{program}/memoranda/{memorandum}', [
        ChairController::class,
        'updateProgramMemorandum'
    ])->whereNumber(['program', 'memorandum'])->name('chair.managesubjects.memorandum.update');

    Route::delete('/chair/manage-subjects/programs/{program}/memoranda/{memorandum}', [
        ChairController::class,
        'deleteProgramMemorandum'
    ])->whereNumber(['program', 'memorandum'])->name('chair.managesubjects.memorandum.delete');

    Route::put('/chair/manage-subjects/programs/{program}', [
        ChairController::class,
        'updateProgram'
    ])->name('chair.managesubjects.program.update');

    Route::delete('/chair/manage-subjects/programs/{program}', [
        ChairController::class,
        'deleteProgram'
    ])->name('chair.managesubjects.program.delete');

    Route::put('/chair/manage-subjects/programs/{program}/memoranda/{memorandum}/years/{year}/subjects', [
        ChairController::class,
        'saveCurriculumSheet'
    ])->whereNumber(['program', 'memorandum'])->where('year', '[^/]+')->name('chair.managesubjects.sheet.save');

    Route::post('/chair/manage-subjects/programs/{program}/years/{year}/subjects', [
        ChairController::class,
        'storeCatalogSubject'
    ])->where('year', '[^/]+')->name('chair.managesubjects.subject.store');

    Route::put('/chair/manage-subjects/programs/{program}/years/{year}/subjects/{subject}', [
        ChairController::class,
        'updateCatalogSubject'
    ])->where('year', '[^/]+')->name('chair.managesubjects.subject.update');

    Route::delete('/chair/manage-subjects/programs/{program}/years/{year}/subjects/{subject}', [
        ChairController::class,
        'deleteCatalogSubject'
    ])->where('year', '[^/]+')->name('chair.managesubjects.subject.delete');


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

    Route::get('/chair/room-availability', [
        ChairController::class,
        'roomAvailability'
    ])->name('chair.roomavailability');


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

    Route::get('/schedules/room/{room}/published', [
        ChairController::class,
        'index'
    ])->defaults('readonly', true)->name('schedules.published.room');

    Route::post('/chair/schedule/conflict-check', [
        ChairController::class,
        'checkScheduleFacultyConflict'
    ])->name('chair.schedule.conflict-check');

    Route::post('/chair/schedule/clear-cells', [
        ChairController::class,
        'clearScheduleCells'
    ])->name('chair.schedule.clear-cells');


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

Route::post('/chair/modifyschedule/clear', [
    ChairController::class,
    'clearModifiedSchedule'
])->name('chair.modify.schedule.clear');

    /*
    |--------------------------------------------------------------------------
    | ACADEMIC TERM / SCHEDULE LIBRARY
    |--------------------------------------------------------------------------
    */

    Route::get('/chair/schedule-library', [
        ChairController::class,
        'academicTerms'
    ])->name('chair.schedule.library');

    Route::post('/chair/academic-terms', [
        ChairController::class,
        'storeAcademicTerm'
    ])->name('chair.academic.terms.store');

    Route::post('/chair/academic-terms/{termId}/activate', [
        ChairController::class,
        'activateAcademicTerm'
    ])->name('chair.academic.terms.activate');

    Route::post('/chair/academic-terms/activate-selected', [
        ChairController::class,
        'activateSelectedAcademicTerm'
    ])->name('chair.academic.terms.activate-selected');

    Route::delete('/chair/academic-terms/{termId}', [
        ChairController::class,
        'deleteAcademicTerm'
    ])->name('chair.academic.terms.delete');

    Route::post('/chair/schedule-library/{sourceTermId}/reuse', [
        ChairController::class,
        'reuseAcademicSchedule'
    ])->name('chair.schedule.library.reuse');

});

// Account profile and sign-in settings (available to any authenticated user).
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::put('/profile/username', [ProfileController::class, 'updateUsername'])
        ->name('profile.username.update');

    Route::put('/profile/password', [PasswordController::class, 'update'])
        ->name('profile.password.update');

    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])
        ->name('profile.photo');
});
