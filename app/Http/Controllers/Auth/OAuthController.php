<?php
namespace App\Http\Controllers\Auth;

use Laravel\Passport\Http\Controllers\AccesTokenController;
use Psr\Http\Message\ServerRequestInterface;

class AuthController extends AccesTokenController
{
    /**
     * Function of AuthController
     */
    use ProvidesConvenienceMethods;
    
    public function login(ServerRequestInterface $request){
        try{
            $tokenResponse = parent::issueToken($request);

            $token = $tokenResponse->getContent();

            $tokenInfo = json_decode($token, true);

            if (array_key_exists('error', $tokenInfo)){

                return response($tokenInfo, 401);

            }

            $request = $request->getParsedBody();

            $username = $request['username'];

            $user     = User::where('email',$email)->first();

            $tokenInfo = collect($tokenInfo);

            $tokenInfo->put('user',$user);


            return $tokenInfo;
            
        } catch (ModelNotFoundException $e){
            //email not found
            //return error message
            return response(["mensaje"=>"Usuario no encontrado"], 500);
        
        } catch (OAuthServerException $e){
            //password no correct
            //return error message
            return response(["mensaje"=>"El usuario uso credenciales no correctas,', 6, 'invalid_credentials"], 500);
        
        } catch (Exception $e){
            //return error message
            return response(["mensaje"=>"Internal server error"], 500);

        }
    }



    }
    