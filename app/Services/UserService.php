<?php


namespace App\Services;


use App\Models\Friend;
use App\Models\User;
use Illuminate\Support\Str;

class UserService extends BaseService
{
    /**
     * 注册
     * @param $request
     * @return array
     * @throws \Exception
     */
    public function register($request)
    {
        if ($user = User::where('name', $request->name)->first()) {
            throw new \Exception('用户名已存在');
        }
        if ($user = User::where('email', $request->email)->first()) {
            throw new \Exception('邮箱已被注册');
        }
        $uuid = Str::uuid();
        $add_data = [
            'uuid' => $uuid,
            'name' => $request->name,
            'email' => $request->email,
            'password' => encrypt($request->password),
        ];

        User::insert($add_data);

        return [
            'token' => encrypt($uuid),
            'user_id' => $uuid,
            'name' => $request->name,
            'avatar' => '',
        ];
    }


    /**
     * 登录
     * @param $request
     * @return array
     * @throws \Exception
     */
    public function login($request)
    {
        if (!$user = User::where('name', $request->name)->first()) {
            throw new \Exception('用户不存在');
        }
        if ($request->password !== decrypt($user->password)) {
            throw new \Exception('密码错误');
        }

        return [
            'token' => encrypt($user->uuid),
            'user_id' => $user->uuid,
            'name' => $user->name,
            'avatar' => '',
        ];
    }

    /**
     * 退出登录
     * @param $request
     * @return array
     */
    public function logout($request)
    {
        return [];
    }

    /**
     * 好友列表
     * @param $request
     * @return mixed
     * @throws \Exception
     */
    public function firends($request)
    {
        $uuid = decrypt($request->token);
        if (!$user = User::where('uuid', $uuid)->first()) {
            throw new \Exception('身份验证已失效');
        }
        $friends = [];

        // 获取和自己相关的所有好友关系
        $list = Friend::where(
            function ($query) use ($user) {
                $query->where('uid', $user['id'])
                    ->orWhere('friend_uid', $user['id']);
            }
        )->get();
        if ($list) {
            // 取出加自己好友 和 自己加对方好友的 用户id并去重
            $uids = $list->pluck('uid')->toArray();
            $friend_uids = $list->pluck('friend_uid')->toArray();
            $uid_arr = array_unique(array_merge($uids, $friend_uids));
            $users = User::whereIn('id', $uid_arr)->get()->toArray();
            $i = 0;
            foreach ($uid_arr as $index => $item) {
                // 剔除自己
                if ($item == $user['id']) {
                    continue;
                }
                foreach ($users as $key => $value) {
                    if ($item == $value['id']) {
                        $friends[$i]['friend_user_id'] = $value['uuid'];
                        $friends[$i]['name'] = $value['name'];
                        $friends[$i]['avatar'] = $value['avatar'];
                        $friends[$i]['msg_count'] = null;
                        $i++;
                    }
                }
            }
        }
        return $friends;
    }

    /**
     * 添加好友
     * @param $request
     * @return array
     * @throws \Exception
     */
    public function addFriend($request)
    {
        $uuid = decrypt($request->token);
        if (!$user = User::where('uuid', $uuid)->first()) {
            throw new \Exception('身份验证已失效');
        }
        if ($user['name'] === $request->name) {
            throw new \Exception('不能添加自己为好友');
        }
        if (!$friend = User::where('name', $request->name)->first()) {
            throw new \Exception('用户不存在');
        }
        $res = Friend::where(
            function ($query) use ($user, $friend) {
                $query->where(
                    function ($query) use ($user, $friend) {
                        $query->where('uid', $user['id'])->where('friend_uid', $friend['id']);
                    }
                )->orWhere(
                    function ($query) use ($user, $friend) {
                        $query->where('uid', $friend['id'])->where('friend_uid', $user['id']);
                    }
                );
            }
        )->first();
        if ($res) {
            throw new \Exception('对方已是您的好友');
        }
        $add = [
            'uid' => $user['id'],
            'friend_uid' => $friend['id'],
            'created_at' => date('Y-m-d H:i:s'),
        ];

        Friend::insert($add);

        return [
            'name' => $friend['name'],
            'avatar' => $friend['avatar'],
            'friend_user_id' => $friend['uuid'],
        ];
    }
}
