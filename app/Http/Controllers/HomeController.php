<?php

namespace App\Http\Controllers;

use App\Models\LogActivity;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $sharingPerson = new Api\SharingController();
        $response =  $sharingPerson->get_sharing_persons('ekolog');

//        $persons = Person::where('user_id', Auth::id())
//            ->with([
//                'tags',
//                'notes.file',
//                'cities',
//                'companies',
//                'links',
//                'files',
//                'activities',
//                'contacts',
//                'infos',
//                'connections',
//                'multiplelds'
//            ])
//            ->where('me', 0)
//            ->paginate();;
//        $log = LogActivity::where('user_id', Auth::id())
//            ->orderBy('id', 'DESC')->first();
        return view('home');
    }
}
