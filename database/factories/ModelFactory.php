<?php

use App\User;
use App\Pais;
use App\Provincia;
use App\Localidad;
use App\TipoHabitacion;
use App\Pension;
use App\Hotel;
use App\Temporada;
use App\Cliente;
use App\Alojamiento;
use App\Fecha;
use App\Habitacion;
use App\Reserva;
use App\ResguardoHotel;
use App\Resguardo;
use App\Id;
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'verified'=> $verificado= $faker->randomElement([User::USUARIO_VERIFICADO,User::USUARIO_NO_VERIFICADO]),
        'verification_token'=> $verificado== User::USUARIO_VERIFICADO ? null : User::generateVerificationToken(),
        'tipo_usuario' => $faker->randomElement([User::USUARIO_CLIENTE,User::USUARIO_EDITOR,User::USUARIO_ADMINISTRADOR]),
    ];
});

$factory->define(App\Pais::class, function (Faker\Generator $faker) {

    return [
      'nombre' => $faker->country,
    ];
});

$factory->define(App\Provincia::class, function (Faker\Generator $faker) {

    $pais= Pais::All()->random();
    $posicion=Id::where('nombre','provincias')->where('ides',$pais->id."")->first();
    $pos=$posicion->posicion;
    $posicion->posicion+=1;
    $posicion->save();
    return [
      'id'=> $pos,
      'nombre' => $faker->state,
      'Pais_id'=> $pais->id,
    ];
});
$factory->define(App\Localidad::class, function (Faker\Generator $faker) {

    $provincia= Provincia::All()->random();
    $posicion=Id::where('nombre','localidads')->where('ides',$provincia->Pais_id."-".$provincia->id."")->first();
    $pos=$posicion->posicion;
    $posicion->posicion+=1;
    $posicion->save();
    return [
      'id'=> $pos,
      'nombre' => $faker->city,
      'Pais_id'=> $provincia->Pais_id,
      'Provincia_id'=> $provincia->id,
    ];
});

$factory->define(App\Hotel::class, function (Faker\Generator $faker) {
    $localidad= Localidad::All()->random();
    $values="";
    for($i=0;$i<8;$i++){
      $aux=$faker->randomDigit;
      $values=$values .  strval($aux);
    }

    return [
      'nombre' => $faker->name,
      'NIF' => $values,
      'Provincia_id'=> $localidad->Provincia_id,
      'Localidad_id'=> $localidad->id,
      'Pais_id'=>$localidad->Pais_id,
    ];
});

$factory->define(App\Pension::class, function (Faker\Generator $faker) {
    $hotel=Hotel::All()->random();
    $posicion=Id::where('nombre','pensions')->where('ides',$hotel->id."")->first();
    $pos=$posicion->posicion;
    $posicion->posicion+=1;
    $posicion->save();
    return [
       'id'=>$pos,
       'tipo'=> $faker->randomElement([Pension::PENSION_DESAYUNO,Pension::PENSION_COMPLETA,Pension::PENSION_COMPLETA_CENA]),
       'Hotel_id'=>$hotel->id,
    ];
});

$factory->define(App\TipoHabitacion::class, function (Faker\Generator $faker) {
    return [
       'tipo'=> $faker->unique()->randomElement([TipoHabitacion::HABITACION_SIMPLE,TipoHabitacion::HABITACION_DOBLE,TipoHabitacion::HABITACION_MATRIMONIAL]),
    ];
});


