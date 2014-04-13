<?php namespace Escapeboy\Disqus;
 
class Disqus {

	/**
	*
	* Get data from Disqus API
	* (string) $section required
	* (string) $method required
	* (array) $params
	*
	**/

	public function get($section, $method, $params=array()){

	  	if(!isset($params['thread']) && !isset($params['thread:ident']) && !isset($params['thread:link'])){
	  		throw new Exception("You must specify at least one of thread or thread:ident or thread:link", 1);
	  	}
	  	if(!isset($params['forum'])){
	  		$params['forum'] = \Config::get('disqus::forum');
	  	}

	  	$endpoint = 'http://disqus.com/api/'.\Config::get('disqus::api_version').'/'.$section.'/'.$method.'.json?api_key='.urlencode(\Config::get('disqus::api_key'));
	  	foreach ($params as $key => $value) {
	  		$endpoint.='&'.$key.'='.urlencode($value);
	  	}

	  	$result = \Cache::remember(md5($endpoint), \Config::get('disqus::cache_time'), function() use ($endpoint){
	  		$session = curl_init($endpoint);
	  		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
	  		$result = curl_exec($session);
	  		curl_close($session);
	  		return $result;
	  	});

	  	return $result;
	}

	/**
	*
	* Send data to Disqus API
	* (string) $section required
	* (string) $method required
	* (array) $params
	*
	**/
	public function post($section, $method, $params=array()){

		foreach ($params as $key => $value) {
			$vars.=$key.'='.$value.'&';
		}
		$endpoint = 'http://disqus.com/api/'.\Config::get('disqus::api_version').'/'.$section.'/'.$method.'.json?api_key='.urlencode(\Config::get('disqus::api_key'));

		$session = curl_init($endpoint);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($session, CURLOPT_POST, count($params));
		curl_setopt($session, CURLOPT_POSTFIELDS, $vars);
		
		$result = curl_exec($session);
		curl_close($session);

		return $result;
	}


	/**
	 * Return total comments in thread
	 * (string) $thread_url required
	 */
	public function commentsCount($thread_url)
	{
		$thread = self::getThread($thread_url);
		return $thread->response->posts;
	}

	/**
	 * Return thread details
	 * (string) $thread_url required
	 */
	public function getThread($thread_url)
	{
		return json_decode($this->get('threads', 'details', array('thread:link' => $thread_url)));
	}
}