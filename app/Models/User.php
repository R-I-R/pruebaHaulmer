<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Services\MockAPIService;
use Illuminate\Support\Facades\Hash;

class User implements Authenticatable, JWTSubject
{
	private $id;
	private $session_token;
	private $email;
	private $password;
	private $name;


	public static function loadFromValues($id, $session_token, $email, $password, $name)
	{
		$instance = new self();
		$instance->id = $id;
		$instance->session_token = $session_token;
		$instance->email = $email;
		$instance->password = $password;
		$instance->name = $name;
		return $instance;
	}

	public static function loadFromArray($data)
	{
		$instance = new self();
		$instance->id = $data['id'];
		$instance->session_token = $data['session_token'];
		$instance->email = $data['email'];
		$instance->password = $data['password'];
		$instance->name = $data['name'];
		return $instance;
	}

	public static function isUnregistered($email)
	{
		$response = MockAPIService::searchBy('users','email', $email);
		return $response->status == 404;
	}

	public static function create($email, $password, $name){

		$response = MockAPIService::insert('users', [
			'email' => $email,
			'password' => Hash::make($password),
			'name' => $name,
			'session_token' => ''	
		]);
		if($response->status == 201){
			return User::loadFromArray($response->data);
		}else{
			return null;
		}
	}

	public function getId(){
		return $this->id;
	}

	public function getEmail(){
		return $this->email;
	}

	public function getName(){
		return $this->name;
	}

	public function setEmail($email){
		$this->email = $email;
	}

	public function setName($name){
		$this->name = $name;
	}

	public function setPasswordAndHashingIt($password){
		$this->password = Hash::make($password);
	}

   /**
     * @return string
     */
    public function getAuthIdentifierName(){
		return "id";
    }

    /**
     * @return mixed
     */
    public function getAuthIdentifier(){
		return $this->id;
    }

    /**
     * @return string
     */
    public function getAuthPassword(){
		return $this->password;
    }

    /**
     * @return string
     */
    public function getRememberToken(){
		return $this->session_token;
    }

    /**
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
		$this->session_token = $value;
		MockAPIService::update('users', $this->id, ['session_token' => $value]);
	
    }

    /**
     * @return string
     */
    public function getRememberTokenName(){
		return "session_token";
	}

	/**
	 * Get the identifier that will be stored in the subject claim of the JWT.
	 *
	 * @return mixed
	 */
	public function getJWTIdentifier(){
		return $this->id;
	}

	/**
	 * Return a key value array, containing any custom claims to be added to the JWT.
	 *
	 * @return array
	 */
	public function getJWTCustomClaims(){
		return [];
	}

	public function save(){
		$response = MockAPIService::update('users', $this->id, [
			'email' => $this->email,
			'name' => $this->name,
			'password' => $this->password
		]);
		
		if($response->success()) return $this;
		else return null;
	}

	public function delete(){
		$response = MockAPIService::delete('users', $this->id);
		return $response->success();
	}

	public function toArray(){
		return ['name' => $this->name, 'email' => $this->email, 'password' => $this->password];
	}
}
