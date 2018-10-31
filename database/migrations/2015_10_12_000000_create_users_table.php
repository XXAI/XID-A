<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('nombre');
			$table->string('apellido_paterno');
			$table->string('apellido_materno')->nullable();
			$table->string('email')->unique();			
			$table->string('password', 60);
			$table->string('calle')->nullable();
			$table->string('numero_interior')->nullable();
			$table->string('numero_exterior')->nullable();
			$table->string('colonia')->nullable();
			$table->string('codigo_postal')->nullable();
			$table->integer('pais_id')->unsigned()->nullable();
			$table->integer('estado_id')->unsigned()->nullable();
			$table->integer('municipio_id')->unsigned()->nullable();
			$table->char('sexo', 1)->nullable();
			$table->char('nacionalidad')->nullable();
			$table->string('curp', 18)->nullable();
			$table->string('rfc', 13)->nullable();
			$table->string('clave_elector', 18)->nullable();
			$table->rememberToken();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
