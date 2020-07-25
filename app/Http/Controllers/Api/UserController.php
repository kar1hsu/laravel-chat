<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $UserService;
    protected $rets;


    public function __construct()
    {
        $this->UserService = new UserService();
        $this->rets = [
            'code' => 1000,
            'message' => '',
            'data' => [],
        ];
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:2', 'max:16'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ],[
            'name.required'        =>'请输入用户名',
            'name.min'        =>'请输入2位数以上的用户名',
            'name.max'        =>'用户名不可超过16位',
            'email.required'     =>'请输入邮箱地址',
            'email.email'     =>'请输入正确的邮箱地址',
            'password.required'         =>'请输入密码',
            'password.min'         =>'请输入6位数以上密码',
        ]);
        if ($validator->fails()) {
            $this->rets['code'] = 1001;
            $error = $validator->errors()->toArray();
            $this->rets['message'] = reset($error)[0];
            return response()->json($this->rets);
        }
        try {
            $data = $this->UserService->register($request);
            $this->rets['data'] = $data;
        }catch (\Exception $exception){
            $this->rets['code'] = 1001;
            $this->rets['message'] = $exception->getMessage();
            return response()->json($this->rets);
        }
        return response()->json($this->rets);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:2', 'max:16'],
            'password' => ['required', 'string', 'min:6'],
        ],[
            'name.required'        =>'请输入用户名',
            'name.min'        =>'请输入2位数以上的用户名',
            'name.max'        =>'用户名不可超过16位',
            'password.required'         =>'请输入密码',
            'password.min'         =>'请输入6位数以上密码',
        ]);
        if ($validator->fails()) {
            $this->rets['code'] = 1001;
            $error = $validator->errors()->toArray();
            $this->rets['message'] = reset($error)[0];
            return response()->json($this->rets);
        }

        try {
            $data = $this->UserService->login($request);
            $this->rets['data'] = $data;
        }catch (\Exception $exception){
            $this->rets['code'] = 1001;
            $this->rets['message'] = $exception->getMessage();
            return response()->json($this->rets);
        }
        return response()->json($this->rets);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            $data = $this->UserService->logout($request);
            $this->rets['data'] = $data;
        }catch (\Exception $exception){
            $this->rets['code'] = 1001;
            $this->rets['message'] = $exception->getMessage();
            return response()->json($this->rets);
        }
        return response()->json($this->rets);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFriend(Request $request)
    {
        try {
            $data = $this->UserService->firends($request);
            $this->rets['data'] = $data;
        }catch (\Exception $exception){
            $this->rets['code'] = 1001;
            $this->rets['message'] = $exception->getMessage();
            return response()->json($this->rets);
        }
        return response()->json($this->rets);
    }
}
