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
        return apiOutPut(123,'',[]);
        $verifyData = $this->verify($request,[
            'title' => 'required'
        ],[],[]);

        dd(123432);
    }
}
