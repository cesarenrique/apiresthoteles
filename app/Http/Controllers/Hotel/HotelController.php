<?php

namespace App\Http\Controllers\Hotel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Hotel;
use App\Localidad;
use App\Provincia;
use App\Pais;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hoteles= Hotel::All();

        return response()->json(['data'=>$hoteles],200);
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
          'nombre'=>'required',
          'NIF'=> 'required|unique:hotels',
          'Localidad_id'=> 'required',
          'Provincia_id'=>'required',
          'Pais_id'=>'required'
      ];

      $this->validate($request,$reglas);

      $campos= $request->all();

      $localidad= Localidad::findOrFail($request->Localidad_id);

      if($localidad->Provincia_id!=$request->Provincia_id){
        return response()->json(['error'=>'Provincia no coincide con localidad','code'=>409],409);

      }
      if($localidad->Pais_id!=$request->Pais_id){
        return response()->json(['error'=>'Pais no coincide con localidad','code'=>409],409);

      }

      $hotel = Hotel::create($campos);

      return response()->json(['data'=>$hotel],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $hotel=Hotel::findOrFail($id);

        return response()->json(['data' => $hotel],200);
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
      $hotel= Hotel::findOrFail($id);
      $reglas= [
          'NIF'=> 'unique:hotels,NIF,'.$hotel->id,

      ];

      $this->validate($request,$reglas);

      if($request->has('nombre')){
        $hotel->nombre=$request->nombre;
      }

      if($request->has('NIF') && $hotel->NIF != $request->NIF){
        $hotel->NIF=$request->NIF;
      }

      if($request->has('Localidad_id')){
        $localidad= Localidad::findOrFail($request->Localidad_id);

        if($request->has('Provincia_id') && $localidad->Provincia_id!=$request->Provincia_id){
          return response()->json(['error'=>'Provincia no coincide con localidad','code'=>409],409);

        }

        if($request->has('Pais_id') && $localidad->Pais_id!=$request->Pais_id){
          return response()->json(['error'=>'Pais no coincide con localidad','code'=>409],409);

        }

        $localidad2= Localidad::findOrFail($hotel->Localidad_id);

        if(!($request->has('Provincia_id')) && !($request->has('Pais_id')) && !( $localidad->Provincia_id==$localidad2->Provincia_id &&
          $localidad->Pais_id==$localidad2->Pais_id)){
          return response()->json(['error'=>'Pais o Provincia no coincide con localidad','code'=>409],409);
        }
        $hotel->Localidad_id=$request->Localidad_id;
      }

      if($request->has('Provincia_id')){
        $localidad= Localidad::findOrFail($hotel->Localidad_id);

        if($request->has('Localidad_id') && $localidad->id!=$request->Localidad_id){
          return response()->json(['error'=>'Localidad no coincide con Provincia','code'=>409],409);

        }

        if($request->has('Pais_id') && $localidad->Pais_id!=$request->Pais_id){
          return response()->json(['error'=>'Pais no coincide con Provincia o Localidad','code'=>409],409);

        }

        $provincia= Provincia::findOrFail($request->Provincia_id);

        if(!($request->has('Pais_id')) && !($provincia->id==$localidad->Provincia_id &&
          $provincia->Pais_id==$localidad->Pais_id)){
          return response()->json(['error'=>'Pais o Localidad no coincide con Provincia','code'=>409],409);
        }
        $hotel->Provincia_id=$request->Provincia_id;
      }

      if($request->has('Pais_id')){
        $localidad= Localidad::findOrFail($hotel->Localidad_id);

        if( $request->has('Localidad_id') && $localidad->Provincia_id!=$request->Provincia_id){
          return response()->json(['error'=>'Localidad no coincide con Provincia','code'=>409],409);

        }

        if($request->has('Provincia_id') && $localidad->Provincia_id!=$request->Provincia_id){
          return response()->json(['error'=>'Provincia no coincide con localidad','code'=>409],409);

        }

        if($localidad->Pais_id!=$request->Pais_id){
          return response()->json(['error'=>'Pais no coincide con Provincia y localidad','code'=>409],409);

        }
        $hotel->Pais_id=$request->Pais_id;
      }

      $hotel->save();

      return response()->json(['data'=>$hotel],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $hotel= Hotel::findOrFail($id);

      $hotel->delete();

      return response()->json(['data' => $hotel],200);
    }
}
