<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function test(){
        $user = User::first();
        return Auth::guard('api')->login($user);
    }
}
