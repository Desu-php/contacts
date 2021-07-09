<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Sharing;
use App\Models\SharingUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class SharingController extends Controller
{
    //
    use \App\Traits\Sharing;

    private $sharingWith = ['tags_ids', 'cities_ids', 'activities_ids', 'companies_ids'];

    public function store(Request $request)
    {
        $validator = $this->validations($request);

        if ($validator->fails()) {
            return Response()->json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $sharing = Sharing::create([
            'name' => $request->name,
            'type_access' => $request->type_access,
            'open' => $request->open,
            'user_id' => Auth::id()
        ]);

        $this->attaches($sharing, $request->conditions);

        return response()->json([
            'id' => $sharing->id,
            'api' => route('sharing.show', $sharing->id),
            'web' => route('web.sharing.show', $sharing->id)
        ]);

    }

    private function attaches($sharing, $conditions, $delete = false)
    {
        $tables = ['tags', 'cities', 'activities', 'companies'];

        foreach ($tables as $table) {
            $this->attach($table, $sharing, $conditions, $delete);
        }
    }

    private function attach($key, $sharing, $conditions, $delete = false)
    {
        if ($delete) {
           $sharing->$key()->detach();
        }

        foreach ($conditions[$key] as $value) {
            $sharing->$key()->attach($value['id'], ['not' => $value['not']]);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validations($request);

        if ($validator->fails()) {
            return Response()->json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $sharing = Sharing::find($id);

        if (is_null($sharing)) {
            return response()->json([
                'error' => 'Sharing not found'
            ], 404);
        }

        $sharing->update([
            'name' => $request->name,
            'type_access' => $request->type_access,
            'open' => $request->open,
        ]);

        $this->attaches($sharing, $request->conditions, true);

        return response()->json([
            'id' => $sharing->id,
            'api' => route('sharing.show', $sharing->id),
            'web' => route('web.sharing.show', $sharing->id)
        ]);

    }

    private function validations($request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type_access' => 'required|numeric',
            'open' => 'required|numeric',
            'conditions' => 'required|array',
            'conditions.cities' => 'nullable|array',
            'conditions.cities.*.id' => 'required|exists:cities,id',
            'conditions.cities.*.not' => 'required|boolean',
            'conditions.tags' => 'nullable|array',
            'conditions.tags.*.id' => 'required|exists:tags,id',
            'conditions.tags.*.not' => 'required|boolean',
            'conditions.companies' => 'nullable|array',
            'conditions.companies.*.id' => 'required|exists:companies,id',
            'conditions.companies.*.not' => 'required|boolean',
            'conditions.activities' => 'nullable|array',
            'conditions.activities.*.id' => 'required|exists:activities,id',
            'conditions.activities.*.not' => 'required|boolean',

        ]);
    }


    public function destroy($id)
    {
        $sharing = Sharing::where('user_id', Auth::id())
            ->where('id', $id)->first();

        if (is_null($sharing)) {
            return response()->json([
                'error' => 'sharing not found'
            ]);
        }

        $sharing->delete();

        return response()->json([
            'message' => 'Шаринг успешно удален'
        ]);
    }

    public function sharing_user_access_off(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sharing_id' => 'required|exists:sharing_users,sharing_id',
            'user_id' => 'nullable|exists:sharing_users,user_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->fails()
            ], 400);
        }

        $sharingUser = SharingUser::where('sharing_id', $request->sharing_id)
            ->where('access', SharingUser::ACCESS_ALLOWED)
            ->whereHas('sharing', function (Builder $builder) {
                $builder->where('user_id', Auth::id());
            });

        if (!empty($request->user_id)) {
            $sharingUser->where('user_id', $request->user_id);
        }

        $sharingUser->update(['access' => SharingUser::ACCESS_DENIED]);

        return response()->json([
            'message' => 'Доступ успешно закрыт'
        ]);
    }

