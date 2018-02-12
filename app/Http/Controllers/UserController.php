<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
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
        $this->middleware(
            'auth',
            [
                'only'=>[
                    'createProfile'
                ]
            ]
        );
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
        $this->validate($request, User::$rules);
        $exists = User::where("email", $request->input("email"))->first();
        if ($exists) {
            return response()->json(
                ["status" => "fail",
                "message" => "email already taken"],
                409
            );
        }
        $user = new User();
        $user->name = $request->input("username");
        $user->email = $request->input("email");
        $user->password = Hash::make($request->input("password"));
        $user->save();

        return response()->json($user, 201);
    }

    public function createProfile($id, Request $request) {
        $this->validate($request, Profile::$rules);

        $userProfile = new Profile();
        $userProfile->name = $request->input('name');
        $userProfile->phone = $request->input('phone');
        $userProfile->about = $request->input('about');
        $userProfile->package = $request->input('package');
        $userProfile->user_id = $id;
        $folderName = "uploads/";

        $photo = $request->file('photoUrl');
        $photoname = uniqid(8).'.'.$photo->getClientOriginalExtension();
        $destinationPath = $this->publicPath($folderName);
        $photo->move($destinationPath, $photoname);

        $userProfile->photoUrl = $folderName.$photoname;

        $badge = $request->file('badge');
        $badgename = uniqid(8).'.'.$badge->getClientOriginalExtension();
        $destinationPath = $this->publicPath($folderName);
        $badge->move($destinationPath, $badgename);
        $userProfile->badge = $folderName.$badgename;
        $userProfile ->save();

        return $userProfile;

    }

    /**
     * Fetch user profile
     * @param id - user id.
     * @return Response - json
     */
    public function getUserProfile($id)
    {
        $userProfile = Profile::with('user')->where('user_id', $id)->get()->toArray();
        $user = $userProfile[0]["user"];
        $result = (object)[
            "name"=>$userProfile[0]["name"],
            "email" => $user["email"],
            "photo" => $userProfile[0]["photoUrl"],
            "phone" => $userProfile[0]["phone"],
            "badge" => $userProfile[0]["badge"],
            "about" => $userProfile[0]["about"],
            "package" => $userProfile[0]["package"]
        ];

        return response()->json($result, 200);
    }
}
