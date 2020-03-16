<?php

$reader = new \Doctrine\Common\Annotations\AnnotationReader();
$klein = new \Klein\Klein();
$routes = [];
$controllers = scandir(APPPATH.'Controller');

foreach ($controllers as $controller) {
    $path = explode('.', $controller)[0];
    if( strlen($path) > 0 ) {
        $classPath = APPPATH.'Controller'.DIRECTORY_SEPARATOR.$controller;
        require_once $classPath;
        $class = 'Framework\Controller\\'.$path;
        $routes[strtolower($path)] = $class;
    }
}

function registerRoute($method, $class, $methodName) {
    global $klein;
    $path = $method->path;
    $httpMethod = !empty($method->methods) ? $method->methods : "GET";
    if(property_exists($method, 'params')) {
        if(!empty($method->params)) {
            foreach($method->params as $key=>$type) {
                if($type == 'integer' or $type == 'int' or $type == 'i') $type = 'i';
                else $type = '';
                $path .= '/['.$type.':'.$key.']?';
            }
        }
    }
    $klein->respond($httpMethod, $path, function ($request) use($class, $methodName ) {
        $params = $request->paramsNamed();
        $controller = new $class();
        return $controller->{$methodName}($params);
    });
}


foreach ($routes as $path=>$callback) {
    $class = new ReflectionClass($callback);

    $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);

    foreach($methods as $method) {
        if(count($route = $reader->getMethodAnnotations($method)) > 0) {
            registerRoute($route[0], $callback, $method->name);
        }
    }

    $callbacks = [];
}
$klein->dispatch();