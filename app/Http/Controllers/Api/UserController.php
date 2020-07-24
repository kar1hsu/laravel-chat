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
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:32'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
        if ($validator->fails()) {
            $this->rets['code'] = 1001;
            $this->rets['message'] = $validator;
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
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:32'],
            'password' => ['required', 'string', 'min:6'],
        ]);
        if ($validator->fails()) {
            $this->rets['code'] = 1001;
            $this->rets['message'] = $validator;
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
}
