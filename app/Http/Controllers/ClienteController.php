<?php

namespace App\Http\Controllers;

use App\Persona;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function validateRequest($request){
        if(!$request->ajax()) return true;
    }

    public function index(Request $request)
    {
        //if($this->validateRequest($request)) return redirect('/');

        $textFilter = $request->textFilter;
        $typeFilter = $request->typeFilter;

        if($textFilter != ''){
            $personas = Persona::where($typeFilter, 'like', '%'.$textFilter.'%')->orderBy('id', 'desc')->paginate(2);
        }else{
            $personas = Persona::orderBy('id', 'desc')->paginate(2);
        }

        return [
            'pagination' => [
                'total'         => $personas->total(),
                'current_page'  => $personas->currentPage(),
                'per_page'      => $personas->perPage(),
                'last_page'     => $personas->lastPage(),
                'from'          => $personas->firstItem(),
                'to'            => $personas->lastItem()
            ],
            'clientes' => $personas
        ];
    }

    public function store(Request $request)
    {
        if($this->validateRequest($request)) return redirect('/');
        $persona = new Persona();
        $persona->nombre = $request->nombre;
        $persona->tipo_documento = $request->tipo_documento;
        $persona->num_documento = $request->num_documento;
        $persona->direccion = $request->direccion;
        $persona->telefono = $request->telefono;
        $persona->email = $request->email;
        $persona->save();
    }

    public function update(Request $request)
    {
        if($this->validateRequest($request)) return redirect('/');
        $persona = Persona::findOrFail($request->id);
        $persona->nombre = $request->nombre;
        $persona->tipo_documento = $request->tipo_documento;
        $persona->num_documento = $request->num_documento;
        $persona->direccion = $request->direccion;
        $persona->telefono = $request->telefono;
        $persona->email = $request->email;
        $persona->save();
    }

}
