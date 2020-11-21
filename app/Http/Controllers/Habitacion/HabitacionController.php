<?php

namespace App\Http\Controllers\Habitacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Habitacion;
use App\Hotel;
use App\Id;
use App\TipoHabitacion;

class HabitacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $habitacion=Habitacion::where('Hotel_id',$id)->firstOrFail();
        $habitaciones=Habitacion::where('Hotel_id',$id)->get();


        return response()->json(['data'=>$habitaciones],200);
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
    public function store(Request $request, $id)
    {
      $hotel=Hotel::findOrFail($id);
      $reglas= [
          'numero'=>'required',
          'TipoHabitacion_id'=> 'required',
      ];

      $this->validate($request,$reglas);

      $existe=Habitacion::where('Hotel_id',$hotel->id)->where('numero',$request->numero)->first();
      if($existe!=null){
        return response()->json(['error'=>'El numero de habitacion existe en dicho hotel','code'=>409],409);
      }
      $campos= $request->all();
      $campos['Hotel_id']=$hotel->id."";

      $nuevoId=Id::where('nombre','habitacions')->first();

      $campos['id']=$nuevoId->posicion."";
      $nuevoId->posicion+=1;
      $nuevoId->save();


      $habitacion = Habitacion::create($campos);

      return response()->json(['data'=>$habitacion],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($hotel,$id)
    {
      $hotel=Hotel::findOrFail($hotel);
      $habitacion=Habitacion::findOrFail($id);

      return response()->json(['data' => $habitacion],200);
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
    public function update(Request $request, $hotel, $id)
    {
      $hotel=Hotel::findOrFail($hotel);
      /*
      $reglas= [
          'numero'=>'required',
          'TipoHabitacion_id'=> 'required',
      ];

      $this->validate($request,$reglas);
      */
      $habitacion=Habitacion::findOrFail($id);

      if($request->has('numero')){

        $existe=Habitacion::where('Hotel_id',$hotel->id)->where('numero',$request->numero)->first();

        if($existe!=null && $existe->id!=$habitacion->id){
          return response()->json(['error'=>'El numero de habitacion existe en dicho hotel','code'=>409],409);
        }
        $habitacion->numero=$request->numero;
      }

      if($request->has('TipoHabitacion_id')){
        $tipo=TipoHabitacion::where('id',$request->TipoHabitacion_id)->first();
        if($tipo==null){

            return response()->json(['error'=>'El tipo de habitacion no existe en clases tipos habitacion','code'=>409],409);
        }
        $habitacion->TipoHabitacion_id=$request->TipoHabitacion_id;
      }


      $habitacion->save();

      return response()->json(['data'=>$habitacion],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($hotel,$id)
    {
      $hotel= Hotel::findOrFail($hotel);
      $habitacion= Habitacion::findOrFail($id);

      $habitacion->delete();

      return response()->json(['data' => $habitacion],200);

    }

    /**
     * Verifica si esta libre
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function libre(Request $request,$hotel,$id){

    }

    /**
     * Verifica si esta ocupada
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ocupada($id){
      //
    }

    /**
     * Verifica si esta ocupada
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reservar($id){
      //
    }


}
