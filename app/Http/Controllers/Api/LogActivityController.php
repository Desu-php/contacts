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
            'logs' => 'required|array',
            'logs.*.appId' => 'required|string',
            'logs.*.changes' => 'required|array',
            'logs.*.changes.index' => 'required|numeric',
            'logs.*.changes.action' => 'required|string',
            'logs.*.changes.entity' => 'required|string',
            'logs.*.changes.id' => 'nullable|numeric',
            'logs.*.changes.values' => 'required|array',
            'logs.*.changes.where' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        foreach ($request->logs as $log) {
            ;
            $result = [];
            switch ($log['changes']['action']) {
                case 'add':
                    $result = $this->add($log);
                    break;
                case 'del':
                    $result = $this->del($log);
                    break;
                case 'set':
                    $result = $this->set($log);
                    break;
                case 'update':
                    $result = $this->update($log);
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
            $this->logCreate($log);
        }

        return response()->json(true);
    }

    private function add($log)
    {
        try {
            $insert = [];

            foreach ($log['changes']['values'] as $key => $value) {
                $insert[$key] = $value;
            }

            if (ucfirst($log['changes']['entity']) == 'Person') {
                $insert['user_id'] = Auth::id();
            }

            $model = $this->createModel($log['changes']['entity']);

            $model->create($insert);

            return $result = ['success' => true, 'message' => 'Успешно добавен'];
        } catch (\Exception $exception) {
            return $result = ['success' => false, 'message' => $exception->getMessage()];
        }
    }

    private function del($log)
    {
        try {
            $model = $this->createModel($log['changes']['entity']);

            $where = [];

            foreach ($log['changes']['values'] as $key => $value) {
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

    private function set($log)
    {
        try {
            $model = $model = $this->createModel($log['changes']['entity']);

            $where = [];

            foreach ($log['changes']['values'] as $key => $value) {
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

    private function update($log)
    {
        try {
            $model = $model = $this->createModel($log['changes']['entity']);

            $where = [];
            foreach ($log['changes']['where'] as $key => $value) {
                $where[] = [$key, $value];
            }

            $values = [];
            foreach ($log['changes']['values'] as $key => $value) {
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

    private function logCreate($log)
    {
        LogActivity::create([
            'app_id' => $log['appId'],
            'index' => $log['changes']['index'],
            'action' => $log['changes']['action'],
            'entity' => $log['changes']['entity'],
            'values' => json_encode($log['changes']['values']),
            'user_id' => Auth::id(),
            'where' => json_encode($log['changes']['where']),
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
            'limit' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $logActivities = LogActivity::where('app_id', '<>', $request->AppId)
            ->where('id', '>', $request->ID)
            ->where('user_id', Auth::id());

        if (!empty($request->limit)) {
            $logActivities->take($request->limit);
        }

        return response()->json([
            'data' => $logActivities->get()
        ]);

    }
}
