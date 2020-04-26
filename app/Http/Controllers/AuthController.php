<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CreateUsersTable;
use App\CreateWorkersTable;


class AuthController extends Controller
{
    public function userRegister() 
    {
    }

    public function userLogin($name, $password) 
    {
        $flag1 = false;
        $flag2 = false;

        $dbNames = DB::table('workers')->get('name');
        $dbPasswords = DB::table('users')->get('password');

        if(in_array($name, $dbNames)) {
            $flag1 = true;
        }
        if(in_array($password, $dbPasswords)) {
            $flag2 = true;
        }

        if($flag1 == true && $flag2 == true) {
            return true;
        }
        else {
            showAlert("Incorrect Name or Password");
        }
    }

    public function showAlert($message) 
    {
        echo $message;
    }
}
