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
use App\Precios;
use App\ResguardoHotel;
use App\Resguardo;
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

    return [
      'id'=> $faker->unique()->randomNumber($nbDigits = 3, $strict = false),
      'nombre' => $faker->state,
      'Pais_id'=> $pais->id,
    ];
});
$factory->define(App\Localidad::class, function (Faker\Generator $faker) {

    $provincia= Provincia::All()->random();
    $pais= $provincia->Pais_id;
    return [
      'id'=> $faker->unique()->randomNumber($nbDigits = 6, $strict = false),
      'nombre' => $faker->city,
      'Pais_id'=> $pais,
      'Provincia_id'=> $provincia->id,
    ];
});
$factory->define(App\Pension::class, function (Faker\Generator $faker) {
    $hotel=Hotel::All()->random();
    return [
       'tipo'=> $faker->unique()->randomElement([Pension::SOLO_ALOJAMIENTO,Pension::PENSION_DESAYUNO,Pension::PENSION_COMPLETA,Pension::PENSION_COMPLETA_CENA]),
       'Hotel_id'=>$hotel->id,
    ];
});
$factory->define(App\Hotel::class, function (Faker\Generator $faker) {
    $localidad= Localidad::All()->random();
    $provincia= $localidad->Provincia_id;
    $values="";
    for($i=0;$i<8;$i++){
      $aux=$faker->randomDigit;
      $values=$values .  strval($aux);
    }
    $pais= $localidad->Pais_id;
    return [
      'nombre' => $faker->name,
      'NIF' => $values,
      'Provincia_id'=> $provincia,
      'Localidad_id'=> $localidad->id,
      'Pais_id'=>$pais,
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
    return [
       'id'=> $faker->unique()->randomNumber($nbDigits = 6, $strict = false),
       'numero'=> strval($faker->unique()->numberBetween($min=1,$max=9000)),
       'Hotel_id'=> $hotel->id,
       'TipoHabitacion_id'=> $tipo->id,
     ];
});
$factory->define(App\Temporada::class, function (Faker\Generator $faker) {

    return [
       'tipo'=> $faker->unique()->randomElement([Temporada::TEMPORADA_BAJA,Temporada::TEMPORADA_MEDIA,Temporada::TEMPORADA_ALTA]),
    ];
});
$factory->define(App\Fecha::class, function (Faker\Generator $faker) {
    $temporada= Temporada::All()->random();
    return [
       'fecha'=> date('Y-m-d', strtotime('now +' . $this->faker->unique()->numberBetween(1, 2000) . ' days')),
       'Temporada_id'=> $temporada->id,

    ];
});
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
    return [
       'id'=> $faker->unique()->randomNumber($nbDigits = 7, $strict = false),
       'numero'=> $faker->creditCardNumber,
       'Cliente_id'=> $cliente->id,

    ];
});
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

    ];
});

$factory->define(App\Precios::class, function (Faker\Generator $faker) {

    $habitaciones=DB::select('SELECT * FROM habitacions ORDER BY id asc LIMIT 20');
    $alojamientos= Alojamiento::where('Pension_id','>',0)->get();
    $combinaciones=array();
    foreach ($alojamientos as $alojamiento) {
      foreach ($habitaciones as $habitacion) {

          // code...

          $combinaciones[]=array($alojamiento,$habitacion->Hotel_id);

      }
    }
    $elegido=$faker->unique()->randomElement($combinaciones);

    return [
       'precio'=> strval($faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 2000)),
       'Pension_id'=> $elegido[0]->Pension_id,
       'TipoHabitacion_id'=> $elegido[0]->TipoHabitacion_id,
       'Temporada_id'=>$elegido[0]->Temporada_id,
       'Hotel_id'=> $elegido[1],
    ];
});
$factory->define(App\Reserva::class, function (Faker\Generator $faker) {

    $fechas= Fecha::All();
    $habitaciones=DB::select('SELECT * FROM habitacions ORDER BY id asc LIMIT 20');
    $precios=Precios::All();

    $combinaciones=array();
    foreach ($fechas as $fecha) {
      foreach ($habitaciones as $habitacion) {        // code...
        foreach ($precios as $precio) {
          // code...
          if($habitacion->TipoHabitacion_id==$precio->TipoHabitacion_id &&
               $habitacion->Hotel_id==$precio->Hotel_id &&
                $fecha->Temporada_id==$precio->Temporada_id){
            $combinaciones[]=array($fecha,$habitacion,$precio->Pension_id);
          }
        }
      }
    }
    $elegido=$faker->unique()->randomElement($combinaciones);

    $cliente=Cliente::All()->random();

    return [
       'Fecha_id'=>$elegido[0]->id,
       'Habitacion_id'=> $elegido[1]->id,
       'Hotel_id'=> $elegido[1]->Hotel_id,
       'Pension_id'=>$elegido[2],
       'TipoHabitacion_id'=>$elegido[1]->TipoHabitacion_id,
       'Cliente_id'=>$cliente->id,
       'Temporada_id'=>$elegido[0]->Temporada_id,

    ];
});
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

  $precios=DB::select('select Fecha_id,p.Pension_id ,p.TipoHabitacion_id,Habitacion_id,
p.Hotel_id, Cliente_id, p.Temporada_id, precio
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
    return [
       'precio'=>$precio->precio,
       'pagado'=> $pagado,
       'Fecha_id'=>$precio->Fecha_id,
       'Habitacion_id'=> $precio->Habitacion_id,
       'Hotel_id'=> $precio->Hotel_id,
       'Estado'=> Resguardo::RESERVA_ACEPTADA,
    ];
});
