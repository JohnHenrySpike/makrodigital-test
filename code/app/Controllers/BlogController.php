<?
namespace App\Controllers;

use App\Models\Post;
use Controller;
use Symfony\Component\HttpFoundation\Request;

use OpenApi\Attributes as OA;
class BlogController extends Controller
{
    #[OA\Get(
        path: '/blog',
        tags: ['blog'],
        responses: [
            new OA\Response(response: 200, description: 'OK')
        ]
    )]
    public function index(int $page = 1){
        $data = Post::query()->paginate($page)->asArray();
        return $this->json(["data"=>$data]);
    }

    #[OA\Get(
        path: '/blog/{id}',
        description: null,
        tags: ['blog'],
        parameters: [
            new OA\Parameter('id', 'id', 'blog post id', 'path', true)
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK')
        ]
    )]
     public function show(int $id){
        $model = Post::find($id);
        return $this->json($model->getAttributes());
    }

    #[OA\Post(
        path: '/blog',
        tags: ['blog'],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                required: ["title", "text"],
                properties: [
                    new OA\Property(property: 'title', type: 'string'),
                    new OA\Property(property: 'text', type: 'string')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200, 
                description: 'OK',
                content: new OA\JsonContent()
            )
        ]
    )]
    public function add(Request $request){
        $post = new Post($request->toArray());
        $res = $post->insert();
        return $this->json(["data"=>$res]);
    }   
    
    
    #[OA\Put(
        path: '/blog/{id}',
        tags: ['blog'],
        security:['auth'],
        parameters: [
            new OA\Parameter('id', 'id', 'blog post id', 'path', true)
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                required: ["title", "text"],
                properties: [
                    new OA\Property(property: 'title', type: 'string'),
                    new OA\Property(property: 'text', type: 'string')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200, 
                description: 'OK',
                content: new OA\JsonContent()
            )
        ]
    )]
    public function update(Request $request, int $id){
        $model = Post::find($id);
        $model->fill($request->toArray());
        $res = $model->update();
        return $this->json(["data" => $id, "model"=>$res]);
    }


    #[OA\Delete(
        path: '/blog/{id}',
        description: null,
        tags: ['blog'],
        parameters: [
            new OA\Parameter('id', 'id', 'blog post id', 'path', true)
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK')
        ]
    )]
     public function delete(int $id){
        $model = Post::find($id);
        $res = $model->delete();
        return $this->json(["data"=>$res]);
    }
}
