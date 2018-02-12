<?php

namespace App\Http\Controllers;

use App\Models\Request as HelpRequest;
use Mailgun\Mailgun;
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
     * @throws \Mailgun\Messages\Exceptions\MissingRequiredMIMEParameters
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

        $message = "I need help planning a trip Description: ". $description . "\n phone: ". $phone;
        $data[] = $request->input("email");

        $mgClient = new Mailgun(getenv('MAILGUN_SECRET'), new \Http\Adapter\Guzzle6\Client());
        $domain = getenv("MAILGUN_DOMAIN");
        $result = $mgClient->sendMessage($domain, array(
            'from'    => $sender,
            'to'      => getenv(MAIL_USERNAME),
            'subject' => 'Hello',
            'text'    => $message
        ));

        return response()->json($result, 201);
    }
}
