<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
use App\Categoria;

class CategoriaController extends Controller
{
    public function validateRequest($request){
        if(!$request->ajax()) return true;
    }

    public function index(Request $request)
    {
        if($this->validateRequest($request)) return redirect('/');
        //$categorias = Categoria::all();
        //return $categorias;
        
        //Paginacion con Query Builder
        //$categorias = DB::table('categorias')->paginate(2);
        //Paginacion con Eloquent
        $textFilter = $request->textFilter;
        $typeFilter = $request->typeFilter;

        if($textFilter != ''){
            $categorias = Categoria::where($typeFilter, 'like', '%'.$textFilter.'%')->orderBy('id', 'desc')->paginate(2);
        }else{
            $categorias = Categoria::orderBy('id', 'desc')->paginate(2);
        }

        return [
            'pagination' => [
                'total'         => $categorias->total(),
                'current_page'  => $categorias->currentPage(),
                'per_page'      => $categorias->perPage(),
                'last_page'     => $categorias->lastPage(),
                'from'          => $categorias->firstItem(),
                'to'            => $categorias->lastItem()
            ],
            'categorias' => $categorias
        ];
    }

    public function selectCategorias(Request $request){
        if($this->validateRequest($request)) return redirect('/');

        $categorias = Categoria::where('condicion', '=', '1')
            ->select('id', 'nombre')
            ->orderBy('id', 'asc')
            ->get();

        return [ 'categorias' => $categorias];
    }

    public function store(Request $request)
    {
        if($this->validateRequest($request)) return redirect('/');
        $categoria = new Categoria();
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->condicion = 1;
        $categoria->save();
    }

    public function update(Request $request)
    {
        if($this->validateRequest($request)) return redirect('/');
        $categoria = Categoria::findOrFail($request->id);
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->condicion = '1';
        $categoria->save();
    }

    public function activate(Request $request)
    {
        if($this->validateRequest($request)) return redirect('/');
        $categoria = Categoria::findOrFail($request->id);
        $categoria->condicion = '1';
        $categoria->save();
    }

    public function desactivate(Request $request)
    {
        if($this->validateRequest($request)) return redirect('/');
        $categoria = Categoria::findOrFail($request->id);
        $categoria->condicion = '0';
        $categoria->save();
    }
}
