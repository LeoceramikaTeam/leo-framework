<?php


$routes = [];
$controllers = scandir(APPPATH.'Controller');
$excludes = ['__construct', 'get_instance'];
foreach ($controllers as $controller) {
    $path = explode('.', $controller)[0];


    if( strlen($path) > 0 ) {
        $classPath = APPPATH.'Controller'.DIRECTORY_SEPARATOR.$controller;
        require_once $classPath;
        $class = 'Framework\Controller\\'.$path;
        $classs = new ReflectionClass($class);
        $methods = $classs->getMethods(ReflectionMethod::IS_PUBLIC);
        $callbacks = [];
        $routes[strtolower($path)] = $class;
    }
}

$klein = new \Klein\Klein();

$klein->respond('GET','/', function ($request)  {
    $controller = new Framework\Controller\Home();
    $id = $request->id ? $request->id : NULL;
    return $controller->base($id);
});

foreach ($routes as $path=>$callback) {
    $klein->respond('GET', '/'.$path.'/[i:id]?', function ($request) use($callback) {
        $controller = new $callback();
        $id = $request->id ? $request->id : NULL;
        return $controller->base($id);
    });
}


$klein->dispatch();