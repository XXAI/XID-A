<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Illuminate\Http\Response as HttpResponse;


Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');


Route::post('/signup', 'UserController@signup');
Route::get('/signup', 'UserController@signup');


Route::post('/signin', function () {

   $credentials = Input::only('email', 'password');

   if ( ! $token = JWTAuth::attempt($credentials)) {
       return Response::json(false, HttpResponse::HTTP_UNAUTHORIZED);
   }

   return Response::json(compact('token'));
});


Route::post('/refresh-token', function () {
	
	
	if ($token = JWTAuth::getToken()){
		if ( $newToken = JWTAuth::refresh($token)) {
			return Response::json(false, HttpResponse::HTTP_UNAUTHORIZED);
		}

   		return Response::json(compact('token'));
	} else {
		return Response::json(false, HttpResponse::HTTP_UNAUTHORIZED);
	}
	
   
});


/*

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

*/


// Api pública

Route::group([ 'prefix' => 'v1', 'before'=>'oauth'], function () {
   Route::get('/perfil','PerfilController@obtenerMiPerfil');
   Route::get('/perfil/{email}','PerfilController@obtenerPerfilPublico');
});

//Api JWT
Route::group([ 'prefix' => 'jwt', 'before'=>'jwt-auth'], function () {
	Route::group([ 'prefix' => 'v1'], function () {
		Route::get('/perfil','PerfilController@obtenerMiPerfilJWT');
	});
});


// OAuth
Route::group([ 'prefix' => 'oauth'], function () {
	Route::post('access_token', function() {
	    return Response::json(Authorizer::issueAccessToken());
	});
	
	Route::post('vinculacion', ['before'=>'oauth',function() {
	    try{
			$vinculo = new App\UserClient;
			$vinculo->user_id = Authorizer::getResourceOwnerId();
			$vinculo->client_id = Authorizer::getClientId();
			
			$existe_vinculo = App\UserClient::where('client_id',$vinculo->client_id )->where('user_id',$vinculo->user_id)->first();
			
			if($existe_vinculo){
				return Response::json(['data'=>"Vínculo creado con éxito"],200);
			}
			
			if(!$vinculo->save()){
				throw new Exception("No se pudo crear el vínculo");
			}
			return Response::json(['data'=>"Vínculo creado con éxito"],200);
			
		} catch (Exception $e){
			return Response::json(['error'=>$e->getMessage()],500);
		}
	}]);
	
	Route::get('check/{access_token}', function($access_token){
		try{
			if(Authorizer::validateAccessToken(true, $access_token)==""){
				return Response::json(['data'=>'Token válido :)'],200);
			}else{
				throw new Exception('Token no válido');
			}
		}catch(Exception $e){
			return Response::json(['error'=>$e->getMessage()],401);
		}
	});
	
	Route::get('check/{access_token}/{email}', function($access_token,$email){
        try{
                if(Authorizer::validateAccessToken(true, $access_token)==""){
                        return Response::json(['data'=>'Token vlido :)'],200);
                }else{
                        throw new Exception('Token no vlido');
                }
        }catch(Exception $e){
                return Response::json(['error'=>$e->getMessage()],401);
        }
	});
});