//    public function sharing_users_access_off(Request $request)
//    {
//        $validator = Validator::make($request->all(), [
//            'sharing_id' => 'required|exists:sharings,id'
//        ]);
//
//        if ($validator->fails()){
//            return response()->json([
//                'errors' => $validator->getMessageBag()
//            ], 400);
//        }
//
//       $sharing = Sharing::where('user_id', Auth::id())
//           ->where('id', $request->sharing_id)
//           ->first();
//
//        if (is_null($sharing)){
//            return  response()->json([
//                'error' => 'Sharing not found'
//            ],404);
//        }
//
//        $sharing->type_access = 0;
//        $sharing->save();
//
//        return response()->json([
//            'message' => 'Доступ успешно удален'
//        ]);
//    }

    public function get_sharings_list()
    {

        $sharing_list = Sharing::whereHas('users', function (Builder $builder) {
            $builder->where('users.id', Auth::id())
                ->where('access', SharingUser::ACCESS_ALLOWED);
        })->with(['user' => function ($query) {
            $query->select(['id']);
            $query->with(['profile' => function ($query) {
                $query->select(['givenName', 'familyName', 'middleName', 'user_id', 'thumbnailImage']);
            }]);
        }])->with($this->sharingWith)->get();

        $sharing_list = $this->sharingUsers($sharing_list);

        return response()->json(
            $sharing_list
        );
    }

    private function sharingUsers($sharings)
    {
        $sharings->map(function ($sharing) {
            $persons = Person::where('user_id', $sharing->user_id)->where('me', 0);
            foreach ($this->sharingWith as $value) {
                $persons = $this->sharingConditions($sharing, $persons, $value, str_replace('_ids', '', $value));
            }

            $sharing->persons_count = $persons->count();
        });
        return $sharings;
    }

    private function sharingConditions($sharing, $persons, $relations, $whereHas)
    {
        foreach ($sharing->$relations as $relation_id) {
            return $persons->where(function ($query) use ($relation_id, $whereHas) {
                $query->orWhereHas($whereHas, function ($query) use ($relation_id, $whereHas) {
                    $singular = Str::singular($whereHas) . '_id';
                    $query->where($whereHas . '.id', $relation_id->$singular);
                });
            });
        }
        return $persons;
    }

    public function get_sharing_access_users($id)
    {
        $sharing = Sharing::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (is_null($sharing)) {
            return response()->json([
                'error' => 'Sharing not found'
            ], 404);
        }

        return response()->json($sharing
            ->users()
            ->where('access', SharingUser::ACCESS_ALLOWED)
            ->with('profile.profileInfo')
            ->get());
    }

    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string'
        ]);

        if ($validator->fails()) {
            return Response()->json([
                'errors' => $validator->getMessageBag()
            ]);
        }
        $query = $request->get('query');

        $sharings = Sharing::whereHas('users', function (Builder $builder) {
            $builder->where('users.id', Auth::id())
                ->where('access', SharingUser::ACCESS_ALLOWED);
        })->where(function (Builder $builder) use ($query) {
            $builder->where('name', 'LIKE', '%' . $query . '%')
                ->orWhereHas('tags', function (Builder $builder) use ($query) {
                    $builder->where('name', 'LIKE', '%' . $query . '%');
                })
                ->orWhereHas('cities', function (Builder $builder) use ($query) {
                    $builder->where('name', 'LIKE', '%' . $query . '%');
                })
                ->orWhereHas('activities', function (Builder $builder) use ($query) {
                    $builder->where('name', 'LIKE', '%' . $query . '%');
                })
                ->orWhereHas('companies', function (Builder $builder) use ($query) {
                    $builder->where('name', 'LIKE', '%' . $query . '%');
                });
        })->where(function (Builder $builder) use ($query) {
            $builder->orWhereHas('user.persons.tags', function (Builder $builder) use ($query) {
                $builder->where('name', 'LIKE', '%' . $query . '%');
            })
                ->orWhereHas('user.persons.cities', function (Builder $builder) use ($query) {
                    $builder->where('name', 'LIKE', '%' . $query . '%');
                })
                ->orWhereHas('user.persons.activities', function (Builder $builder) use ($query) {
                    $builder->where('name', 'LIKE', '%' . $query . '%');
                })
                ->orWhereHas('user.persons.companies', function (Builder $builder) use ($query) {
                    $builder->where('name', 'LIKE', '%' . $query . '%');
                });
        })->with(['user.persons' => function ($builder) use ($query) {
            $builder->where(function (Builder $builder) use ($query) {
                $builder->orWhereHas('tags', function (Builder $builder) use ($query) {
                    $builder->where('name', 'LIKE', '%' . $query . '%');
                })
                    ->orWhereHas('cities', function (Builder $builder) use ($query) {
                        $builder->where('name', 'LIKE', '%' . $query . '%');
                    })
                    ->orWhereHas('activities', function (Builder $builder) use ($query) {
                        $builder->where('name', 'LIKE', '%' . $query . '%');
                    })
                    ->orWhereHas('companies', function (Builder $builder) use ($query) {
                        $builder->where('name', 'LIKE', '%' . $query . '%');
                    });
            });
            $builder->with([
                'companies',
                'activities',
                'cities',
                'tags'
            ]);
        }])->get();


        $results = $sharings->map(function ($sharing) {
            if ($sharing->type_access == SharingUser::ACCESS_DENIED) {
                $sharing->user->persons->map(function ($item) {
                    unset($item->activities);
                });
            }
            return $sharing;
        });

        return response()->json($results);
    }

    public function get_my_sharing_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'open' => 'nullable|boolean'
        ]);

        if ($validator->fails()){
            return  response()->json([
                'errors' => $validator->getMessageBag()
            ],400);
        }

        $sharings = Sharing::where('user_id', Auth::id())
            ->withCount('users')
            ->with($this->sharingWith);

        if ($request->has('open')){
            $sharings->where('open', $request->open);
        }

        $sharings = $sharings->paginate();

        $sharings = $this->sharingUsers($sharings);


        return response()->json($sharings);
    }

    public function unsubscribe($id)
    {
        $sharingUser = SharingUser::where('sharing_id', $id)
            ->where('user_id', Auth::id())->delete();

        return response()->json($sharingUser);
    }

    public function get_sharing_persons($id)
    {
        $sharing = Sharing::whereHas('users', function (Builder $builder) {
            $builder->where('users.id', Auth::id())
                ->where('access', SharingUser::ACCESS_ALLOWED);
        })->where('id', $id)->first();

        if (is_null($sharing)) {
            return response()->json([
                'error' => 'Sharing not found'
            ], 404);
        }
        $persons = $sharing->user->persons()->with([
            'tags',
            'cities',
            'activities',
            'connections'
        ]);
        foreach ($this->sharingWith as $value) {
            $persons = $this->sharingConditions($sharing, $persons, $value, str_replace('_ids', '', $value));
        }

        return response()->json($persons->paginate());
    }
}
