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

Route::group(['middleware' => ['guest']], function(){
    /*Login*/
    Route::get('/', 'Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
});

Route::group(['middleware' => ['auth']], function() {
    Route::get('/dashboard', function () {
        return view('content/content');
    })->name('dashboard');

    Route::group(['middleware' => ['Almacenero']], function () {
        /*Categoria*/
        Route::get('/categorias', 'CategoriaController@index');
        Route::post('/categorias/registrar', 'CategoriaController@store');
        Route::put('/categorias/actualizar', 'CategoriaController@update');
        Route::put('/categorias/activar', 'CategoriaController@activate');
        Route::put('/categorias/desactivar', 'CategoriaController@desactivate');
        Route::get('/categorias/selectCategorias', 'CategoriaController@selectCategorias');

        /*Articulo*/
        Route::get('/articulos', 'ArticuloController@index');
        Route::post('/articulo/registrar', 'ArticuloController@store');
        Route::put('/articulo/actualizar', 'ArticuloController@update');
        Route::put('/articulo/activar', 'ArticuloController@activate');
        Route::put('/articulo/desactivar', 'ArticuloController@desactivate');

        /*Proveedor*/
        Route::get('/proveedores', 'ProveedorController@index');
        Route::post('/proveedor/registrar', 'ProveedorController@store');
        Route::put('/proveedor/actualizar', 'ProveedorController@update');

    });

    Route::group(['Middleware' => ['Vendedor']], function () {
        /*Cliente*/
        Route::get('/clientes', 'ClienteController@index');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::put('/cliente/actualizar', 'ClienteController@update');
    });

    Route::group(['Middleware' => ['Administrador']], function () {
        /*Categoria*/
        Route::get('/categorias', 'CategoriaController@index');
        Route::post('/categorias/registrar', 'CategoriaController@store');
        Route::put('/categorias/actualizar', 'CategoriaController@update');
        Route::put('/categorias/activar', 'CategoriaController@activate');
        Route::put('/categorias/desactivar', 'CategoriaController@desactivate');
        Route::get('/categorias/selectCategorias', 'CategoriaController@selectCategorias');

        /*Articulo*/
        Route::get('/articulos', 'ArticuloController@index');
        Route::post('/articulo/registrar', 'ArticuloController@store');
        Route::put('/articulo/actualizar', 'ArticuloController@update');
        Route::put('/articulo/activar', 'ArticuloController@activate');
        Route::put('/articulo/desactivar', 'ArticuloController@desactivate');

        /*Proveedor*/
        Route::get('/proveedores', 'ProveedorController@index');
        Route::post('/proveedor/registrar', 'ProveedorController@store');
        Route::put('/proveedor/actualizar', 'ProveedorController@update');

        /*Cliente*/
        Route::get('/clientes', 'ClienteController@index');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::put('/cliente/actualizar', 'ClienteController@update');

        /*Roles*/
        Route::get('/roles', 'RolController@index');
        Route::get('/roles/selectRoles', 'RolController@selectRoles');

        /*Users*/
        Route::get('/users', 'UserController@index');
        Route::post('/user/registrar', 'UserController@store');
        Route::put('/user/actualizar', 'UserController@update');
        Route::put('/user/activar', 'UserController@activate');
        Route::put('/user/desactivar', 'UserController@desactivate');
    });

});

//Route::get('/home', 'HomeController@index')->name('home');
