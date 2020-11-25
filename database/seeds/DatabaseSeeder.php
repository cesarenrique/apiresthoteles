<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Pais;
use App\Provincia;
use App\Localidad;
use App\Hotel;
use App\TipoHabitacion;
use App\Pension;
use App\Habitacion;
use App\Temporada;
use App\Fecha;
use App\Cliente;
use App\Tarjeta;
use App\Alojamiento;
use App\Reserva;
use App\ResguardoHotel;
use App\Resguardo;
use App\Id;
use Faker\Generator;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        DatabaseSeeder::rellenarID();

        User::truncate();
        Pais::truncate();
        Provincia::truncate();
        Localidad::truncate();
        Pension::truncate();
        Hotel::truncate();
        TipoHabitacion::truncate();
        Habitacion::truncate();
        Temporada::truncate();
        Fecha::truncate();
        Cliente::truncate();
        Tarjeta::truncate();
        Alojamiento::truncate();
        Reserva::truncate();
        ResguardoHotel::truncate();
        Resguardo::truncate();


        $cantidadUsuarios=200;
        $cantidadPaises=20;
        $cantidadProvincias=100;
        $cantidadLocalidades=300;
        $cantidadHoteles=100;
        $cantidadPension=100;
        $cantidadTipoHabitacion=3;
        $cantidadHabitaciones=1000;
        //$cantidadTemporadas=3;
        //$cantidadFechas=500;
        $cantidadClientes=500;
        $cantidadTarjetas=500;
        //$cantidadAlojamiento=36;
        //$cantidadReservas=50;

        $cantidadResguardo=50;

        factory(User::class,$cantidadUsuarios)->create();

        factory(Pais::class,$cantidadPaises)->create(); DatabaseSeeder::rellenarIdPaises();
        factory(Provincia::class,$cantidadProvincias)->create(); DatabaseSeeder::rellenarIdProvincias();
        factory(Localidad::class,$cantidadLocalidades)->create();
        factory(Hotel::class,$cantidadHoteles)->create();DatabaseSeeder::rellenarIdHoteles();
        factory(Pension::class,$cantidadPension)->create();
        factory(TipoHabitacion::class,$cantidadTipoHabitacion)->create();
        factory(Habitacion::class,$cantidadHabitaciones)->create();
        //factory(Temporada::class,$cantidadTemporadas)->create();
        DatabaseSeeder::rellenarTemporada();
        //factory(Fecha::class,$cantidadFechas)->create();
        DatabaseSeeder::rellenarFecha();
        factory(Cliente::class,$cantidadClientes)->create();DatabaseSeeder::rellenarIdCliente();
        factory(Tarjeta::class,$cantidadTarjetas)->create();
        //factory(Alojamiento::class,$cantidadAlojamiento)->create();
        $faker= Faker\Factory::create();
        DatabaseSeeder::rellenarAlojamiento($faker);
        //factory(Reserva::class,$cantidadReservas)->create();
        DatabaseSeeder::rellenarReservas();
        //$cantidadResguardoHotel=Reserva::distinct()->count('Hotel_id');
        DatabaseSeeder::rellenarResguadosHotel($faker);
        //factory(ResguardoHotel::class,$cantidadResguardoHotel)->create();
        DatabaseSeeder::rellenarResguados($faker);
        //factory(Resguardo::class,$cantidadResguardo)->create();

        //DB::statement('Insert into ids (nombre,posicion) values ("habitacions",1000000 )');


        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
        // $this->call(UsersTableSeeder::class);

    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function rellenarID(){
        Id::truncate();
        //DB::statement(' Insert into ids (nombre,posicion,ides) values ("provincias",1)');
        //DB::statement(' Insert into ids (nombre,posicion) values ("localidads",1)');
        //DB::statement(' Insert into ids (nombre,posicion) values ("habitacions",1)');
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function rellenarIdPaises(){
        $paises=Pais::All();

        foreach ($paises as $pais) {

          DB::statement(' Insert into ids (nombre,posicion,ides) values ("provincias",1,"'.$pais->id.'")');
        }
    }

    public function rellenarIdProvincias(){
        $provincias=Provincia::All();

        foreach ($provincias as $provincia) {

          DB::statement(' Insert into ids (nombre,posicion,ides) values ("localidads",1,"'.$provincia->Pais_id.'-'.$provincia->id.'")');
        }
    }

  public function rellenarIdHoteles(){
    $hoteles=Hotel::All();

    foreach ($hoteles as $hotel) {

      DB::statement(' Insert into pensions (id,tipo,Hotel_id) values (1,"'.Pension::SOLO_ALOJAMIENTO.'",'.$hotel->id.')');
      DB::statement(' Insert into ids (nombre,posicion,ides) values ("pensions",2,"'.$hotel->id.'")');
      DB::statement(' Insert into ids (nombre,posicion,ides) values ("habitacions",1,"'.$hotel->id.'")');

    }

  }

  public function rellenarTemporada(){

    $hoteles=Hotel::All();

    foreach ($hoteles as $hotel) {
      DB::statement(' Insert into temporadas (id,tipo,dia_desde,mes_desde,dia_hasta,mes_hasta,mismo,Hotel_id) values (1,"'.Temporada::TEMPORADA_ALTA.'",15,6,31,8,0,'.$hotel->id.')');
      DB::statement(' Insert into temporadas (id,tipo,dia_desde,mes_desde,dia_hasta,mes_hasta,mismo,Hotel_id) values (2,"'.Temporada::TEMPORADA_MEDIA.'",1,9,15,1,1,'.$hotel->id.')');
      DB::statement(' Insert into temporadas (id,tipo,dia_desde,mes_desde,dia_hasta,mes_hasta,mismo,Hotel_id) values (3,"'.Temporada::TEMPORADA_BAJA.'",16,1,14,6,0,'.$hotel->id.')');
      DB::statement(' Insert into ids (nombre,posicion,ides) values ("temporadas",4,"'.$hotel->id.'")');
    }
  }

  public function rellenarFecha(){
    $hoteles=Hotel::All();

    foreach ($hoteles as $hotel) {

      $start_date = '2019-01-01';
      $fecha = DateTime::createFromFormat('Y-m-d',$start_date);
      for($i=0;$i<20;$i++){

        $temporada_id=DatabaseSeeder::extraerTemporada($fecha,$hotel);
        DB::statement(' Insert into fechas (fecha,Hotel_id,Temporada_id) values ("'.date_format($fecha,'Y-m-d').'",'.$hotel->id.','.$temporada_id.')');
        $fecha->modify('+1 day');
      }
    }


  }
  public function extraerTemporada($fecha,$hotel){
      $anio=$fecha->format('Y');
      $temporadas=Temporada::where('Hotel_id',$hotel->id)->get();
      $temporada_baja=Temporada::where('tipo',Temporada::TEMPORADA_BAJA)->first();
      $temporada_id=$temporada_baja->id;
      foreach ($temporadas as $temporada) {
        if($temporada->mismo==0){
            $fecha_desde=date("Y-m-d",strtotime($anio.'-'.$temporada->mes_desde.'-'.$temporada->dia_desde));
            $fecha_hasta=date("Y-m-d",strtotime($anio.'-'.$temporada->mes_hasta.'-'.$temporada->dia_hasta));
            if($fecha_desde<=$fecha && $fecha<=$fecha_hasta){
              $temporada_id=$temporada->id;
            }
        }else{
          $anio2=intval($anio)+1;
          $fecha_desde=date("Y-m-d",strtotime($anio.'-'.$temporada->mes_desde.'-'.$temporada->dia_desde));
          $fecha_hasta=date("Y-m-d",strtotime($anio2.'-'.$temporada->mes_hasta.'-'.$temporada->dia_hasta));
          if($fecha_desde<=$fecha && $fecha<=$fecha_hasta){
            $temporada_id=$temporada->id;
          }
        }
      }
      return $temporada_id;
  }

  public function rellenarIdCliente(){
    $clientes=Cliente::All();

    foreach ($clientes as $cliente) {

        DB::statement(' Insert into ids (nombre,posicion,ides) values ("clientes",1,"'.$cliente->id.'")');
    }

  }

  public function rellenarAlojamiento(Faker\Generator $faker){


    $alojamientos=DB::select("select p.id 'Pension_id',th.id 'TipoHabitacion_id',t.id 'Temporada_id', p.Hotel_id
        from pensions p, tipo_habitacions th, temporadas t
          where p.Hotel_id =t.Hotel_id ");



    foreach ($alojamientos as $alojamiento) {
        $existe=DB::select("select * from alojamientos where Pension_id=".$alojamiento->Pension_id." and TipoHabitacion_id=".$alojamiento->TipoHabitacion_id." and Temporada_id=".$alojamiento->Temporada_id." and Hotel_id=".$alojamiento->Hotel_id);
        if($existe==null){
          $precio=strval($faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 2000));
          DB::statement(' Insert into alojamientos (Hotel_id,Pension_id,TipoHabitacion_id,Temporada_id,precio) values ('.$alojamiento->Hotel_id.','.$alojamiento->Pension_id.','.$alojamiento->TipoHabitacion_id.','.$alojamiento->Temporada_id.','.$precio.')');
        }
    }




  }

  public function rellenarReservas(){

    $reservas=DB::select("select f2.id 'Fecha_id', a.Pension_id, a.TipoHabitacion_id, a.Hotel_id, a.Temporada_id, a.id 'Alojamiento_id', h2.id 'Habitacion_id'
from alojamientos a, fechas f2 , habitacions h2
where  a.Hotel_id = f2.Hotel_id
and a.Hotel_id  = h2.Hotel_id
and a.Temporada_id = f2.Temporada_id
and a.TipoHabitacion_id = h2.TipoHabitacion_id  ");


    foreach ($reservas as $reserva) {
          $existe=DB::select("select * from reservas where Fecha_id=".$reserva->Fecha_id. " and Alojamiento_id=".$reserva->Alojamiento_id." and Habitacion_id=".$reserva->Habitacion_id. " and Hotel_id=".$reserva->Hotel_id);
          if($existe==null){
            DB::statement(' Insert into reservas (Hotel_id,Pension_id,TipoHabitacion_id,Habitacion_id,Temporada_id,Fecha_id,Alojamiento_id) values ('.$reserva->Hotel_id.','.$reserva->Pension_id.','.$reserva->TipoHabitacion_id.','.$reserva->Habitacion_id.','.$reserva->Temporada_id.','.$reserva->Fecha_id.','.$reserva->Alojamiento_id.')');
          }else{
            echo "error2";
          }

    }
  }

  public function rellenarResguadosHotel(Faker\Generator $faker){
    $reservas=DB::select('select  DISTINCT (r2.Hotel_id) from  reservas r2');

    foreach ($reservas as $reserva) {
        $porcentaje= $faker->numberBetween($min = 1, $max = 50);
          DB::statement(' Insert into resguardo_hotels (Hotel_id,porcentaje) values ('.$reserva->Hotel_id.','.$porcentaje.')');
    }
  }

  public function rellenarResguados(Faker\Generator $faker){
    $precios=DB::select('select  r2.id,Fecha_id,a.Pension_id ,a.TipoHabitacion_id,Habitacion_id, a.Hotel_id, a.Temporada_id,precio, a.id "Alojamiento_id"
from  reservas r2,alojamientos a
where r2.Hotel_id =a.Hotel_id
and r2.Pension_id =a.Pension_id
and r2.Temporada_id =a.Temporada_id
and r2.TipoHabitacion_id =a.TipoHabitacion_id');

    foreach ($precios as $precio) {
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
      DB::statement(' Insert into resguardos (Hotel_id,Habitacion_id,Fecha_id,Alojamiento_id,pagado,precio,estado) values ('.$precio->Hotel_id.','.$precio->Habitacion_id.','.$precio->Fecha_id.','.$precio->Alojamiento_id.','.$pagado.','.$precio->precio.',"'.$estado.'")');
    }

  }
}
