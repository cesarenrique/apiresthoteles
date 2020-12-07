<?php

namespace App\Http\Controllers\Hotel;

use Illuminate\Support\Facades\DB;
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

    /**
     * filtrar po lugar especifico
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function hotelPorLugar(Request $request){


      $reglas= [
          'Localidad_id'=> 'required',
          'Provincia_id'=> 'required',
          'Pais_id'=> 'required',
      ];

      $this->validate($request,$reglas);

      $lugar="";
      $hoteles="";

      if($request->has('Localidad_id') && $request->has('Provincia_id')  && $request->has('Pais_id') ){


          $lugar=Localidad::where('id',$request->Localidad_id)->where('Provincia_id',$request->Provincia_id)->where('Pais_id',$request->Pais_id)->first();
          if($lugar!=null){
            $hoteles= Hotel::where('Localidad_id',$lugar->id)->where('Provincia_id',$request->Provincia_id)->where('Pais_id',$request->Pais_id)->get();

          }else{
            return response()->json(['error'=>'Pais o Provincia o localidad caso no coinciden con las id tablas del servidor','code'=>409],409);
          }
      }else{
        return response()->json(['error'=>'No tiene lugar por donde filtrar','code'=>409],409);
      }


      return response()->json(['data'=>$hoteles],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reservas(Request $request)
    {

      if(!($request->has('Localidad_id') && $request->has('Provincia_id')  && $request->has('Pais_id')
        && $request->has('desde')  && $request->has('hasta') )){
        return response()->json(['error'=>'No tiene lugar por donde filtrar, falta query Localidad_id o Provincia_id o Pais_id o desde o hasta','code'=>409],409);
      }

      $localidad=$request->Localidad_id;
      $provincia=$request->Provincia_id;
      $pais=$request->Pais_id;
      $desde=$request->desde;
      $hasta=$request->hasta;

        $reservas=DB::select("select  r2.Fecha_id, r2.Pension_id, r2.TipoHabitacion_id,
 r2.Hotel_id, r2.Temporada_id, r2.Alojamiento_id, r2.Habitacion_id,
 precio, h.nombre 'HotelNombre', h.NIF, f2.fecha,
 p2.nombre 'PaisNombre', p3.nombre 'ProvinciaNombre',l2.nombre 'LocalidadNombre'
from  fechas f2 , habitacions h2 , hotels h  , reservas r2 , alojamientos a2, pais p2, provincias p3 , localidads l2
where  r2.Hotel_id = f2.Hotel_id
and r2.Hotel_id  = h2.Hotel_id
and r2.Hotel_id  = h.id
and r2.Alojamiento_id =a2.id
and r2.Temporada_id = f2.Temporada_id
and r2.Temporada_id = r2.Temporada_id
and r2.Temporada_id = a2.Temporada_id
and r2.TipoHabitacion_id = h2.TipoHabitacion_id
and r2.TipoHabitacion_id =r2.TipoHabitacion_id
and h2.TipoHabitacion_id =r2.TipoHabitacion_id
and a2.id =r2.Alojamiento_id
and r2.Habitacion_id = h2.id
and f2.id = r2.Fecha_id
and p2.id = h.Pais_id
and p3.id = h.Provincia_id
and l2.id = h.Localidad_id
and p2.id =".$pais." and p3.id=".$provincia." and l2.id=".$localidad ."
and f2.fecha >='".$desde."' and f2.fecha<='".$hasta."'
and reservado='libre'
order by h.id,h2.id");


      return response()->json(['data'=>$reservas],200);
    }
}
