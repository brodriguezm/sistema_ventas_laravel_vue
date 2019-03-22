<?php

namespace App\Http\Controllers;

use App\Articulo;
use Illuminate\Http\Request;

class ArticuloController extends Controller
{
    public function validateRequest($request){
        if(!$request->ajax()) return true;
    }

    public function index(Request $request)
    {
        //if($this->validateRequest($request)) return redirect('/');
        //$categorias = Categoria::all();
        //return $categorias;
        
        //Paginacion con Query Builder
        //$categorias = DB::table('categorias')->paginate(2);
        //Paginacion con Eloquent
        $textFilter = $request->textFilter;
        $typeFilter = $request->typeFilter;

        if($textFilter != ''){
            $articulos = Articulo::join('categorias', 'articulos.idcategoria','=','categorias.id')
                ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria',
                         'articulos.precio_venta','articulos.stock','articulos.descripcion','articulos.condicion')
                ->where('articulos.'.$typeFilter, 'like', '%'.$textFilter.'%')
                ->orderBy('articulos.id', 'desc')->paginate(2);
        }else{
            $articulos = Articulo::join('categorias', 'articulos.idcategoria','=','categorias.id')
                ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria',
                         'articulos.precio_venta','articulos.stock','articulos.descripcion','articulos.condicion')
                ->orderBy('articulos.id', 'desc')->paginate(2);
        }

        return [
            'pagination' => [
                'total'         => $articulos->total(),
                'current_page'  => $articulos->currentPage(),
                'per_page'      => $articulos->perPage(),
                'last_page'     => $articulos->lastPage(),
                'from'          => $articulos->firstItem(),
                'to'            => $articulos->lastItem()
            ],
            'categorias' => $articulos
        ];
    }

    public function store(Request $request)
    {
        if($this->validateRequest($request)) return redirect('/');
        $articulo = new Articulo();
        $articulo->idcategoria = $request->idcategoria;
        $articulo->codigo = $request->codigo;
        $articulo->nombre = $request->nombre;
        $articulo->precio_venta = $request->precio_venta;
        $articulo->stock = $request->stock;
        $articulo->descripcion = $request->descripcion;
        $articulo->condicion = 1;
        $articulo->save();
    }

    public function update(Request $request)
    {
        if($this->validateRequest($request)) return redirect('/');
        $articulo = Articulo::findOrFail($request->id);
        $articulo->idcategoria = $request->idcategoria;
        $articulo->codigo = $request->codigo;
        $articulo->nombre = $request->nombre;
        $articulo->precio_venta = $request->precio_venta;
        $articulo->stock = $request->stock;
        $articulo->descripcion = $request->descripcion;
        $articulo->condicion = 1;
        $articulo->save();
    }

    public function activate(Request $request)
    {
        if($this->validateRequest($request)) return redirect('/');
        $articulo = Articulo::findOrFail($request->id);
        $articulo->condicion = '1';
        $articulo->save();
    }

    public function desactivate(Request $request)
    {
        if($this->validateRequest($request)) return redirect('/');
        $articulo = Articulo::findOrFail($request->id);
        $articulo->condicion = '0';
        $articulo->save();
    }
}
