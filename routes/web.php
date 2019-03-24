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
    return view('content/content');
});

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

/*Cliente*/
Route::get('/clientes', 'ClienteController@index');
Route::post('/cliente/registrar', 'ClienteController@store');
Route::put('/cliente/actualizar', 'ClienteController@update');

/*Proveedor*/
Route::get('/proveedores', 'ProveedorController@index');
Route::post('/proveedor/registrar', 'ProveedorController@store');
Route::put('/proveedor/actualizar', 'ProveedorController@update');