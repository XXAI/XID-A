<?php namespace App\Http\Controllers;

use App\User;
use JWTAuth;
use Input, Response, Request,  Validator;
use Illuminate\Http\Response as HttpResponse;

class UserController extends Controller {


	/**
	 * Registra un usuario
	 *
	 * @return Response
	 */
	
	
	public function signup(){
		$mensajes = [
			'required' => "required",
			'email' => "email",
			'accepted' => "accepted",
			'confirmed' => "confirmed",
			'unique' => "unique"
		];
		$reglas = [
				'nombre'				=> 'required',	
				'apellido_paterno'		=> 'required',
				'email'					=> 'required|email|unique:users',
				'password'				=> 'required|confirmed',
				//'p'	=> 'required|same:password',
				'apellido_paterno'		=> 'required',
				'g_recaptcha_response'	=> 'required',
				'acepto'				=> 'accepted',
				'informacion_veridica'	=> 'accepted',
				'curp'					=> 'unique:users',
				'rfc'					=> 'unique:users',
				'clave_elector'			=> 'unique:users'
				
			];
		$v = Validator::make(Input::all(), $reglas, $mensajes);
	
	    if ($v->fails()) {
			return Response::json(['error' => $v->errors()], HttpResponse::HTTP_CONFLICT);
	    }
	
	   try {
		   // Comprobamos el recaptcha
		   $post_request = 'secret='.env('RECAPTCHA_SECRET')
                    .'&remoteip='.Request::ip()
                    .'&response='.Input::get('g_recaptcha_response'); 
		   	$ch = curl_init();
	        $header[]         = 'Content-Type: application/x-www-form-urlencoded';
	        curl_setopt($ch, CURLOPT_HTTPHEADER,     $header);
	        curl_setopt($ch, CURLOPT_URL, env('RECAPTCHA_SITE_VERIFY'));
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_request);
	         
	        // Execute & get variables
	        $api_response = json_decode(curl_exec($ch)); 
	        $curlError = curl_error($ch);
	        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	        
	        if($curlError){ 
	        	 throw new Exception("Hubo un problema al intentar hacer la autenticacion. cURL problem: $curlError");
	        }
	        
	        if($http_code != 200){
	            return Response::json(['error'=>$api_response->error],$http_code);
	        }  
			
			/*
			if(!$api_response->success){
				return Response::json(['error' => ['g_recaptcha_response' => 'invalid']], HttpResponse::HTTP_CONFLICT);
			}*/
			
			$datos = Input::all();
			$user = User::create($datos);
			
			
			
	   } catch (Exception $e) {
		   return Response::json(['error' => $e->getMessage()], HttpResponse::HTTP_CONFLICT);
	   }
	   
	   $token = JWTAuth::fromUser($user);
	
	   return Response::json(compact('token'));
	}
	
}