<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LogActivity;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LogActivityController extends Controller
{
    //

    public function put_log_change_param(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appId' => 'required|string',
            'changes' => 'required|array',
            'changes.index' => 'required|numeric',
            'changes.action' => 'required|string',
            'changes.entity' => 'required|string',
            'changes.id' => 'nullable|numeric',
            'changes.values' => 'required|array',
            'changes.where' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $result = [];
        switch ($request->changes['action']) {
            case 'add':
                $result = $this->add($request);
                break;
            case 'del':
                $result = $this->del($request);
                break;
            case 'set':
                $result = $this->set($request);
                break;
            case 'update':
                $result = $this->update($request);
                break;
            default:
                $result = ['success' => false, 'message' => 'Action не найден'];
                return true;
        }

        if (!$result['success']) {
            return response()->json([
                'error' => $result['message']
            ], 500);
        }

        $this->logCreate($request);
        return response()->json(true);
    }

    private function add($request)
    {
        try {
            $insert = [];

            foreach ($request->changes['values'] as $key => $value) {
                $insert[$key] = $value;
            }

            $model = $this->createModel($request->changes['entity']);

            $model->create($insert);

            return $result = ['success' => true, 'message' => 'Успешно добавен'];
        } catch (\Exception $exception) {
            return $result = ['success' => false, 'message' => $exception->getMessage()];
        }
    }

    private function del($request)
    {
        try {
            $model = $this->createModel($request->changes['entity']);

            $where = [];

            foreach ($request->changes['values'] as $key => $value) {
                $where[] = [$key, $value];
            }

            if (!empty($where)) {
                $model->where($where)->delete();
            }

            return $result = ['success' => true, 'message' => 'Успешно удален'];
        } catch (\Exception $exception) {
            return $result = ['success' => false, 'message' => $exception->getMessage()];
        }
    }

    private function set($request)
    {
        try {
            $model = $model = $this->createModel($request->changes['entity']);

            $where = [];

            foreach ($request->changes['values'] as $key => $value) {
                $where[$key] = $value;
            }

            if (!empty($where)) {
                $model->firstOrCreate($where);
            }

            return $result = ['success' => true, 'message' => 'Успешно'];
        } catch (\Exception $exception) {
            return $result = ['success' => false, 'message' => $exception->getMessage()];
        }


    }

    private function update($request)
    {
        try {
            $model = $model = $this->createModel($request->changes['entity']);

            $where = [];
            foreach ($request->changes['where'] as $key => $value) {
                $where[] = [$key, $value];
            }

            $values = [];
            foreach ($request->changes['values'] as $key => $value) {
                $values[$key] = $value;
            }

            if (!empty($where) && !empty($values)) {
                $model->where($where)->update($values);
            }

            return $result = ['success' => true, 'message' => 'Успешно'];
        } catch (\Exception $exception) {
            return $result = ['success' => false, 'message' => $exception->getMessage()];
        }


    }

    private function logCreate($request)
    {
        LogActivity::create([
            'app_id' => $request->appId,
            'index' => $request->changes['index'],
            'action' => $request->changes['action'],
            'entity' => $request->changes['entity'],
            'values' => json_encode($request->changes['values']),
            'user_id' => Auth::id(),
            'where' => $request->changes['where'],
        ]);
    }

    private function createModel($entity)
    {
        $entity = 'App\Models\\' . ucfirst($entity);
        return new $entity;
    }

    public function get_log_change_param(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'AppID' => 'required|string',
            'ID' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $logActivities = LogActivity::where('app_id', '<>', $request->AppId)
            ->where('id', '>', $request->ID)
            ->where('user_id', Auth::id())->get();

        return response()->json([
            'data' => $logActivities
        ]);

    }
}
