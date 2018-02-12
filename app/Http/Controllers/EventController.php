<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Exceptions\NotFoundException;
use App\Models\Company;

/**
 * EventController
 * 
 * Manipulate events table.
 * 
 */
class EventController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(
            'auth',
            [
                'only'=>[
                'modify', 'delete'
                ]
            ]
        );
    }


    /**
     * Fetch all events
     */
    public function getAll()
    {
        $events = Event::all();
        return response()->json($events, 200);
    }

    /**
     * Fetch all events belonging to a company.
     *
     * @return \Illuminate\Http\Response
     */
    public function getById($id)
    {
        $events =Event::where("company_id", $id)
        ->get();
        return response()->json($events, 200);
    }

    /**
     * Create a new record of an event.
     *
     * @param Request $request - request object
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request, $id)
    {
//        $this->validate(
//            $request, Event::$rules
//        );

        $event = new Event();
        $event->name = $request->input("title");
        $event->location = $request->input("location");
        $event->cost = $request->input("cost");
        $event->date = $request->input("date");
        $event->activities = $request->input("activities");
        $event->company_id = $id;
        $event->description = $request->input("description");
        $image = $request->file('image');
        $filename = uniqid(8).'.'.$image->getClientOriginalExtension();
        $folderName = "uploads/";
        $destinationPath = $this->publicPath($folderName);
        $image->move($destinationPath, $filename);

        $event->image = $folderName.$filename;
        $event->save();

        return response()->json("success", 201);
    }

    /**
     * Show interest in event.
     *
     */
    public function showInterest($id, $user_id,  Request $request) {
        $event = Event::find($id);
        if ($request->input('interested')){
            $event->user_id = $user_id;
            $event->users()->save();
            $result = (Object)["status" => "success"];
            return response()->json($result, 201);
        }

    }

    /**
     * Get upcoming events.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUpcomingEvents()
    {

        $upcomingEvents = Event::orderBy('date', 'asc')
            ->limit(5)
            ->get();

        return response()->json($upcomingEvents, 200);
    }

    /**
     * Update events.
     *
     * @param Request $request - request object.
     * @param Integer $id - event id.
     *
     * @param $eventId - event id
     * @return \Illuminate\Http\JsonResponse
     * @throws NotFoundException
     */
    public function update(Request $request, $id, $eventId)
    {
        $this->validate(
            $request, Event::$rules
        );
        $event = Event::find($eventId)
            ->where('comapny_id', $id);
        if (!$event) {
            throw new NotFoundException();
        }
        $event->name = $request->input("name");
        $event->location = $request->input("location");
        $event->cost = $request->input("cost");
        $event->date = $request->input("date");
        $event->update();

        return response()->json($event, 202);
    }

    /**
     * Delete an event by id.
     *
     * @param $id
     * @param $eventId - event id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws NotFoundException
     */
    public function delete($id, $eventId)
    {
        $event = Event::find($eventId);
        if (!$event) {
            throw new NotFoundException();
        }
        $event->delete();
        return response()->json('deleted', 200);
    }
}