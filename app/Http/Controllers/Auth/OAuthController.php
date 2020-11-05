<?php

namespace App\Http\Controllers\Auth;

use Laravel\Passport\Http\Controllers\AccessTokenController;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Http\Request;
use Exception;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Passport\Exceptions\OAuthServerException;

class OAuthController extends AccessTokenController
{

    public function login(ServerRequestInterface $request)
    {
        try {
            $tokenResponse = parent::issueToken($request);

            $token = $tokenResponse->getContent();
            $tokenInfo = json_decode($token, true);
            if (array_key_exists('error', $tokenInfo)) {
                return response($tokenInfo, 401);
            }
            $request = $request->getParsedBody();
            $email = $request['username'];
            $user = User::where('email', $email)->first();
            $tokenInfo = collect($tokenInfo);
            $tokenInfo->put('user', $user);
            return $tokenInfo;
        } catch (ModelNotFoundException $e) {
            //email not found
            //return error message
            return response(["mensaje" => "Usuario no encontrado"], 500);
        } catch (OAuthServerException $e) {
            //password no correct
            //return error message
            return response(["mensaje" => "El usuario uso credenciales no correctas,', 6, 'invalid_credentials"], 401);
        } catch (Exception $e) {
            //return error message
            return response(["mensaje" => "Internal server error"], 500);
        }
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
