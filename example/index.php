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
