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
        $cantidadHoteles=1000;
        $cantidadPension=400;
        $cantidadTipoHabitacion=3;
        $cantidadHabitaciones=3000;
        //$cantidadTemporadas=3;
        $cantidadFechas=500;
        $cantidadClientes=1000;
        $cantidadTarjetas=1000;
        $cantidadAlojamiento=36;
        $cantidadReservas=50;

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
        DatabaseSeeder::rellenarAlojamiento();
        //factory(Reserva::class,$cantidadReservas)->create();
        DatabaseSeeder::rellenarReservas();
        $cantidadResguardoHotel=Reserva::distinct()->count('Hotel_id');

        factory(ResguardoHotel::class,$cantidadResguardoHotel)->create();
        //factory(Resguardo::class,$cantidadResguardo)->create();

        DB::statement('Insert into ids (nombre,posicion) values ("habitacions",1000000 )');

        */

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
        $fecha->modify('+1 day');
        $temporada_id=DatabaseSeeder::extraerTemporada($fecha,$hotel);
        DB::statement(' Insert into fechas (fecha,Hotel_id,Temporada_id) values ("'.date_format($fecha,'Y-m-d').'",'.$hotel->id.','.$temporada_id.')');
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

  public function rellenarAlojamiento(){
    $pensiones= Pension::All();
    $tipo_habitaciones=TipoHabitacion::All();
    $temporadas=Temporada::All();
    $faker= new Faker\Generator() ;
    foreach ($pensiones as $pension) {
      foreach ($tipo_habitaciones as $tipo_habitacion) {
        foreach ($temporadas as $temporada) {
          // code...
          if($pension->Hotel_id==$temporada->Hotel_id){
            $precio=strval($faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 2000)),
            DB::statement(' Insert into alojamientos (Hotel_id,Pension_id,TipoHabitacion_id,Temporada_id,precio) values ('.$pension->Hotel_id.','.$pension->id.','.$tipo_habitacion->id.','.$temporada->id.','.$precio.')');
          }
        }
      }
    }

    DB::statement(' Insert into ids (nombre,posicion,ides) values ("reserva",1,"regeneracion")');


  }

  public function rellenarReservas(){

  }
}
