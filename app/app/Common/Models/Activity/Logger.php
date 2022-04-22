<?php


namespace App\Common\Models\Activity;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Log;

class Logger
{

    /**
     * @param Request $request
     */
    public static function request(string $name, Request $request){
        $now = Carbon::now();
        $name .= "-REQUEST-" . $now->toTimeString();
        Log::channel('activity')->info($name, [
            'user' => $request->user(),
            'userInfo' => $request->getUserInfo(),
            'userAgent' => $request->userAgent(),
            'ip' => $request->getClientIp(),
            'request' => $request->all(),
            'date' => $now
        ]);
    }

    /**
     * @param string $name
     * @param User $user
     * @param Model $entity
     * @param Model|NULL $oldEntity
     */
    public static function entity(string $name, User $user, Model $entity, Model $oldEntity = NULL){
        $now = Carbon::now();
        $name .= "-ENTITY-" . $now->toTimeString();
        Log::channel('activity')->info($name, [
            'user' => $user,
            'oldEntity' => $oldEntity,
            'entity' => $entity,
            'date' => $now
        ]);
    }

    /**
     * @param string $name
     * @param Request $request
     * @param Model $entity
     * @param Model|NULL $oldEntity
     */
    public static function activity(string $name, Request $request, Model $entity, Model $oldEntity = NULL){
        $now = Carbon::now();
        $name .= "-ACTIVITY-" . $now->toTimeString();
        Log::channel('activity')->info($name, [
            'user' => $request->user(),
            'userInfo' => $request->getUserInfo(),
            'userAgent' => $request->userAgent(),
            'ip' => $request->getClientIp(),
            'request' => $request->all(),
            'oldEntity' => $oldEntity,
            'entity' => $entity,
            'date' => $now
        ]);
    }

}
