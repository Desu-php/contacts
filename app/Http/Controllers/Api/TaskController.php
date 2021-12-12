<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StatusResource;
use App\Http\Resources\TaskResource;
use App\Models\Status;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    //

    public function getTasks()
    {
        $tasks = Auth::user()->tasks()->with('status')
            ->paginate();

        return TaskResource::collection($tasks);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'reminder' => 'nullable|string',
            'status_id' => 'required|exists:statuses,id'
        ]);

        $task = Auth::user()->tasks()->create($data);

        return response()->json([
            'message' => 'Успешно добавлен',
            'data' => $task
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $this->validate($request, [
            'status_id' => 'required|exists:statuses,id',
        ]);

        Auth::user()->tasks()->where('id', $id)
            ->update($data);

        return response()->json([
            'message' => 'Успешно обновлен'
        ]);
    }

    public function destroy($id)
    {
        Auth::user()->tasks()->where('id', $id)
            ->delete();

        return response()->json([
            'message' => 'Успешно удален'
        ]);
    }

    public function getStatuses()
    {
        return StatusResource::collection(Status::where('type', Task::class)->get());
    }
}
