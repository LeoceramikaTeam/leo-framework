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

foreach ($routes as $path=>$callback) {
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