$factory->define(App\Habitacion::class, function (Faker\Generator $faker) {
    $hotel= Hotel::All()->random();
    $tipo= TipoHabitacion::All()->random();
    $posicion=Id::where('nombre','habitacions')->where('ides',$hotel->id."")->first();
    $pos=$posicion->posicion;
    $posicion->posicion+=1;
    $posicion->save();
    $repetido=true;
    $numero=0;
    $numero=$faker->numberBetween($min=1,$max=200);
    $ceros="";
    if($numero<10) $ceros="00";
    if(10<$numero && $numero<100) $ceros="0";
    $numero=$pos.$ceros.$numero;
    return [
       'id'=> $pos,
       'numero'=> $numero,
       'Hotel_id'=> $hotel->id,
       'TipoHabitacion_id'=> $tipo->id,
     ];
});
/*
$factory->define(App\Temporada::class, function (Faker\Generator $faker) {

    return [
       'tipo'=> $faker->unique()->randomElement([Temporada::TEMPORADA_BAJA,Temporada::TEMPORADA_MEDIA,Temporada::TEMPORADA_ALTA]),
    ];
});*/
/*
$factory->define(App\Fecha::class, function (Faker\Generator $faker) {
    $temporada= Temporada::All()->random();
    return [
       'fecha'=> date('Y-m-d', strtotime('now +' . $this->faker->unique()->numberBetween(1, 2000) . ' days')),
       'Temporada_id'=> $temporada->id,

    ];
});*/
$factory->define(App\Cliente::class, function (Faker\Generator $faker) {

    $values="";
    for($i=0;$i<8;$i++){
      $aux=$faker->randomDigit;
      $values=$values .  strval($aux);
    }

    return [
       'NIF'=> $values,
       'nombre'=> $faker->name,
       'email'=> $faker->unique()->email,
       'telefono'=> $faker->phoneNumber,

    ];
});
$factory->define(App\Tarjeta::class, function (Faker\Generator $faker) {
    $cliente=Cliente::All()->random();

    $posicion=Id::where('nombre','habitacions')->where('ides',$cliente->id."")->first();
    $pos=$posicion->posicion;
    $posicion->posicion+=1;
    $posicion->save();
    return [
       'id'=> $pos,
       'numero'=> $faker->creditCardNumber,
       'Cliente_id'=> $cliente->id,

    ];
});
/*
$factory->define(App\Alojamiento::class, function (Faker\Generator $faker) {


    $pensiones= Pension::All();
    $tipo_habitaciones=TipoHabitacion::All();
    $temporadas=Temporada::All();
    $combinaciones=array();
    foreach ($pensiones as $pension) {
      foreach ($tipo_habitaciones as $tipo_habitacion) {
        foreach ($temporadas as $temporada) {
          // code...

          $combinaciones[]=array($pension->id,$tipo_habitacion->id,$temporada->id);
        }
      }
    }
    $elegido=$faker->unique()->randomElement($combinaciones);


    return [

       'Pension_id'=> $elegido[0],
       'TipoHabitacion_id'=> $elegido[1],
       'Temporada_id'=> $elegido[2],
        'precio'=> strval($faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 2000)),
    ];
});*/

$factory->define(App\ResguardoHotel::class, function (Faker\Generator $faker) {

      $reservas=Reserva::All();
      foreach ($reservas as $reserva) {        // code...
          $combinaciones[]=$reserva->Hotel_id;
      }
      $hotel= $faker->unique()->randomElement($combinaciones);
    return [
       'porcentaje'=> $faker->numberBetween($min = 1, $max = 50),
       'Hotel_id'=> $hotel,

    ];
});
$factory->define(App\Resguardo::class, function (Faker\Generator $faker) {

  $precios=DB::select('select r2.id,Fecha_id,p.Pension_id ,p.TipoHabitacion_id,Habitacion_id,
p.Hotel_id, p.Temporada_id, precio
from  reservas r2,precios p
where r2.Hotel_id =p.Hotel_id
and r2.Pension_id =p.Pension_id
and r2.Temporada_id =p.Temporada_id
and r2.TipoHabitacion_id =p.TipoHabitacion_id');

    $precio=$faker->unique()->randomElement($precios);

    $resguardoHotel=ResguardoHotel::where('Hotel_id',$precio->Hotel_id)->first();
    $pagado="";
    if($resguardoHotel!=null){
      $pagado=(floatval($faker->numberBetween($min = $resguardoHotel->porcentaje, $max = 100))/100)*$precio->precio;
    }else{
      $pagado=$precio->precio;
    }

    $estado=$faker->randomElement([Resguardo::RESERVA_ACEPTADA,Resguardo::RESERVA_FALLIDA]);

    if($estado==Resguardo::RESERVA_ACEPTADA){
      DB::statement('UPDATE reservas SET reservado="'.Reserva::RESERVADO.'" WHERE id='.$precio->id);
    }

    $cliente=Cliente::All()->random();
    return [
       'precio'=>$precio->precio,
       'pagado'=> $pagado,
       'Fecha_id'=>$precio->Fecha_id,
       'Habitacion_id'=> $precio->Habitacion_id,
       'Hotel_id'=> $precio->Hotel_id,
       'Cliente_id'=>$cliente->id,
       'Estado'=> $estado,
    ];
});
