<?php
/**
 * tencent qq strategy for Opauth
 * based on http://wiki.connect.qq.com/%e4%bd%bf%e7%94%a8authorization_code%e8%8e%b7%e5%8f%96access_token
 * 
 * More information on Opauth: http://opauth.org
 * 
 * @link         http://opauth.org
 * @package      Opauth.QQStrategy
 * @license      MIT License
 */

class tencentqqStrategy extends OpauthStrategy{
	
	/**
	 * Compulsory config keys, listed as unassociative arrays
	 */
	public $expects = array('key', 'secret');
	
	/**
	 * Optional config keys with respective default values, listed as associative arrays
	 */
	public $defaults = array(
		'redirect_uri' => '{complete_url_to_strategy}qq_callback'
	);

	/**
	 * Auth request
	 */
	public function request(){
		$url = 'https://graph.qq.com/oauth2.0/authorize';
		$params = array(
			'client_id' => $this->strategy['key'],
			'redirect_uri' => $this->strategy['redirect_uri'],
			'response_type' => "code",
			'state' => "nice"
		);

		if (!empty($this->strategy['scope'])) $params['scope'] = $this->strategy['scope'];
		if (!empty($this->strategy['state'])) $params['state'] = $this->strategy['state'];
		if (!empty($this->strategy['response_type'])) $params['response_type'] = $this->strategy['response_type'];
		if (!empty($this->strategy['display'])) $params['display'] = $this->strategy['display'];
		if (!empty($this->strategy['auth_type'])) $params['auth_type'] = $this->strategy['auth_type'];
		
		$this->clientGet($url, $params);
	}
	
	/**
	 * Internal callback, after tencent's OAuth
	 */
	public function qq_callback(){
		if (array_key_exists('code', $_GET) && !empty($_GET['code'])){
			$url = 'https://graph.qq.com/oauth2.0/token';
			$params = array(
				'client_id' =>$this->strategy['key'],
				'client_secret' => $this->strategy['secret'],
				'redirect_uri'=> $this->strategy['redirect_uri'],
				'code' => $_GET['code'],       
				'grant_type' => 'authorization_code'
			);
			
			$response = $this->serverGet($url, $params,null,$headers);
			if (empty($response)){
				$error = array(
					'code' => 'Get access token error',
					'message' => 'Failed when attempting to get access token',
					'raw' => array(
						'headers' => $headers
					)
				);
				$this->errorCallback($error);
			}
			
			$response = explode("&", $response);
			$token = explode("=", $response[0]);
			$token = $token[1];
			$time  = explode("=", $response[1]);
			$time  = $time[1];
			
			$uid = $this->getuid($token);
			
            $tencentuser = $this->getuser($token,$uid); 
			
			$this->auth = array(
					'uid' => $uid,
					'info' => array(
					),
					'credentials' => array(
						'token' => $token,
						'expires' => date('c', time() + $time)
					),
					'raw' => $tencentuser
				);
			
				if (!empty($tencentuser->name)) $this->auth['info']['name'] = $tencentuser->name;
				if (!empty($tencentuser->screen_name)) $this->auth['info']['nickname'] = $tencentuser->screen_name;
				if (!empty($tencentuser->location)) $this->auth['info']['location'] = $tencentuser->location;
				if (!empty($tencentuser->avatar_large)) $this->auth['info']['image'] = $tencentuser->avatar_large;

			//Uncomment to see what tencent returns
			//debug($results);
			//debug($tencentuser);
      //debug($this->auth);	

         $this->callback();

				 // If the data doesn't seem to be written to the session, it is probably because your sessions are
				 // not set up for UTF8. The following lines will jump over the security but will allow you to use
				 // the plugin without utf8 support in the database.

         // $completeUrl = Configure::read('Opauth._cakephp_plugin_complete_url');
         // if (empty($completeUrl)) $completeUrl = Router::url('/opauth-complete');
         // $CakeRequest = new CakeRequest('/opauth-complete');
         // $data['auth'] = $this->auth;
         // $CakeRequest->data = $data;
         // $Dispatcher = new Dispatcher();
         // $Dispatcher->dispatch( $CakeRequest, new CakeResponse() );
         // exit();
		}
		else
		{
			$error = array(
				'code' => $_GET['error'],
				'message' => $_GET['error_description'],
				'raw' => $_GET
			);
			
			$this->errorCallback($error);
		}
	}


    private function getuid($access_token){
		$uid = $this->serverget('https://graph.qq.com/oauth2.0/me', array('access_token' => $access_token));
		if (!empty($uid)){
			$result = array();
            preg_match_all("/(?:\{)(.*)(?:\})/i",$uid, $result);
            $uid = explode('"',$uid);
            return $uid[7];
		} 
	  else{
			$error = array(
				'code' => 'Get UID error',
				'message' => 'Failed when attempting to query for user UID',
				'raw' => array(
					'access_token' => $access_token,	
					'headers' => $headers
				)
			);

			$this->errorCallback($error);
		} 
	}

	private function getuser($access_token,$uid){
			$tencentuser = $this->serverget('https://graph.qq.com/user/get_user_info', array('access_token' => $access_token,'openid'=>$uid,'format'=>'json','oauth_consumer_key'=> $this->strategy['key']));
			if (!empty($tencentuser)){
				return json_decode($tencentuser);
			}
			else{
			$error = array(
				'code' => 'Get User error',
				'message' => 'Failed when attempting to query for user information',
				'raw' => array(
					'access_token' => $access_token,	
					'headers' => $headers
				)
			);

			$this->errorCallback($error);
		}
} 
}
