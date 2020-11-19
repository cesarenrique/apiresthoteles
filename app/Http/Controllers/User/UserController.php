<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios= User::All();

        return response()->json(['data' => $usuarios],200);
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
            'name'=>'required',
            'email'=> 'required|email|unique:users',
            'password'=> 'required|min:6|confirmed'
        ];

        $this->validate($request,$reglas);

        $campos= $request->all();
        $campos['password']=bcrypt($request->password);
        $campos['verified']=User::USUARIO_NO_VERIFICADO;
        $campos['verification_token']= User::generateVerificationToken();
        $campos['tipo_usuario']=User::USUARIO_CLIENTE;


        $usuario = User::create($campos);

        return response()->json(['data'=>$usuario],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usuario=User::findOrFail($id);

        return response()->json(['data' => $usuario],200);
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
      $user= User::findOrFail($id);

      $reglas= [
          'email'=> 'email|unique:users,email,'.$user->id,
          'password'=> 'min:6|confirmed',
          'tipo_usuario'=> 'in:'. User::USUARIO_ADMINISTRADOR.','.User::USUARIO_EDITOR.','.User::USUARIO_CLIENTE.','.User::USUARIO_NO_REGISTRADO
      ];

      $this->validate($request,$reglas);

      if($request->has('name')){
        $user->name=$request->name;
      }

      if($request->has('email') && $user->email != $request->email){

        $user->verified=User::USUARIO_NO_VERIFICADO;
        $user->verification_token=User::generateVerificationToken();
        $user->email=$request->email;
      }

      if($request->has('password')){
        $user->password=bcrypt($request->password);
      }
      if($request->has('tipo_usuario')){
        if(!$user->esVerificado() && ($request->tipo_usuario==User::USUARIO_ADMINISTRADOR || $request->tipo_usuario==User::USUARIO_EDITOR)){
          return response()->json(['error'=>'Unicamente los usuarios verificados pueden cambiar  su valor a esAdministrador o editor','code'=>409],409);
        }
        $user->tipo_usuario=$request->tipo_usuario;
      }

      if($user->isDirty()){
        return response()->json(['error'=>'Se debe especificar al menos un valor diferente para actualizar','code'=>422],422);

      }
      $user->save();

      return response()->json(['data' => $user],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user= User::findOrFail($id);

        $user->delete();

        return response()->json(['data' => $user],200);
    }
}
