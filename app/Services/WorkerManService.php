<?php


namespace App\Services;


use GatewayClient\Gateway;

class WorkerManService extends BaseService
{
    protected $user;

    public function webSocketConnect($client_id, $data)
    {

    }

    public function sendMessage($client_id, $message)
    {
        $message_data = json_decode($message, true);
        if (!$message_data) {
            return 0;
        }
        // uuid加密后生成的token
        $token = $message_data['token'];
        try {
            // 解密uuid
            $uuid = decrypt($token);
            $user = $uuid;
        } catch (\Exception $exception) {
            $return['code'] = 9999;
            $return['msg'] = 'un login';
            // 提示用户登录并关闭当前链接
            Gateway::sendToClient($client_id, json_encode($return));
            Gateway::closeClient($client_id);
            return 0;
        }

        switch ($message_data['type']) {
            case 'ping': // 心跳
                break;
            case 'login': // 登录
                // 绑定uuid并设置session
                Gateway::bindUid($client_id, $uuid);
                Gateway::setSession($client_id, $user);
                break;
            case 'send': // 发送信息
                switch ($message_data['send_type']) {
                    case 'friend': // 发送给好友
                        $new_message = array(
                            'type' => 'send',
                            'send_type' => 'friend',
                            'from_client_id' => $uuid,
                            'from_client_name' => $user['username'],
                            'to_client_id' => $message_data['to_client_id'],
                            'content' => "<b>say: </b>" . nl2br(htmlspecialchars($message_data['content'])),
                            'time' => date('Y-m-d H:i:s'),
                        );
                        // 根据to_client_id实际是用户的uuid,根据uuid去获取绑定的client_id
                        $to_client_ids = Gateway::getClientIdByUid($message_data['to_client_id']);
                        if ($to_client_ids) {
                            foreach ($to_client_ids as $client_id) {
                                // 发送消息
                                Gateway::sendToClient($client_id, json_encode($new_message));
                            }
                        }
                        break;
                    case 'room': // 发送到群
                        $new_message = array(
                            'type' => 'send',
                            'send_type' => 'room',
                            'from_client_id' => $client_id,
                            'from_client_name' => $user['username'],
                            'to_room_id' => $message_data['room_id'],
                            'content' => "<b>say: </b>" . nl2br(htmlspecialchars($message_data['content'])),
                            'time' => date('Y-m-d H:i:s'),
                        );
                        // 发送消息
                        Gateway::sendToGroup($message_data['room_id'], json_encode($new_message));
                        break;
                }
                break;
        }
    }

    public function closeConnect($client_id)
    {
    }
}