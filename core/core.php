<?php 

    class Core {
        public function run($routes) {
            $url = '/';

            isset($_GET['url']) ? $url .= $_GET['url'] : '';

            ($url !='/') ? $url = rtrim($url, '/') : $url; 

            foreach($routes as $path => $controller) {
                $pattern = '#^'.preg_replace('/{id}/', '(\w+)', $path).'$#';

                $routerFound = false;

                if(preg_match($pattern, $url, $matches)) {
                    array_shift($matches);

                    $routerFound = true;

                    [$currentController, $action] = explode('@', $controller);

                    require_once __DIR__."/../controllers/$currentController.php";

                    $newController = new $currentController();
                    $newController->$action($matches);
                }
            }

            if(!$routerFound) {
                require_once __DIR__."/../controllers/NotFoundController.php";
                $controller = new NotFoundController();
                $controller->index();
            }
        }
    }

?>