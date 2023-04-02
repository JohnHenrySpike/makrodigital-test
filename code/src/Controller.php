<?

use Symfony\Component\HttpFoundation\JsonResponse;

abstract class Controller {

    protected function json($data, int $status = 200){
        return new JsonResponse($data, $status);
    }
}