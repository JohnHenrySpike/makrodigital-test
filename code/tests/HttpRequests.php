<?
namespace Tests;

use App;
use Route;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\ErrorListener;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

/**
 * Summary of HttpRequests
 */
trait HttpRequests {
    private function call(string $method, string $uri, array $parameters = []): Response{
        App::registerRoutes();
        $request = Request::create($uri, $method, $parameters, [], []);

        $matcher = new UrlMatcher(Route::getCollection(), new RequestContext());
                
        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new RouterListener($matcher, new RequestStack()));
        $dispatcher->addSubscriber(new ErrorListener([IndexController::class, 'error']));

        $kernel = new HttpKernel($dispatcher, new ControllerResolver());
        $response = $kernel->handle($request);
        $kernel->terminate($request, $response);
        return $response;
        
    }

    public function get(string $uri){
        return $this->call('GET', $uri);
    }

    public function post(string $uri, array $data = [])
    {
        return $this->call('POST', $uri, $data);
    }
}