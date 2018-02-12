<?php

namespace App\Http\Controllers;

use App\Models\Request as HelpRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Create and send request.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createRequest(Request $request)
    {
        $this->validate($request, HelpRequest::$rules);

        $helpRequest = new HelpRequest();
        $sender = $request->input("email");
        $description = $request->input("description");
        $phone = $request->input("phone");
        $helpRequest->email = $sender;
        $helpRequest->phone = $phone;
        $helpRequest->location = $request->input("location");
        $helpRequest->description = $description;
        $helpRequest->save();

        $data = [];
        $message = "I need help planning a trip Description: ". $description . "\n phone: ". $phone;
        $data[] = getenv('ADMIN_EMAIL');
        $data[] = $request->input("email");

        Mail::raw(
            $message, $data, function ($msg) use ($data) {
                $msg->to([$data[0],]);
                $msg->from([$data[1],]);
            }
        );
<<<<<<< HEAD
=======
        Mail::to($sender)->send("yo! my country people");
>>>>>>> feat(project-structure):Initial project structure

        return response()->json($helpRequest, 201);
    }
}
