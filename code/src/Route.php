<?

use Symfony\Component\Routing\Route as SymfonyRoute;
use Symfony\Component\Routing\RouteCollection;

class Route{

    private static array $routes = [];

    public static function get(string $uri, array|callable $action){
        self::addRoute($uri, $action, 'GET');
    }

    private static function addRoute(string $uri, array|callable $action, string $method){
        $r = new SymfonyRoute($uri, ["_controller"=>$action], methods:$method);
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
        return $collection;
    }
}