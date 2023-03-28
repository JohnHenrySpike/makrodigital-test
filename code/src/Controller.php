<?

use Symfony\Component\HttpFoundation\JsonResponse;

abstract class Controller {

    protected function json($data){
        return new JsonResponse($data);
    }
}