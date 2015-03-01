<?php
require_once 'HTTP/Request2.php';


class LockitronError extends Exception { }

class LockitronAPI {
    private $api;

    //private $auth_id;

    private $access_token;

    function __construct($access_token, $url="https://api.lockitron.com", $version="v2") {

        if ((!isset($access_token)) || (!$access_token)) {
            throw new LockitronError("no access_token");
        }
        $this->version = $version;
        $this->api = $url."/".$this->version;
        $this->access_token = $access_token;
    }

    private function request($method, $path, $params=array()) {
        $url = $this->api.rtrim($path, '/');
		$params["access_token"] = $this->access_token;
        if (!strcmp($method, "POST")) {
            $req = new HTTP_Request2($url, HTTP_Request2::METHOD_POST);
			$url = $req->getUrl();
            $url->setQueryVariables($params);
        } else if (!strcmp($method, "GET")) {
            $req = new HTTP_Request2($url, HTTP_Request2::METHOD_GET);
            $url = $req->getUrl();
            $url->setQueryVariables($params);
        } else if (!strcmp($method, "DELETE")) {
            $req = new HTTP_Request2($url, HTTP_Request2::METHOD_DELETE);
            $url = $req->getUrl();
            $url->setQueryVariables($params);
        } else if (!strcmp($method, "PUT")) {
            $req = new HTTP_Request2($url, HTTP_Request2::METHOD_PUT);
            $url = $req->getUrl();
            $url->setQueryVariables($params);
        }
        $req->setAdapter('curl');
        $req->setConfig(array('timeout' => 30));
        $req->setHeader(array(
            'Connection' => 'close',
            'User-Agent' => 'PHPLockitron'
        ));
        $r = $req->send();
        $status = $r->getStatus();
        $body = $r->getbody();
        $response = json_decode($body, true);
        return array("status" => $status, "response" => $response);
    }

    ## Locks ##
    public function get_locks() {
        return $this->request('GET', '/locks');
    }
	
	public function get_lock($lock) {
        return $this->request('GET', '/locks/'.$lock);
	}
	
	public function modify_lock_attribute($lock, $params=array()) {
        return $this->request('PUT', '/locks/'.$lock, $params);
	}
	
	## Keys ##
	public function get_keys_for_lock($lock) {
        return $this->request('GET', '/locks/'.$lock.'/keys');
	}
	
	public function create_key_for_lock($lock, $params=array()) {
        return $this->request('POST', '/locks/'.$lock.'/keys', $params);
	} 

	public function modify_key_for_lock($key, $lock, $params=array()) {
        return $this->request('PUT', '/locks/'.$lock.'/keys/'.$key, $params);
	}
	public function revoke_key_for_lock($key, $lock) {
        return $this->request('DELETE', '/locks/'.$lock.'/keys/'.$key);
	}
	
	## Users ##
	public function get_user($user) {
        return $this->request('GET', '/users/'.$user);
	}
	
	public function get_current_user() {
        return $this->request('GET', '/users/me');
	}
	
	## Activities ##
	public function get_activities_for_lock($lock) {
        return $this->request('GET', '/locks/'.$lock.'/activity');
	}
	
	public function cancel_pending_activity_for_lock($activity, $lock) {
        return $this->request('DELETE', '/locks/'.$lock.'/activity/'.$activity);
	}
}

?>