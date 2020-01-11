<?php

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/logout','Auth\LoginController@logout')->name('logout');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/dashboard','DashboardController@index')->name('dashboard');

Route::get('/user','UserController@index')->name('userList');
Route::get('/user/create','UserController@create')->name('userCreate');
Route::get('/user/{id}','UserController@edit')->name('userUpdate');
Route::post('/user','UserController@store')->name('userStore');

Route::get('organisation','OrganisationController@index')->name('organisationList');
Route::get('organisation/create','OrganisationController@create')->name('organisationCreate');
Route::get('organisation/{id}','OrganisationController@edit')->name('organisationUpdate');
Route::post('organisation','OrganisationController@store')->name('organisationStore');

Route::get('course','CourseController@index')->name('courseList');
Route::get('course/create','CourseController@create')->name('courseCreate');
Route::get('course/{id}','CourseController@edit')->name('courseUpdate');
Route::post('course','CourseController@store')->name('courseStore');

Route::get('/student','UserController@studentIndex')->name('studentList');
Route::get('/student/create','UserController@studentCreate')->name('studentCreate');
Route::get('/teacher','UserController@teacherIndex')->name('teacherList');

Route::get('/timetable/{id}','TimetableController@index')->name('timetableMain');
Route::post('/timetable/{id}','TimetableController@store')->name('timetableStore');
