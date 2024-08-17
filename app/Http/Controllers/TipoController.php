<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\tipo;
use Illuminate\Http\Request;

class TipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipo = tipo::where('estado', false)->get();
        return response()->json(['tipo'=>$tipo]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tipo=new tipo();
        $tipo->tipo=$request->tipo;
        $tipo->save();

        return response()->json(['message'=>'tipo de usuario creado'],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(tipo $tipo)
    {
        $tipos=tipo::where('tipo',$tipo)->get();
        return response()->json(['tipos'=>$tipos]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(tipo $tipo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, tipo $tipo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(tipo $id)
    {
        $tipo=tipo::find($id);
        if($tipo){
            $tipo->estado =true;
            $tipo->save();
            return response()->json(['message'=>'Eliminado corrrecto']);
        }
    }

    public function listado(){
        $usuario= DB::tipos('tipos')
        ->join('user','tipos_id','=','users.tipos_id')
        ->select('users_id','users.name','users.email','tipos.tipos_id','tipos.tipo')
        ->get();
        return response()->json(['usuario'=>$usuario]);
    }
}
