<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;

/**
 * UserController
 * 
 */
class UserController extends Controller
{
    /**
     * Create a new user controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Fetch all registered users.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        $users = User::all();
        return response()->json($users, 200);
    }

    /**
     * Authenticate
     * 
     * Authenticate users
     */
    public function authenticate(Request $request)
    {
        $this->validate(
            $request, [
                "email" => "required",
                "password" => "required",
            ]
        );

        $user = User::where("email", $request->input("email"))->first();
        if (!$user) {
            return response()->json(
                ["status" => "fail",
                "message" => "email not found"],
                401
            );
        }

        if (Hash::check($request->input("password"), $user->password)) {
            $apikey = base64_encode(str_random(40));
            User::where("email", $request->input("email"))->update(["api_key" => "$apikey"]);

            $user = User::where("email", $request->input("email"))->first();
            return response()->json(["status" => "success", "user" => $user]);
   
        } else {
            return response()->json(['status' => 'fail'], 401);
   
        }
    }

    /**
     * Create
     * 
     * Register new user
     * 
     * @return Response - response object.
     */
    public function create(Request $request)
    {
        $this->validate(
            $request, [
                "name" => "required",
                "email" => "required",
                "password" => "required",
            ]
        );
        $exists = User::where("email", $request->input("email"))->first();
        if ($exists) {
            return response()->json(
                ["status" => "fail",
                "message" => "email already taken"],
                409
            );
        }
        $user = new User();
        $user->name = $request->input("name");
        $user->email = $request->input("email");
        $user->password = Hash::make($request->input("password"));
        $user->save();

        return response()->json($user, 201);
    }
}
