<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use  App\User;
use  App\Models\User;
use Illuminate\Support\Facades\Crypt;


class UserController extends Controller{
    
    public function index(){

        //return "Homa Mundo por aqui tambien";
        $users = User::All();
        return response()->json($users, status:200);

    }
}