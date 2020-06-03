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
    return redirect('login');
});
Route::get('/get/{folderA}/{folderB}','ImageController@get');
Route::post('/post','ImageController@post')->name('post');
Auth::routes([
    'register' => false,
]);
Route::group(['middleware' => ['auth'],],function(){
        
    Route::get('/home', function() {
        return view('home');
    })->name('home');
    
    Route::group(['prefix' => 'admin'],function()
    {
        // students
        Route::get('/add-student','AdminController@getAddStudent')->name('add.student');
        Route::post('/add-student','AdminController@postAddStudent')->name('add.student');
        Route::get('/edit-student/{name}', 'AdminController@getUpdateStudent')->name('edit.student');
        Route::post('/update-student/{name}', 'AdminController@postUpdateStudent')->name('update.student');
        Route::get('/list-students','AdminController@getListStudents')->name('list.student');
        Route::get('/view-student/{name}', 'AdminController@getViewStudent')->name('view.student');

        // Courses
        Route::get('/add-course','AdminController@getAddCourse')->name('add.course');
        Route::post('/add-course','AdminController@postAddCourse')->name('add.course');
        Route::get('/list-courses', 'AdminController@getListCourses')->name('list.course');
        Route::get('/edit-course/{code}', 'AdminController@getUpdateCourse')->name('edit.course');
        Route::post('/update-course/{code}', 'AdminController@postUpdateCourse')->name('update.course');
    });
});
