<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MockAPIResponse{
	public $status;
	public $data;

	public function __construct($status, $data){
		$this->status = $status;
		$this->data = $data;
		if(!$this->data) $this->status = 404;
	}

	public function success(){
		return ($this->status == 200 || $this->status == 201);
	}
}

class MockAPIService{

	static private $baseUrl = 'https://61ae1a98a7c7f3001786f609.mockapi.io/';

	static private function query($HttpFunction, $url, $data = null){
		try{
			$response = Http::$HttpFunction($url, $data);
			return new MockAPIResponse($response->status(), $response->json());
		}catch(Exception $e){
			return new MockAPIResponse(500, null);
		}
	}

	static public function getAll($table){
		return self::query('get',self::$baseUrl.$table);
	}

	static public function getById($table, $id){
		return self::query('get',self::$baseUrl.$table.'/'.$id);
	}

	static public function searchBy($table, $field, $value){
		return self::query('get',self::$baseUrl.$table.'?'.$field.'='.$value);
	}

	static public function insert($table, $data){
		return self::query('post',self::$baseUrl.$table, $data);
	}

	static public function update($table, $id, $data){
		return self::query('put',self::$baseUrl.$table.'/'.$id, $data);
	}

	static public function delete($table, $id){
		return self::query('delete', self::$baseUrl.$table.'/'.$id);
	}
	
}