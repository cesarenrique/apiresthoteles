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
use App\Precios;
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
        Precios::truncate();
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
        $cantidadTemporadas=3;
        $cantidadFechas=500;
        $cantidadClientes=1000;
        $cantidadTarjetas=1000;
        $cantidadAlojamiento=36;
        $cantidadPrecios=50;
        $cantidadReservas=50;

        $cantidadResguardo=50;

        factory(User::class,$cantidadUsuarios)->create();

        factory(Pais::class,$cantidadPaises)->create(); DatabaseSeeder::rellenarIdPaises();
        factory(Provincia::class,$cantidadProvincias)->create(); DatabaseSeeder::rellenarIdProvincias();
        factory(Localidad::class,$cantidadLocalidades)->create();
        factory(Hotel::class,$cantidadHoteles)->create();DatabaseSeeder::rellenarIdHoteles();
        factory(Pension::class,$cantidadPension)->create();
        /*factory(TipoHabitacion::class,$cantidadTipoHabitacion)->create();
        factory(Habitacion::class,$cantidadHabitaciones)->create();
        factory(Temporada::class,$cantidadTemporadas)->create();
        factory(Fecha::class,$cantidadFechas)->create();
        factory(Cliente::class,$cantidadClientes)->create();
        factory(Tarjeta::class,$cantidadTarjetas)->create();
        factory(Alojamiento::class,$cantidadAlojamiento)->create();
        factory(Precios::class,$cantidadPrecios)->create();
        factory(Reserva::class,$cantidadReservas)->create();

        $cantidadResguardoHotel=Reserva::distinct()->count('Hotel_id');

        factory(ResguardoHotel::class,$cantidadResguardoHotel)->create();
        factory(Resguardo::class,$cantidadResguardo)->create();

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
        //DB::statement(' Insert into ids (nombre,posicion) values ("provincias",1)');
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
    }

  }
}
