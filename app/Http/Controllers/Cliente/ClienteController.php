<?php

namespace App\Http\Controllers\Cliente;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Cliente;
use App\Resguardo;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes=Cliente::All();

        return response()->json(['data'=>$clientes],200);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      $reglas= [
          'NIF'=>'required|unique:clientes',
          'email'=> 'required|email|unique:clientes',
          'nombre'=> 'required',
      ];

      $this->validate($request,$reglas);

      $campos=$request->all();

      $cliente = Cliente::create($campos);

      return response()->json(['data'=>$cliente],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cliente=Cliente::findOrFail($id);
        return response()->json(['data'=>$cliente],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $cliente=Cliente::findOrFail($id);

      $reglas= [
          'NIF'=>'unique:clientes,NIF,'.$cliente->id,
          'email'=> 'email|unique:clientes,email,'.$cliente->id,

      ];

      $this->validate($request,$reglas);

      if($request->has('email') && $cliente->email != $request->email){
        $cliente->email=$request->email;
      }

      if($request->has('NIF') && $cliente->NIF != $request->NIF){
        $cliente->NIF=$request->NIF;
      }

      if($request->has('nombre')){
        $cliente->nombre=$request->nombre;
      }

      if($request->has('telefono')){
        $cliente->telefono=$request->telefono;
      }

      if(!$cliente->isDirty()){
        return response()->json(['error'=>'Se debe especificar al menos un valor diferente para actualizar','code'=>422],422);

      }
      $cliente->save();

      return response()->json(['data' => $cliente],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $cliente= Cliente::findOrFail($id);

      $cliente->delete();

      return response()->json(['data' => $cliente],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reservasHotel($id)
    {
      $cliente= Cliente::findOrFail($id);

      $resguardos=Resguardo::where('Cliente_id',$cliente->id)->get();

      return response()->json(['data' => $resguardos ],200);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function clientePorNIF(Request $request)
    {

        if($request->has('NIF')){
          $cliente=Cliente::where('NIF',$request->NIF)->first();
          if($cliente==null){
            return response()->json(['error'=>'no existe ese NIF de cliente','code'=>409],409);
          }
          return response()->json(['data'=>$cliente],200);
        }else{
          return response()->json(['error'=>'Necesito query NIF','code'=>409],409);
        }


    }
}
