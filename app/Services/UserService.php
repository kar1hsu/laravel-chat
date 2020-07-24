<?php


namespace App\Services;


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
            'token' => $uuid,
            'name' => $request->name,
        ];
    }
}