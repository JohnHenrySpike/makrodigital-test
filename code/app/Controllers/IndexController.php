<?
namespace App\Controllers;

use Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Info(
 *     title="App API",
 *     version="0.1"
 * )
 */

class IndexController extends Controller
{
    /**
     * @OA\Get(
     *     path="/",
     *     @OA\Response(
     *         response="200",
     *         description="The data"
     *     )
     * )
     */

    public function index(){
        return new Response("index page");
    }

    public function error(Request $request){
        /**
         * @var \Symfony\Component\HttpKernel\Exception\HttpException $e
         */
        $e = $request->attributes->get('exception');
        return new Response($e->getMessage(), $e->getStatusCode());
    }
}