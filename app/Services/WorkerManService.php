<?php


namespace App\Services;


use App\Models\User;
use GatewayClient\Gateway;
use Illuminate\Support\Facades\Log;

class WorkerManService extends BaseService
{
    protected $user;
    protected $default_room_id = 'worker-default-room';

    public function webSocketConnect($client_id, $data)
    {

    }

    /**
     * 接受信息并做处理
     * @param $client_id
     * @param $message
     * @return bool
     */
    public function sendMessage($client_id, $message)
    {
        $message_data = json_decode($message, true);
        Log::info($client_id, [$message_data]);
        if (!$message_data) {
            return false;
        }
        // uuid加密后生成的token
        $token = $message_data['token'];
        try {
            // 解密uuid
            $uuid = decrypt($token);
            $user = User::where('uuid', $uuid)->first();
            if(!$user){
                throw new \Exception('un login');
            }
            $user = $user->toArray();
        } catch (\Exception $exception) {
            $return['code'] = 9999;
            $return['message'] = $exception->getMessage();
            // 提示用户登录并关闭当前链接
            Gateway::sendToClient($client_id, json_encode($return));
            Gateway::closeClient($client_id);
            return false;
        }

        switch ($message_data['type']) {
            case 'ping': // 心跳
                break;
            case 'login': // 登录
                // 绑定uuid并设置session
                Gateway::bindUid($client_id, $uuid);
                Gateway::setSession($client_id, $user);
                $new_message = array(
                    'code' => 1000,
                    'type' => 'send',
                    'send_type' => 'room',
                    'from_user_id' => 'worker',
                    'from_user_name' => '通知',
                    'to_room_id' => $this->default_room_id,
                    'content' => $user['name'] ."加入房间",
                    'time' => date('Y-m-d H:i:s'),
                );
                // 加入默认群组
                Gateway::joinGroup($client_id, $this->default_room_id);
                // 发送通知
                Gateway::sendToGroup($this->default_room_id, json_encode($new_message));
                break;
            case 'send': // 发送信息
                switch ($message_data['send_type']) {
                    case 'friend': // 发送给好友
                        $new_message = array(
                            'code' => 1000,
                            'type' => 'send',
                            'send_type' => 'friend',
                            'from_user_id' => $uuid,
                            'from_user_name' => $user['name'],
                            'to_friend_user_id' => $message_data['friend_user_id'],
                            'content' => $message_data['content'],
                            'time' => date('Y-m-d H:i:s'),
                        );
                        // 根据to_client_id实际是用户的uuid,根据uuid去获取绑定的client_id
                        $to_client_ids = Gateway::getClientIdByUid($message_data['friend_user_id']);
                        if ($to_client_ids) {
                            foreach ($to_client_ids as $to_client_id) {
                                // 发送消息给好友
                                Gateway::sendToClient($to_client_id, json_encode($new_message));
                            }
                        }
                        // todo 发送消息给自己 前端展示用
                        Gateway::sendToClient($client_id, json_encode($new_message));
                        break;
                    case 'add_friend': // 新增好友
                        $new_message = array(
                            'code' => 1000,
                            'type' => 'send',
                            'send_type' => 'add_friend',
                            'from_user_id' => $uuid,
                            'from_user_name' => $user['name'],
                            'to_friend_user_id' => $message_data['friend_user_id'],
                            'time' => date('Y-m-d H:i:s'),
                        );
                        // 根据to_client_id实际是用户的uuid,根据uuid去获取绑定的client_id
                        $to_client_ids = Gateway::getClientIdByUid($message_data['friend_user_id']);
                        if ($to_client_ids) {
                            foreach ($to_client_ids as $to_client_id) {
                                // 发送消息给好友
                                Gateway::sendToClient($to_client_id, json_encode($new_message));
                            }
                        }
                        break;
                    case 'room': // 发送到群
                        $new_message = array(
                            'code' => 1000,
                            'type' => 'send',
                            'send_type' => 'room',
                            'from_user_id' => $uuid,
                            'from_user_name' => $user['name'],
                            'to_room_id' => $this->default_room_id,
//                            'content' => "<b>say: </b>" . nl2br(htmlspecialchars($message_data['content'])),
                            'content' => $message_data['content'],
                            'time' => date('Y-m-d H:i:s'),
                        );
                        // 发送消息
                        Gateway::sendToGroup($this->default_room_id, json_encode($new_message));
                        break;
                }
                break;
        }
        return false;
    }


    /**
     * 关闭连接
     * @param $client_id
     */
    public function closeConnect($client_id)
    {
        $user = $_SESSION;
        $new_message = array(
            'code' => 1000,
            'type' => 'send',
            'send_type' => 'room',
            'from_user_id' => $client_id,
            'from_user_name' => '通知',
            'to_room_id' => $this->default_room_id,
            'content' => $user['name'].'离开了房间',
            'time' => date('Y-m-d H:i:s'),
        );
        // 发送消息
        Gateway::sendToGroup($this->default_room_id, json_encode($new_message));
    }
}
