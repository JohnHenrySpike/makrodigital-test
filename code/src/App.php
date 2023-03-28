<?

use App\Controllers\IndexController;
use OpenApi\Annotations as OA;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\ErrorListener;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

class App {
 
    public function run(){
        include __DIR__."/../config/routes.php";

        $request = Request::createFromGlobals();

        $matcher = new UrlMatcher(Route::getCollection(), new RequestContext());
                
        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new RouterListener($matcher, new RequestStack()));
        $dispatcher->addSubscriber(new ErrorListener([IndexController::class, 'error']));

        $kernel = new HttpKernel($dispatcher, new ControllerResolver());
        $response = $kernel->handle($request)->send();
        $kernel->terminate($request, $response);
    }
}