<?php

namespace App\Http\Controllers\Localidad;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Pais;
use App\Provincia;
use App\Localidad;

class LocalidadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($PaisId,$ProvinciaId)
    {
      $pais=Pais::findOrFail($PaisId);
      $provincia=Provincia::where('Pais_id',$pais->id)->where('id',$ProvinciaId)->first();
      if($provincia==null){
        return response()->json(['error'=>'No existe esa provincias en ese Pais','code'=>409],409);
      }
      $localidads=Localidad::where('Pais_id',$pais->id)->where('Provincia_id',$provincia->id)->get();
      if($localidads==null){
        return response()->json(['error'=>'No existe esa localidades en ese Provincia','code'=>409],409);
      }
      return response()->json(['data' => $localidads],200);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($PaisId,$ProvinciaId,$id)
    {
      $pais=Pais::findOrFail($PaisId);
      $provincia=Provincia::where('Pais_id',$pais->id)->where('id',$ProvinciaId)->first();
      if($provincia==null){
        return response()->json(['error'=>'No existe esa provincias en ese Pais','code'=>409],409);
      }
      $localidad=Localidad::where('Pais_id',$pais->id)->where('Provincia_id',$provincia->id)->where('id',$id)->first();
      if($localidad==null){
        return response()->json(['error'=>'No existe esa localidad en ese Provincia','code'=>409],409);
      }
      return response()->json(['data' => $localidad],200);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
