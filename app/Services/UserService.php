<?php


namespace App\Services;


use App\Models\Friend;
use App\Models\User;
use Illuminate\Support\Str;

class UserService extends BaseService
{
    /**
     * @param $request
     * @return array
     * @throws \Exception
     */
    public function register($request)
    {
        if($user = User::where('name', $request->name)->first()){
            throw new \Exception('用户名已存在');
        }
        if($user = User::where('email', $request->email)->first()){
            throw new \Exception('邮箱已被注册');
        }
        $uuid = Str::uuid();
        $add_data = [
            'uuid' => $uuid,
            'name' => $request->name,
            'email' => $request->email,
            'password' => encrypt($request->password)
        ];

        User::insert($add_data);

        return [
            'token' => encrypt($uuid),
            'name' => $request->name,
            'uuid' => $uuid,
        ];
    }


    /**
     * @param $request
     * @return array
     * @throws \Exception
     */
    public function login($request)
    {
        if(!$user = User::where('name', $request->name)->first()){
            throw new \Exception('用户不存在');
        }
        if($request->password !== decrypt($user->password)){
            throw new \Exception('密码错误');
        }

        return [
            'token' => encrypt($user->uuid),
            'name' => $user->name,
            'uuid' => $user->uuid
        ];
    }

    public function logout($request)
    {
        return [];
    }

    public function firends($request)
    {
        $uuid = decrypt($request->token);
        if(!$user = User::where('uuid', $uuid)->first()){
            throw new \Exception('身份验证已失效');
        }
        $friends = Friend::where('friends.uid', $user['id'])
            ->leftJoin('users', 'users.id', '=', 'friends.fid')
            ->get(['users.name', 'users.uuid as friend_user_id']);

        return $friends;

    }

    public function addFirend($request)
    {
        $uuid = decrypt($request->token);
        if(!$user = User::where('uuid', $uuid)->first()){
            throw new \Exception('身份验证已失效');
        }
        if($user['name'] === $request->name){
            throw new \Exception('不能添加自己为好友');
        }
        if(!$firend = User::where('name', $request->name)->first()){
            throw new \Exception('用户不存在');
        }
        if(Friend::where('uid', $user['id'])->where('fid', $firend['id'])->first()){
            throw new \Exception('对方已是您的好友');
        }
        $add1 = [
            'uid' => $user['id'],
            'fid' => $firend['id'],
        ];
        $add2 = [
            'uid' => $firend['id'],
            'fid' => $user['id'],
        ];
        Friend::insert($add1);
        Friend::insert($add2);

        return [
            'name' => $firend['name'],
            'friend_id' => $firend['uuid']
        ];
    }
}
