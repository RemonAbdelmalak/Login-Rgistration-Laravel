<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class CustomerController extends Controller
{

    public function create(Request $request)
    {
        $validtaion = Validator::make($request->all(),
        [
            'name'=> 'required',
            'email'=>['required'],
            'password'=> 'required',
        ]);
        if($validtaion->fails()){

            return response()->json(['error' => $validtaion->errors()], 401);

        } else {
            $user = Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            if ($user) {
                $token = auth('web')->attempt(['email' => $request['email'], 'password' => $request['password']]);

                return $this->respondWithToken($token);
            }
        }
    }

    public function update(Request $request)
    {
        $validtaion = Validator::make($request->all(),
        [
            'name'=> 'required',
            'email'=>['required'],
            'password'=> 'required'
        ]);
        if($validtaion){
            $Customer = Customer::where('id',auth('web')->user()->id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            if($Customer){
                return response()->json('customer updated', 200);
            }
            else
            {
                return response()->json('customer not found', 404);
            }
         }
         else
         {
            return response()->json($validtaion, 401);
         }
}


    public function login(Request $request)
    {
        $token = auth('web')->attempt(['email' => $request['email'], 'password' => $request['password']]);

        if (!$token ) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function delete()
    {

        $Customer = Customer::where('id',auth('web')->user()->id)->delete();
        if($Customer)
        {
            return response()->json('customer deleted', 200);
        }
        else
        {
            return response()->json('customer not found', 404);
        }

    }


    protected function respondWithToken(string $token)
    {
        return response()->json(
            [
                'state' => 'true',
                'token' => $token,
                'token_type' => 'bearer',
                'user'=>auth('web')->user()
            ]
        );
    }
}
