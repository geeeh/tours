<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Exceptions\NotFoundException;

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
        $this->middleware('auth', ['only'=>['create', 'modify', 'delete', 'getUpcomingEvents']]);
    }

    /**
     * Fetch all events.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
       $events = Event::all();
       return response()->json($events, 200);
    }

    /**
     * Create a new record of an event.
     *
     * @param Request $request - request object
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $this->validate(
            $request, Event::$rules
        );

        $event = new Event();
        $event->name = $request->input("name");
        dd($request->input("location"));
        $event->location = $request->input("location");
        $event->cost = $request->input("cost");
        $event->date = $request->input("date");
        $event->activities = $request->input( "activities");
        $event->company_id = $request->input("company");

        $image = $request->file('image');
        $filename  = time() . '.' . $image->getClientOriginalExtension();
        $folderName = "uploads/";
        $destinationPath = $this->publicPath($folderName);
        $image->move($destinationPath, $filename);

        $event->image = $folderName.$filename;
        $event->save();

        return response()->json($event, 201);
    }

    /**
     * Get upcoming events.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUpcomingEvents()
    {
        $today = Carbon::now();
        $events = Event::all();

        $upcomingEvents = [];
        foreach ($events as $event) {
            $date = Carbon::parse($event->date);
            echo($date);
            if ($date->gt($today)) {
                array_push($upcomingEvents, $event);
            }
        }
        return response()->json($upcomingEvents, 200);
    }

    /**
     * Update events.
     *
     * @param Request $request - request object.
     * @param Integer $id - event id.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws NotFoundException
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request, Event::$rules
        );
        $event = Event::find($id);
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
     * @param integer - $id - event id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws NotFoundException
     */
    public function delete($id)
    {
        $event = Event::find($id);
        if (!$event) {
            throw new NotFoundException();
        }
        $event->delete();
        return response()->json('deleted', 200);
    }
}