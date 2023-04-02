<?
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

    private $env;

    private $errorHandler;

    private function registerRoutes(){
        include __DIR__."/../config/routes.php";
    }

    private function loadConfig(){
        $conf = require(__DIR__."/../config/app.php");
        if (!empty($conf)){
            foreach ($conf as $key => $value) {
                $this->$key = $value;
            }
        }
    }

 
    public function run(){

        $this->loadConfig();
        $this->registerRoutes();

        $request = Request::createFromGlobals();

        $matcher = new UrlMatcher(Route::getCollection(), new RequestContext());
                
        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new RouterListener($matcher, new RequestStack()));
        $dispatcher->addSubscriber(new ErrorListener($this->getErrorHandler()));

        $kernel = new HttpKernel($dispatcher, new ControllerResolver());
        $response = $kernel->handle($request)->send();
        $kernel->terminate($request, $response);
    }

	/**
	 * @return mixed
	 */
	private function getErrorHandler() {
		return $this->errorHandler ?? [\App\Controllers\IndexController::class, 'error'];
	}
}