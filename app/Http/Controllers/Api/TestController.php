<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test(Request $request){
        $data = verify($request,[
            'phone' => 'required',
        ],[],[]);

        dd($data);
    }
}
