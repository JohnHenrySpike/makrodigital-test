<?
namespace App\Controllers;

use Controller;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[OA\SecurityScheme(
    securityScheme: 'auth',
    type: 'oauth2',
    flows: [
        new OA\Flow(
            flow: 'password',
            tokenUrl: '/login',
            scopes: []
        )
    ]
)]
#[OA\Info(
    title: 'App API',
    version: '0.1'
)]

class IndexController extends Controller
{
    #[OA\Get(
        path: '/',
        responses: [
            new OA\Response(response: 200, description: 'OK')
        ]
    )]
    public function index(){
        return new Response("index page");
    }

    public function login(Request $request){
        $data = $request->request->all();
        if (isset($data["username"]) && isset($data["password"])){
            if($data["username"] == "user" && $data["password"] == "123456"){
                $r = [
                    "access_token" => md5($data["username"].$data["password"].microtime()),
                    "token_type" => "bearer",
                    "expires_in" => 3600,
                    "scope" => []
                ];
                return $this->json($r);
            }
        }
        return new JsonResponse(["error"=>"wrong credentials"], 401);
    }

    public function error(\Exception $exception, $logger = null){
        return $this->json(
            [
                "error_code" => $exception->getCode(),
                "error_message" => $exception->getMessage(),
                "error" => explode("\n", $exception->getTraceAsString())
            ]
        );
    }
}