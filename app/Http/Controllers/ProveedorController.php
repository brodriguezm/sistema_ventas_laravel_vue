<?php

namespace App\Http\Controllers;

use App\Persona;
use App\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProveedorController extends Controller
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
            $proveedores = Proveedor::join('personas','proveedores.id', '=', 'personas.id')
                            ->select('personas.id', 'personas.nombre', 'personas.tipo_documento',
                                    'personas.num_documento', 'personas.direccion', 'personas.telefono',
                                    'personas.email', 'proveedores.contacto', 'proveedores.telefono_contacto')
                            ->where('personas.'.$typeFilter, 'like', '%'.$textFilter.'%')
                            ->orderBy('personas.id', 'desc')
                            ->paginate(2);
        }else{
            $proveedores = Proveedor::join('personas','proveedores.id', '=', 'personas.id')
                            ->select('personas.id', 'personas.nombre', 'personas.tipo_documento',
                                'personas.num_documento', 'personas.direccion', 'personas.telefono',
                                'personas.email', 'proveedores.contacto', 'proveedores.telefono_contacto')
                            ->orderBy('personas.id', 'desc')
                            ->paginate(2);
        }

        return [
            'pagination' => [
                'total'         => $proveedores->total(),
                'current_page'  => $proveedores->currentPage(),
                'per_page'      => $proveedores->perPage(),
                'last_page'     => $proveedores->lastPage(),
                'from'          => $proveedores->firstItem(),
                'to'            => $proveedores->lastItem()
            ],
            'clientes' => $proveedores
        ];
    }

    public function store(Request $request)
    {
        if($this->validateRequest($request)) return redirect('/');

        try{
            DB::beginTransaction();
            $persona = new Persona();
            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->num_documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->save();

            $proveedor = new Proveedor();
            $proveedor->id = $persona->id;
            $proveedor->contacto = $request->contacto;
            $proveedor->telefono_contacto = $request->telefono_contacto;
            $proveedor->save();

            DB::commit();
        }
        catch (\Exception $ex){
            DB::rollBack();
        }
    }

    public function update(Request $request)
    {
        if($this->validateRequest($request)) return redirect('/');
        try{
            DB::beginTransaction();

            //Buscamos el proveedor a actualizar
            $proveedor = Proveedor::findOrFail($request->id);
            $persona = Persona::findOrFail($proveedor->id);

            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->num_documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->save();

            $proveedor->contacto = $request->contacto;
            $proveedor->telefono_contacto = $request->telefono_contacto;
            $proveedor->save();

            DB::commit();
        }
        catch (\Exception $ex){
            DB::rollBack();
        }
    }
}
