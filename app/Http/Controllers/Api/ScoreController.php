<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Score;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScoreController extends Controller
{
    //

    public function store(Request $request)
    {
        $this->validate($request, [
            'score' => 'required|numeric',
            'percent' => 'nullable|numeric|max:100'
        ]);

        $score = $request->score;

        $prevScore = auth()->user()
            ->scores()
            ->whereDate('created_at', '<', now()->format('Y-m-d'))
            ->sum('score');

        if (!is_null($prevScore)) {
            $score -= $prevScore;
        }

        auth()->user()
            ->scores()
            ->whereDate('created_at', now()->format('Y-m-d'))
            ->updateOrCreate([], ['score' => $score, 'request_score' => $request->score]);

        return response()->json([
            'message' => 'Успешно добавлен'
        ]);
    }

    public function getScores()
    {
        $scores = auth()->user()->scores()
            ->where('created_at', '>=', Carbon::now()->addMonths(-1)->format('Y-m-d'))
            ->orderBy('created_at', 'DESC')
            ->get();

        return response()->json([
            'today' => max($scores->first()->score, 0),
            'week' => max($scores->take(7)->sum('score'), 0),
            'month' => max($scores->sum('score'), 0)
        ]);
    }

    public function getIndicator()
    {
        $users = User::with('scores')
            ->where('id', '<>', Auth::id())
            ->get();
        $scores = Auth::user()->scores()->sum('score');

        $countUser = 0;
        foreach ($users as $user) {
            if ($user->scores->sum('score') < $scores) {
                $countUser++;
            }
        }

        return response()->json(round($countUser * 100 / $users->count()));
    }

    public function last()
    {
        return Auth::user()->scores()->latest()->first();
    }

    public function statistics(Request $request)
    {
        $this->validate($request, [
            'limit' => 'required|numeric'
        ]);

        $startMonth = Carbon::now()->addMonths(-($request->limit - 1))->startOfMonth()->toDateString();

        $scores = Score::whereDate('created_at', '>=', $startMonth)
            ->whereDate('created_at', '<=', now()->toDateString())
            ->where('user_id', auth()->id())
            ->select('scores.*', DB::raw("DATE_FORMAT(created_at, '%Y-%m') new_date"))
            ->orderBy('new_date')
            ->get()
            ->groupBy('new_date');

        $results = [];
        $month = Carbon::parse($startMonth)->format('Y-m');
        $lastMonth = now()->addMonth()->format('Y-m');
        while ($month != $lastMonth) {
            if (!empty($scores[$month])) {
                $results[$month] = $scores[$month]->sum('score');
            } else {
                $results[$month] = 0;
            }
            $month = Carbon::parse($month)->addMonth()->format('Y-m');
        }

        return response()->json($results);
    }
}
