<?php

namespace App\Http\Controllers;

use App\Persona;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
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
            $personas = User::join('personas','users.id', '=', 'personas.id')
                ->join('roles', 'users.id_rol', '=', 'personas.id')
                ->select('personas.id', 'personas.nombre', 'personas.tipo_documento',
                    'personas.num_documento', 'personas.direccion', 'personas.telefono',
                    'personas.email', 'users.username', 'users.password',
                    'users.condicion', 'users.id_rol', 'roles.nombres as rol')
                ->where('personas.'.$typeFilter, 'like', '%'.$textFilter.'%')
                ->orderBy('personas.id', 'desc')
                ->paginate(2);
        }else{
            $personas = User::join('personas','users.id', '=', 'personas.id')
                ->join('roles', 'users.id_rol', '=', 'personas.id')
                ->select('personas.id', 'personas.nombre', 'personas.tipo_documento',
                    'personas.num_documento', 'personas.direccion', 'personas.telefono',
                    'personas.email', 'users.username', 'users.password',
                    'users.condicion', 'users.id_rol', 'roles.nombre as rol')
                ->orderBy('personas.id', 'desc')
                ->paginate(2);
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
            'personas' => $personas
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

            $user = new User();
            $user->id = $persona->id;
            $user->usuario = $persona->usuario;
            $user->password = bcrypt($request->password);
            $user->condicion = '1';
            $user->id_rol = $request->id_rol;
            $user->save();

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
            $persona = Persona  ::findOrFail($request->id);
            $user= User::findOrFail($persona->id);

            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->num_documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->save();

            $user->username = $request->contacto;
            $user->password = bcrypt($request->telefono_contacto) ;
            $user->condicion = (1);
            $user->id_rol = $request->id_rol;
            $user->save();

            DB::commit();
        }
        catch (\Exception $ex){
            DB::rollBack();
        }
    }

    public function activate(Request $request)
    {
        if($this->validateRequest($request)) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condicion = '1';
        $user->save();
    }

    public function desactivate(Request $request)
    {
        if($this->validateRequest($request)) return redirect('/');
        $user = Categoria::findOrFail($request->id);
        $user->condicion = '0';
        $user->save();
    }
}
