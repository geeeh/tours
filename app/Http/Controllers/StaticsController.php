<?php

namespace App\Http\Controllers;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Object_;

class StaticsController extends Controller
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
     * Get new users registered every month.
     *
     * @param Request $request - request object.
     *
     * @return \Illuminate\Http\Response
     */
    public function newUsersPerMonth(Request $request)
    {
        $monthlyUsers = [];
        $now = Carbon::now();
        $startDate = Carbon::parse($request->input("startDate"))??$now->subYear();
        for($date=$startDate; $date->lt($now); $date->addMonth()) {
            $registeredUsers = User::whereBetween("created_at",[$date->startOfMonth(), $date->endOfMonth()])
            ->count();
            $currentMonth = (Object)[$date->format('F') => $registeredUsers];
            array_push($monthlyUsers, $currentMonth);
        }
        return response($monthlyUsers, 200);
    }

    /**
     * Get users interested in a given event.
     *
     * @return \Illuminate\Http\Response
     */
    public function usersPerEvent()
    {
        $eventUsersCount = [];
        $allEvents = Event::all();
        foreach ($allEvents as $event) {
            $eventCount = Event::find($event->id)->withCount('users');
            $usersPerEvent = (Object)[$event->name, $eventCount];
            array_push($eventUsersCount, $usersPerEvent);
        }
        return response($eventUsersCount, 200);
    }

    /**
     * Get users interested in each package.
     */
    public function usersPerPackage()
    {

    }
}
