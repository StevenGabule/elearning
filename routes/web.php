<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Page\PageController@index')->name('homepage');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('course', 'CourseController')->middleware('auth');

Route::get('/courses', 'CourseController@fetch')->name('instructor.course.fetch');
Route::get('/course/{slug}/lesson', 'Page\PageController@showCourse')->name('student.course.fetch');

Route::resource('section', 'SectionController');
Route::get('/course/{course}/sections', 'SectionController@showAllSection')->name('course.sections.show');

Route::post('/course/section/lesson/store', 'LessonController@store')->name('lesson.store');
Route::get('/course/{course}/section/{section}', 'LessonController@createLesson')->middleware('auth');
Route::get('/course/{course}/section/{section}/lesson/{lesson}/upload', 'LessonController@upload')->name('lesson.upload.store');
Route::post('/course/section/lesson/{lesson}/videos', 'VideoController@store');

Route::get('videos/{video}','VideoController@show');
Route::put('videos/{video}', 'VideoController@updateViews');

Route::post('/payments/pay', 'Payment\PaymentController@buyNow')->name('buy_now');
Route::get('/payments/approval', 'Payment\PaymentController@approval')->name('approved');
Route::get('/payments/cancelled', 'Payment\PaymentController@cancelled')->name('cancelled');

Route::get('/course/{slug}/learn/lecture/start=0/{lessonId?}', 'Page\PageController@learnCourse')->name('student.take_course')->middleware('auth');

Route::get('/student/courses', 'Student\StudentController@index')->name('student.course');
Route::post('/student/courses', 'CompletionController@store')->name('student.course.lesson.save')->middleware('auth');


Route::get('/category/{slug}', 'CategoryController@index')->name('category.index');
Route::get('/instructor', 'Page\PageController@instructor')->name('instructor.index')->middleware('auth');
Route::get('/instructor/profile', 'Page\PageController@instructorProfile')->name('instructor.profile')->middleware('auth');
Route::get('/cart', 'Page\PageController@cart')->name('cart.index')->middleware('auth');

// route for refund api
Route::post('/student/course/refund', 'Student\StudentController@refund')->name('student.course.refund');

Route::post('/instructor/payout', 'PayoutController@store')->name('instructor.course.payout');



/*
 * ROUTE FOR ADMIN REQUEST
 * @PARAM /ADMIN/---
 * */
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function() {
   Route::get('/', 'Admin\AdminController@index')->name('admin.dashboard.index');
   Route::get('/students', 'Admin\AdminController@student')->name('admin.dashboard.student');
   Route::get('/courses', 'Admin\AdminController@course')->name('admin.dashboard.course');
   Route::get('/instructors', 'Admin\AdminController@instructor')->name('admin.dashboard.instructor');
   Route::get('/transactions', 'Admin\AdminController@transaction')->name('admin.dashboard.transaction');
   Route::get('/payments', 'Admin\AdminController@payment')->name('admin.dashboard.payment');
   Route::get('/enrollments', 'Admin\AdminController@enrollment')->name('admin.dashboard.enrollment');
   Route::get('/completions', 'Admin\AdminController@completion')->name('admin.dashboard.completion');
   Route::get('/comments', 'Admin\AdminController@comment')->name('admin.dashboard.comment');
   Route::get('/reviews', 'Admin\AdminController@review')->name('admin.dashboard.review');
   Route::get('/approvals', 'Admin\AdminController@approval')->name('admin.dashboard.approval');
   Route::get('/payouts', 'Admin\AdminController@payout')->name('admin.dashboard.payout');
   Route::get('/refunds', 'Admin\AdminController@refund')->name('admin.dashboard.refund');
   Route::get('/announcements', 'Admin\AdminController@announcement')->name('admin.dashboard.announcement');

   Route::post('/course/approve', 'Admin\AdminController@approvalCourse')->name('admin.course.approve');

   Route::get('/categories', 'Admin\AdminController@category')->name('admin.dashboard.categories');
   Route::post('/categories', 'Admin\AdminController@categoryLive')->name('admin.category.live');

   Route::post('/course/refund', 'Payment\PaymentController@refund')->name('admin.payment.refund');

});

