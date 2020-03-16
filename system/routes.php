<?php

$reader = new \Doctrine\Common\Annotations\AnnotationReader();
$klein = new \Klein\Klein();

$routes = [];
$controllers = scandir(APPPATH.'Controller');
$excludes = ['__construct', 'get_instance'];

foreach ($controllers as $controller) {
    $path = explode('.', $controller)[0];
    if( strlen($path) > 0 ) {
        $classPath = APPPATH.'Controller'.DIRECTORY_SEPARATOR.$controller;
        require_once $classPath;
        $class = 'Framework\Controller\\'.$path;
        $routes[strtolower($path)] = $class;
    }
}

function registerRoute($method) {
    global $klein;
    d($method);
}


foreach ($routes as $path=>$callback) {
    $class = new ReflectionClass($callback);
    $methods = array_map(function($elem) {
        if($elem->name!='__construct' && $elem->name!='get_instance') return $elem;
    }, $class->getMethods(ReflectionMethod::IS_PUBLIC));
    $methods = array_diff($methods, array(''));
    array_map("registerRoute", $methods);
    foreach($methods as $method) {
        echo "<pre>".print_r($reader->getMethodAnnotations($method), TRUE).'</pre>';
    }
    $callbacks = [];
    $klein->respond('GET', '/'.$path.'/[i:id]?', function ($request) use($callback) {

        $controller = new $callback();
        return $controller->base($request->id);
    });
}
$klein->respond('/posts/[create|edit:action]?/[i:id]?', function ($request, $response) {
    echo '<pre>';
    print_r($request);
});
$klein->dispatch();