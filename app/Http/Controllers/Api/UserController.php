<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;

class UserController extends Controller
{
    public function __construct(){
		$this->middleware('auth:api', ['except' => ['login','new']]);
	}

	public function login(Request $request){
		$validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

		$credentials = $request->only(['email', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED );
        }
        return $this->respondWithToken($token);
	}

	/**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
			return response()->json(auth()->user()->toArray());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

	public function new(Request $request){
		$validator = Validator::make($request->all(), [
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255'],
			'password' => ['required', 'string', 'min:8'],
		]);
		if ($validator->fails()) {
			return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
		}
		$credentials = $request->only(['name', 'email', 'password']);
		if(!User::isUnregistered($credentials['email'])){
			return response()->json(['error' => 'Email already registered'], Response::HTTP_BAD_REQUEST);
		}

		if($usuario = User::create($credentials['email'], $credentials['password'], $credentials['name'])){
			return response()->json($usuario->toArray(), Response::HTTP_OK);
		}else{
			return response()->json(['error' => 'User not created'], Response::HTTP_BAD_REQUEST);
		}
	}

	public function update(Request $request){
		$validator = Validator::make($request->all(), [
			'name' => ['string', 'max:255'],
			'email' => ['string', 'email', 'max:255'],
			'password' => ['string', 'min:8'],
		]);
		if ($validator->fails()) {
			return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
		}
		$user = auth()->user();

		if($request->has('name')) $user->setName($request->input('name'));
		if($request->has('email')) {$user->setEmail($request->input('email'));error_log($user->getEmail()."aaaa");}
		if($request->has('password')) $user->setPasswordAndHashingIt($request->input('password'));
		//error_log(print_r($request, true));
		if($user->save()) return response()->json($user->toArray(), Response::HTTP_OK);
		else return response()->json(['error' => 'User not updated'], Response::HTTP_BAD_REQUEST);

	}

	public function delete(Request $request){
		if(auth()->user()->delete()) return response()->json(['message' => 'User deleted'], Response::HTTP_OK);
		else return response()->json(['error' => 'User not deleted'], Response::HTTP_BAD_REQUEST);
	}

	/**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
