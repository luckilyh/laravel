<?php

namespace App\Http\Controllers\Api;

use App\Models\Test;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TestController extends Controller
{
    public function test(Request $request){
        $verify = verify($request,[
            'phone' => 'required',
        ],[],[]);

        if (!is_array($verify)){
            return $verify;
        }

        dd($verify);
    }
}
