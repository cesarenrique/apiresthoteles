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
        $cantidadPension=4;
        $cantidadHoteles=1000;
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
        factory(Pais::class,$cantidadPaises)->create();
        factory(Provincia::class,$cantidadProvincias)->create();
        factory(Localidad::class,$cantidadLocalidades)->create();
        factory(Hotel::class,$cantidadHoteles)->create();
        factory(Pension::class,$cantidadPension)->create();
        factory(TipoHabitacion::class,$cantidadTipoHabitacion)->create();
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
        // $this->call(UsersTableSeeder::class);

    }
}
