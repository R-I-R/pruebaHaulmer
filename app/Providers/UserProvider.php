<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\UserProvider as IlluminateUserProvider;
use App\Services\MockAPIService;
use \Illuminate\Contracts\Auth\Authenticatable;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserProvider implements IlluminateUserProvider
{
	/**
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        // Get and return a user by their unique identifier
		$response = MockAPIService::getById('users', $identifier);

		if($response->success()) return User::loadFromArray($response->data);
		else return null;
	
    }

    /**
     * @param  mixed   $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        // Get and return a user by their unique identifier and "remember me" token
		$response = MockAPIService::getById('users', $identifier);
		if($response->success()){
			if($response->data['session_token'] == $token) return User::loadFromArray($response->data);
		}
		return null;
    }

    /**
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        // Save the given "remember me" token for the given user
		$user->setRememberToken($token);
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        // Get and return a user by looking up the given credentials
		if(!isset($credentials['email']) || !isset($credentials['password'])) return null;
		$response = MockAPIService::searchBy('users','email', $credentials['email']);
		if($response->success() && $response->data){
			//error_log(print_r($response->data, true));
			if(Hash::check($credentials['password'],$response->data[0]['password'])) return User::loadFromArray($response->data[0]);
		}

		return null;
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // Check that given credentials belong to the given user
		if(!isset($credentials['email']) || !isset($credentials['password'])) return false;
		
		if(Hash::check($credentials['password'],$user->getAuthPassword()) && $user->getEmail() == $credentials['email']) return true;
		return false;
    }
    
}
