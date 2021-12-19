<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Sharing;
use App\Models\SharingUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            $query->select(['id', 'phone']);
            $query->with(['profile' => function ($query) {
                $query->select(['givenName', 'familyName', 'middleName', 'user_id', 'thumbnailImage']);
            }]);
        }])->with($this->sharingWith)->get();

        $sharing_list = $this->sharingUsers($sharing_list);

        return response()->json($sharing_list);
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
            $persons->orWhere(function ($query) use ($relation_id, $whereHas) {
                $query->whereHas($whereHas, function ($query) use ($relation_id, $whereHas) {
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
        $search = $request->get('query');

        $with = [
            'companies',
            'activities',
            'cities',
            'tags'
        ];

        $sharings = Sharing::whereHas('users', function ($query) {
            $query->where('users.id', Auth::id())
                ->where('access', Sharing::OPEN);
        })->with($with)->get();

        $persons = Person::where(function ($query) {
            $query->whereHas('user.sharings', function ($query) {
                $query->where('user_id', '<>', Auth::id())
                    ->whereHas('users', function ($query) {
                        $query->where('users.id', Auth::id())
                            ->where('access', Sharing::OPEN);
                    });
            });
        })->with($with)->get();

        $wherePersons = [];
        $likePersons = [];
        $neededPersons = [];

        foreach ($sharings->groupBy('user_id') as $id => $sharing) {
            $tempPersons = $persons->where('user_id', $id)->whereNotIn('id', $neededPersons);
            foreach ($sharing as $value) {
                foreach ($tempPersons as $person) {

                    $wherePerson = $this->collectionWhere($person, $value->getRelations(), $search);

                    if ($wherePerson) {
                        $wherePersons[] = $wherePerson;
                        $neededPersons[] = $wherePerson->id;
                        continue;
                    }

                    if (!in_array($person, $wherePersons) && !in_array($person, $likePersons)) {
                        $filteredPerson = $this->collectionFilter($person, $value->getRelations(), $search);
                        if (!is_null($filteredPerson)) {
                            $likePersons[] = $filteredPerson;
                            $neededPersons[] = $filteredPerson->id;
                        }
                    }
                }
            }
        }

        return response()->json(array_merge($wherePersons, $likePersons));
    }

    private function collectionFilter($collection, $relations, $search)
    {
        if ($this->checkName($collection, $search)) {
            return $collection;
        }

        foreach ($relations as $key => $relation) {

            if ($relation->isEmpty()) {
                continue;
            }

            $relationNeeded = $relation->filter(function ($item) use ($search) {
                return false !== stripos(Str::lower($item->name), Str::lower($search));
            });

            if ($relationNeeded->isEmpty()) {
                continue;
            }

            $exists = $collection->$key->whereIn('id', $relationNeeded->pluck('id'));

            if ($exists->isNotEmpty()) {
                return $collection;
            }

        }
        return null;
    }

    private function checkName($collection, $search)
    {
        return false !== stripos(Str::lower($collection->givenName . ' ' . $collection->familyName . ' ' . $collection->middleName), Str::lower($search));
    }

    private function checkNameWhere($collection, $search)
    {
        return $collection->givenName == $search || $collection->familyName == $search || $collection->middlename == $search;
    }

    private function collectionWhere($collection, $relations, $search)
    {
        if ($this->checkNameWhere($collection, $search)) {
            return $collection;
        }

        foreach ($relations as $key => $relation) {
            if ($relation->isEmpty()) {
                continue;
            }

            $relationNeed = $relation->where('name', $search);

            if ($relationNeed->isEmpty()) {
                continue;
            }

            $exists = $collection->$key->whereIn('id', $relationNeed->pluck('id'));

            if ($exists->isNotEmpty()) {
                return $collection;
            }
        }
        return null;
    }

    private function conditionsLike($query, $conditions, $value)
    {
        return $query->orWhere(function ($query) use ($conditions, $value) {
            foreach ($conditions as $condition) {
                $query->orWhereHas($condition, function ($query) use ($value) {
                    $query->where('name', 'LIKE', '%' . $value . '%');
                });
            }
        });
    }

    public function get_my_sharing_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'open' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $sharings = Sharing::where('user_id', Auth::id())
            ->withCount('users')
            ->with($this->sharingWith);

        if ($request->has('open')) {
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

        $persons = $this->filterPersons($persons, $sharing);

        return response()->json($persons->paginate());
    }

    private function filterPersons($persons, $sharing)
    {
        return $persons->where(function ($query) use ($sharing) {
            foreach ($this->sharingWith as $value) {
                $this->sharingConditions($sharing, $query, $value, str_replace('_ids', '', $value));
            }
        });
    }

    public function getSharing($sharing_id)
    {
        $sharing = Sharing::query();

        foreach ($this->sharingWith as $value) {
            $sharing->with(str_replace('_ids', '', $value));
        }

        return response()->json($sharing->findOrFail($sharing_id));
    }
}
