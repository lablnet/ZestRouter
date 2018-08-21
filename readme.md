# ZestRouter

ZestRouter is a small but powerful routing class for php

```php

<?php 

use Lablnet\ZestRouter;

require_once "../vendor/autoload.php";

$router = new ZestRouter;

//Namespaces uses for loading controllers olny
//$router->setDefaultNamespace("App\Controllers\\");

$router->get('', function () {
    echo 'Example route using closure';
});
/*
//OR
$router->add('', function () {
    echo 'Example route using closure';
},'GET');
*/
$router->get('test','Home@index');
/*
 //OR
 $router->get('test',['controller' => 'Home', 'action' => 'index']);
 //OR
  $router->add('test',['controller' => 'Home', 'action' => 'index'],'GET');

*/
//Dispatch/Process the request automatically for mannually dispatch request take a look at Process Request section
$router->dispatch($_SERVER['QUERY_STRING']);

```

# Features

- Can be used with all HTTP Methods
- Flexible regular expression routing
- Custom regexes
- Router with controllers by namespaces
- Routers using closure

# Getting started

## Requirements

- PHP 7 or newest
- Composer

## Rewrite all requests

### Apache (.htaccess)

```
# Remove the question mark from the request but maintain the query string
RewriteEngine On

# Uncomment the following line if your public folder isn't the web server's root
# RewriteBase /

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-l
RewriteRule ^(.*)$ index.php?$1 [L,QSA]

```

### Nginx (nginx.conf)

```

location / { 
    if (!-f $request_filename){
        set $rule_0 1$rule_0;
    }   
    if (!-d $request_filename){
        set $rule_0 2$rule_0;
    }
    if ($rule_0 = "21"){
        rewrite ^/(.*)$ /index.php?$1 last;
    }   
}

```

## Adding Routes

By now, you should have rewrite all requests

### simple (default way)
there are many to two ways to add the routes

#### Using add method
```php

    $router->add('', function () {
        echo "Welcome";
    },'GET'); 

```
The `add()` method accepts the following parameters.

`$route` (string)
This is the route pattern to match against. This can be a plain string, a custom regex.

`$params` (array|string|Closure)
This is paramter for controllers and controller method or closure
(array) => ['controller' => 'Home', 'action' => 'index'] 
(string) => "Home@index" 
(closure) => function () { echo "Welcome"; }

`$method` (string)
This is a pipe-delimited string of the accepted HTTP requests methods.

Example: GET|POST|PATCH|PUT|DELETE

#### Using rests method
```php

    $router->get('', function () {
        echo "Welcome";
    }); 

```
there are 5 request methods supports
`get(),post(),put(),patch(),delete()``

These methods accepts the following parameters.

`$route` (string)
This is the route pattern to match against. This can be a plain string, a custom regex.

`$params` (array|string|Closure)
This is paramter for controllers and controller method or closure
(array) => ['controller' => 'Home', 'action' => 'index'] 
(string) => "Home@index" 
(closure) => function () { echo "Welcome"; }

## Example adding the routes

```php

// add homepage using callable
$router->get( '/home', function() {
    require __DIR__ . '/views/home.php';
});

// add users details page using controller@action string
$router->get( 'users/{id:[0-9]+}', 'UserController@showDetails' );

```
For quickly adding multiple routes, you can use the addRoutes method. This method accepts an array or any kind of traversable.

```php
$router->addRoutes(array(
  array('users/{id:[0-9]+}', 'users@update', 'PATCH'),
  array('users/{id:[0-9]+}', 'users@delete', 'DELETE')
));
```


# Matching Requests

To match the current request, just call the `customDispatch()` method without any parameters.

``` php
$match = $router->customDispatch();
```

# Processing Requests

ZestRouter process requests for you but so you are free to use the method you prefer. To help you get started, here's a simplified example using closures.

```php 

//Add the routes
$router->get('', function () {
    echo 'Example route using closure';
});
$router->get('user/{id:[0-9]+}', function ($args) {
    echo 'Example route using closure with params id: ' .$args['id'];
});

// match current request url
$match = $router->customDispatch();
if ($match && is_callable( $match['callable'] )) {
	call_user_func( $match['callable'], $match ); 
} else {
	// no route was matched
	echo '404 Not Found';
}

```