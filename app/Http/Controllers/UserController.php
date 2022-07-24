<?php

namespace App\Http\Controllers;

use Response;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|unique:users|max:50',
            'email' => 'required|email|unique:users,',
            'address' => 'required',
            'password' => 'required|string|min:6|confirmed',
            'walletBalance' => 'required|integer',
        ]);

        $formFields['password'] = bcrypt($formFields['password']);



        $user =  User::create($formFields);
        // $token = $user->createToken($request->device_name || "ecomApp")->plainTextToken;

        $response = [
            'user' => $user,
            // 'token' => $token
        ];
        return response($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $formFields = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required',
            'password' => 'required|string|min:6|confirmed',
            'walletBalance' => 'required|integer',
        ]);

        $formFields['password'] = bcrypt($formFields['password']);

        if ($user->email != $request['email']) {
            $formFields['email'] = $request->validate([
                'email' => 'required|email|unique:users'
            ])['email'];
        } else {
            $formFields['email'] = $request['email'];
        }

        if ($user->username != $request['username']) {
            $formFields['username'] = $request->validate([
                'username' => 'required|unique:users|max:50'
            ])['username'];
        } else {
            $formFields['username'] = $request['username'];
        }

        $user['name'] = $formFields['name'];
        $user['password'] = $formFields['password'];
        $user['address'] = $formFields['address'];
        $user['walletBalance'] = $formFields['walletBalance'];

        $user->save();

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return User::destroy($id);
    }


    // Logout User
    public function logout(Request $request)
    {

        Auth::user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json('Successfully logged out');
    }

    // Authenticate User
    public function login(Request $request)
    {
        $formFields = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $formFields['email'])->first();

        if (!$user || !Hash::check($formFields['password'], $user->password)) {
            return response([
                'message' => 'Bad creds'
            ], 401);
        }

        $token = $user->createToken($request->device_name || "ecomApp")->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 200);
    }
}
