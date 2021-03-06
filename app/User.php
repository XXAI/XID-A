<?php namespace App;

use Hash;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = 
		[
			'nombre',
			'apellido_paterno',
			'apellido_materno',
			'calle',
			'clave_elector',
			'numero_exterior',
			'numero_interior',
			'colonia',
			'codigo_postal',
			'pais_id',
			'estado_id',
			'municipio_id',
			'nacionalidad',
			'sexo',
			'curp',
			'rfc',
			'clave_elector',
			'email',
			'password'
		];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];
	
	
	/**
	 * Passwords must always be hashed
	 *
	 * @param $password
	 */
	public function setPasswordAttribute($password)
	{
		$this->attributes['password'] = Hash::make($password);
	}

}
