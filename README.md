Laravel 4 Disqus Package
======

Simple package to work with Disqus

Installation
======
With composer:
```
{
    ...
    "require": {
        "escapeboy/disqus": "dev-master"
    }
}
```
Register in `app/config/app.php`
```php
'providers' => array(
    'Escapeboy\Disqus\DisqusServiceProvider',
)
```

Configuration
======
Publish configuration file
```
php artisan config:publish escapeboy/disqus
```
In `app/config/packages/escapeboy/disqus/config.php` edit configuration file:
```php
return array(
		'api_key' => '', // your disqus api key
		'api_secret' => '', // your disqus secret
		'api_version' => '3.0', // disqus API version used. Do not change it
		'cache_time' => 60, // cache time in minutes used to cache results
		'forum'	=> '' // your disqus forum (shortname)
	);
```

Usage
======

For example we want to get from API info for some thread
We want section "thread", sub-section "details". 
And we provide "thread:link" (can use "thread:ident" or "thread")
It will return json.
More info here:
[http://disqus.com/api/docs/threads/details/](http://disqus.com/api/docs/threads/details/)
```php
// Disqus::get($section, $method, $params=array()
$thread = Disqus::get('threads', 'details', array('thread:link' => 'http://thread_url'));
```

Some shorthand functions
======
```php
$comment_count = Disqus::commentsCount('http://thread_url'); // returns integer of comments count for given url
```
*... more functions comming in next releases*


