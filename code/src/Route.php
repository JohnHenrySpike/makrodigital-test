<?

use Symfony\Component\Routing\Route as SymfonyRoute;
use Symfony\Component\Routing\RouteCollection;

class Route{

    private static array $routes = [];

    public static function get(string $uri, array|callable $action, $defaults = []){
        self::addRoute('GET', $uri, $action, $defaults);
    }

    public static function post(string $uri, array|callable $action, $defaults = []){
        self::addRoute('POST', $uri, $action, $defaults);
    }

    public static function put(string $uri, array|callable $action, $defaults = []){
        self::addRoute('PUT', $uri, $action, $defaults);
    }

    public static function delete(string $uri, array|callable $action, $defaults = []){
        self::addRoute('DELETE', $uri, $action, $defaults);
    }

    private static function addRoute(string $method, string $uri, array|callable $action, $defaults = []){
        $r = new SymfonyRoute($uri, array_merge(["_controller"=>$action], $defaults), methods:$method);
        $name = $method.":".$uri;
        self::$routes[$name] = $r;
    }

    public static function getRoutes(){
        return self::$routes;
    }

    public static function getCollection():RouteCollection{
        $collection = new RouteCollection();
        foreach(self::$routes as $n=>$route){
            $collection->add($n, $route);
        }
        self::$routes = [];
        return $collection;
    }
}