<?php

namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;
 
class UserController extends Controller
{
    public $successStatus = 200;

    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(){ 
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyLaravelApp')->accessToken; 
            $success['userId'] = $user->id;
            return response()->json(['success' => $success], $this->successStatus); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }
 
    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'firstname' => 'required',
            'lastname' => 'required',
            'phone_number' => 'required|numeric|digits:10',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) { 
             return response()->json(['error'=>$validator->errors()], 401);            
        }

        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $user = User::create($input); 
        $success['token'] =  $user->createToken('MyLaravelApp')->accessToken; 
        $success['name'] =  $user->firstname;
         
        return response()->json(['success'=>$success], $this->successStatus); 
    }


    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function updateProfile(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'phone_number' => 'numeric|digits:10',
            'email' => 'email|unique:users',
            'date_of_birth' => 'date_format:Y-m-d|before:today|nullable',
        ]);

        if ($validator->fails()) { 
             return response()->json(['error'=>$validator->errors()], 401);            
        }

        $input = $request->all(); 
        $user = Auth::user(); 
        $user->update([
                        'firstname' => $input['firstname'],
                        'lastname' => $input['lastname'],
                        'phone_number' => $input['phone_number'],
                        'email' => isset($input['email']) ? $input['email'] : $user->email,
                        'date_of_birth' => isset($input['date_of_birth']) ? $input['date_of_birth'] : $user->date_of_birth,
                        'businessname' => isset($input['businessname']) ? $input['businessname'] : $user->businessname,
                        'pay_frequency' => isset($input['pay_frequency']) ? $input['pay_frequency'] : $user->pay_frequency,
                        'business_address_1' => isset($input['business_address_1']) ? $input['business_address_1'] : $user->business_address_1,
                        'business_address_2' => isset($input['business_address_2']) ? $input['business_address_2'] : $user->business_address_2,
                        'city' => isset($input['city']) ? $input['city'] : $user->city,
                        'state' => isset($input['state']) ? $input['state'] : $user->state,
                        'zip' => isset($input['zip']) ? $input['zip'] : $user->zip,
                        'contact_firstname' => isset($input['contact_firstname']) ? $input['contact_firstname'] : $user->contact_firstname,
                        'contact_lastname' => isset($input['contact_lastname']) ? $input['contact_lastname'] : $user->contact_lastname,
                        'contact_mobile' => isset($input['contact_mobile']) ? $input['contact_mobile'] : $user->contact_mobile,
                     ]);

        $success['name'] =  $user->firstname;
         
        return response()->json(['success'=>$success], $this->successStatus); 
    }


    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function updateUserPassword(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) { 
             return response()->json(['error'=>$validator->errors()], 401);            
        }

        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        //$user = Auth::user();
        $user = User::where('email', $input['email'])->first();
        $user->update(['password' => $input['password']]);
        
        $success['name'] =  $user->name;
         
        return response()->json(['success'=>$success], $this->successStatus); 
    }
 
    /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function userDetails() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this->successStatus); 
    }
}
