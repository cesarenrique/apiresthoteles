<?php

namespace App\Http\Controllers\Habitacion;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Habitacion;
use App\Hotel;
use App\Id;
use App\TipoHabitacion;
use App\Fecha;
use App\Reserva;
use App\Cliente;
use App\Alojamiento;
use App\ResguardoHotel;
use App\Resguardo;

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
    public function show($hotelId,$id)
    {
      $hotel=Hotel::findOrFail($hotelId);
      $habitacion=Habitacion::where('Hotel_id',$hotel->id)->where('id',Intval($id))->first();
      if($habitacion==null){
        return response()->json(['error'=>'El numero de habitacion existe en dicho hotel','code'=>404],404);
      }
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
    public function update(Request $request, $hotelId, $id)
    {
      $hotel=Hotel::findOrFail($hotelId);
      /*
      $reglas= [
          'numero'=>'required',
          'TipoHabitacion_id'=> 'required',
      ];

      $this->validate($request,$reglas);
      */
      $habitacion=Habitacion::where('Hotel_id',$hotel->id)->where('id',Intval($id))->first();
      if($habitacion==null){
        return response()->json(['error'=>'El numero de habitacion existe en dicho hotel','code'=>404],404);
      }

      if($request->has('numero')){
        $existe=Habitacion::where('Hotel_id',$hotel->id)->where('id',Intval($id))->where('numero',$request->numero)->first();

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
    public function libre(Request $request,$hotel_id,$id){
        $hotel= Hotel::findOrFail($hotel_id);

        $habitacion=Habitacion::findOrFail($id);

        if(!$request->has('desde') || !($request->has('hasta'))) {
           return response()->json(['error'=>'necesitamos el campo fecha desde y fecha hasta','code'=>409],409);
        }
        $fechas=Fecha::where('fecha','>=',$request->desde)->where('fecha','<=',$request->hasta)->where('Hotel_id',$hotel->id)->get();
        $prueba=Fecha::where('fecha','>=',$request->desde)->where('fecha','<=',$request->hasta)->where('Hotel_id',$hotel->id)->first();
        if($prueba==null){
          return response()->json(['error'=>'necesitamos una fecha desde y fecha hasta que contemple base datos','code'=>409],409);
        }


        $i=0;
        $j=0;
        $libres=array();
        foreach ($fechas as $fecha ) {
          $aux=Reserva::where('Fecha_id',$fecha->id)->where('Habitacion_id',$habitacion->id)->where('Hotel_id',$hotel->id)->where('reservado',Reserva::LIBRE)->first();
          if($aux!=null ) {
            $libres[$j]= $fecha;
            $j++;
          }
          $i++;
        }

        if(count($libres)==0){
          return response()->json(['error'=>'no tiene fechas libres','code'=>409],409);
        }

        return response()->json(['data' => $libres],200);

    }

    /**
     * Verifica si esta ocupada
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ocupada(Request $request,$hotel_id,$id){
      $hotel= Hotel::findOrFail($hotel_id);

      $habitacion=Habitacion::findOrFail($id);

      if(!$request->has('desde') || !($request->has('hasta'))) {
         return response()->json(['error'=>'necesitamos el campo fecha desde y fecha hasta','code'=>409],409);
      }
      $fechas=Fecha::where('fecha','>=',$request->desde)->where('fecha','<=',$request->hasta)->where('Hotel_id',$hotel->id)->get();
      $prueba=Fecha::where('fecha','>=',$request->desde)->where('fecha','<=',$request->hasta)->where('Hotel_id',$hotel->id)->first();
      if($prueba==null){
        return response()->json(['error'=>'necesitamos una fecha desde y fecha hasta que contemple base datos','code'=>409],409);
      }


      $i=0;
      $j=0;
      $libres=array();
      foreach ($fechas as $fecha ) {
        $aux=Reserva::where('Fecha_id',$fecha->id)->where('Habitacion_id',$habitacion->id)->where('Hotel_id',$hotel->id)->where('reservado',Reserva::RESERVADO)->first();
        if($aux!=null ) {
          $ocupado[$j]= $fecha;
          $j++;
        }
        $i++;
      }

      if(count($ocupado)==0){
        return response()->json(['error'=>'no tiene fechas ocupacion','code'=>409],409);
      }

      return response()->json(['data' => $ocupado],200);
    }

    /**
     * Verifica si esta ocupada
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function total(Request $request,$hotel_id,$id){
      $hotel= Hotel::findOrFail($hotel_id);

      $habitacion=Habitacion::findOrFail($id);


      if(!$request->has('desde') || !($request->has('hasta')) ) {
         return response()->json(['error'=>'necesitamos el campo fecha desde y fecha hasta','code'=>409],409);
      }
      $fechas=Fecha::where('fecha','>=',$request->desde)->where('fecha','<=',$request->hasta)->where('Hotel_id',$hotel->id)->get();
      $prueba=Fecha::where('fecha','>=',$request->desde)->where('fecha','<=',$request->hasta)->where('Hotel_id',$hotel->id)->first();
      if($prueba==null){
        return response()->json(['error'=>'necesitamos una fecha desde y fecha hasta que contemple base datos','code'=>409],409);
      }


      $i=0;
      $j=0;
      $libres=array();
      $precios=array();
      $acum=0;
      foreach ($fechas as $fecha ) {
        $aux=Reserva::where('Fecha_id',$fecha->id)->where('Habitacion_id',$habitacion->id)->where('Hotel_id',$hotel->id)->where('reservado',Reserva::LIBRE)->first();
        if($aux!=null ) {
          $libres[$j]=$aux;
          $j++;
          $aux2=Alojamiento::find($aux->Alojamiento_id);
          $acum+=$aux2->precio;
          $precios[$j]=$aux2;
        }else{
          return response()->json(['error'=>'la habitacion no se encuentra libre en estos dias','code'=>409],409);

        }
        $i++;
      }



      $resguardoHotel=ResguardoHotel::where('Hotel_id',$hotel->id)->first();
      $porcentaje=0;
      if($resguardoHotel!=null){

        $porcentaje=$resguardoHotel->porcentaje;
        $minimo=$acum*$porcentaje/100;
      }else{
        $minimo=$acum;
        $porcentaje=100;
      }

      $total=array();
      $total['porcentaje']=$porcentaje;
      $total['minimo']=$minimo;
      $total['total']=$acum;

      return response()->json(['data' => $total ],200);
    }

    /**
     * Verifica si esta ocupada
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reservar(Request $request,$hotel_id,$id,$cliente_id){
      $hotel= Hotel::findOrFail($hotel_id);

      $habitacion=Habitacion::findOrFail($id);

      $cliente=Cliente::findOrFail($cliente_id);

      if(!$request->has('desde') || !($request->has('hasta')) || !($request->has('pagado'))) {
         return response()->json(['error'=>'necesitamos el campo fecha desde y fecha hasta','code'=>409],409);
      }
      $fechas=Fecha::where('fecha','>=',$request->desde)->where('fecha','<=',$request->hasta)->where('Hotel_id',$hotel->id)->get();
      $prueba=Fecha::where('fecha','>=',$request->desde)->where('fecha','<=',$request->hasta)->where('Hotel_id',$hotel->id)->first();
      if($prueba==null){
        return response()->json(['error'=>'necesitamos una fecha desde y fecha hasta que contemple base datos','code'=>409],409);
      }


      $i=0;
      $j=0;
      $libres=array();
      $precios=array();
      $acum=0;
      foreach ($fechas as $fecha ) {
        $aux=Reserva::where('Fecha_id',$fecha->id)->where('Habitacion_id',$habitacion->id)->where('Hotel_id',$hotel->id)->where('reservado',Reserva::LIBRE)->first();
        if($aux!=null ) {
          $libres[$j]=$aux;
          $j++;
          $aux2=Alojamiento::find($aux->Alojamiento_id);
          $acum+=$aux2->precio;
          $precios[$j]=$aux2;
        }else{
          return response()->json(['error'=>'la habitacion no se encuentra libre en estos dias','code'=>409],409);

        }
        $i++;
      }



      $resguardoHotel=ResguardoHotel::where('Hotel_id',$hotel->id)->first();
    if($resguardoHotel!=null){
      if(($request->pagado)/$acum*100<$resguardoHotel->porcentaje){
        return response()->json(['error'=>'no cumple minimo pagado para reservar en dicho hotel','code'=>409],409);
      }
    }else{
      if($acum>$request->pagado){
        return response()->json(['error'=>'no cumple minimo pagado para reservar en dicho hotel','code'=>409],409);
      }
    }

    if($acum<$request->pagado){
      $request->pagado=$acum;
    }


      $cantidad=count($libres);
      $pagado=($request->pagado)/$cantidad;

      $k=1;
      foreach ($libres as $libre) {
        $estado=Resguardo::RESERVA_ACEPTADA;
        DB::statement(' Insert into resguardos (Hotel_id,Habitacion_id,Fecha_id,Alojamiento_id,pagado,precio,estado,Cliente_id) values ('.$hotel->id.','.$habitacion->id.','.$libre->Fecha_id.','.$libre->Alojamiento_id.',"'.$pagado.'","'.$precios[$k]->precio.'","'.$estado.'",'.$cliente->id.')');
        $libre->reservado=Reserva::RESERVADO;
        $libre->save();
        $k++;
      }

      $resguardos=Resguardo::where('Cliente_id',$cliente->id)->where('Habitacion_id',$habitacion->id)->where('Hotel_id',$hotel->id)->get();
      $confirmacion=array();
      $l=0;
      foreach ($resguardos as $resguardo) {
        foreach ($fechas as $fecha) {
           if($resguardo->Fecha_id==$fecha->id){
              $confirmacion[$l]=$resguardo;
              $l++;
           }
        }
      }

      return response()->json(['data' => $confirmacion ],200);
    }


}
