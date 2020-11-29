<?php

namespace App\Http\Controllers\Provincia;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Pais;
use App\Provincia;

class ProvinciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($PaisId)
    {
      $pais=Pais::findOrFail($PaisId);
      $provincias=Provincia::where('Pais_id',$pais->id)->get();

      if($provincias==null){
        return response()->json(['error'=>'No existe esa provincias en ese Pais','code'=>409],409);
      }

      return response()->json(['data' => $provincias],200);
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
    public function show($PaisId,$id)
    {
      $pais=Pais::findOrFail($PaisId);
      $provincia=Provincia::where('Pais_id',$pais->id)->where('id',$id)->first();
      if($provincia==null){
        return response()->json(['error'=>'No existe esa provincias en ese Pais','code'=>409],409);
      }
      return response()->json(['data' => $pais],200);
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
