<?php namespace App\Http\Controllers;

use App\User, Authorizer;
use Input, Response, Request,  Validator;
use Illuminate\Http\Response as HttpResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class PerfilController extends Controller {


	public function obtenerMiPerfil(){
		
		 try{
			$usuario_id = Authorizer::getResourceOwnerId();
			$usuario = User::find($usuario_id);
			
			if($usuario){
				//$usuario->avatar = "https://pbs.twimg.com/profile_images/3308620305/06a2dd8497f4b8b140d1307cb9c2147c_400x400.jpeg";
				return Response::json(['data'=>$usuario],200);
			} else {
				return Response::json(['data'=>"No existe el usuario"],409);
			}
			
		} catch (Exception $e){
			return Response::json(['error'=>$e->getMessage()],500);
		}
		
	}
	
	public function obtenerMiPerfilJWT(){
		
		 try{
			
			$token = JWTAuth::getToken();
       		$usuario = JWTAuth::toUser($token);
			
			if($usuario){
				//$usuario->avatar = "https://pbs.twimg.com/profile_images/3308620305/06a2dd8497f4b8b140d1307cb9c2147c_400x400.jpeg";
				return Response::json(['data'=>$usuario],200);
			} else {
				return Response::json(['data'=>"No existe el usuario"],409);
			}
			
		} catch (Exception $e){
			return Response::json(['error'=>$e->getMessage()],500);
		}
		
	}
	
}