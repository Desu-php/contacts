<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Score;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            ->whereDate('created_at','<', now()->format('Y-m-d'))
            ->latest()
            ->first();

        if (!is_null($prevScore)) {
            $score -= $prevScore->score;
        }

        auth()->user()
            ->scores()
            ->whereDate('created_at', now()->format('Y-m-d'))
            ->updateOrCreate([], ['score' => $score]);

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
            'today' => $scores->first()->score,
            'week' => $scores->take(7)->sum('score'),
            'month' => $scores->sum('score')
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
}
