<?
namespace App\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Controller;
use Symfony\Component\HttpFoundation\Request;

use OpenApi\Attributes as OA;
class BlogController extends Controller
{

    const COMMENT_TO_LOAD = 3;

    #[OA\Get(
        path: '/blog',
        tags: ['blog'],
        responses: [
            new OA\Response(
                response: 200, 
                description: 'OK',
                content: new OA\JsonContent(
                    properties:[
                        new OA\Property(property: 'items', type: 'array', items: new OA\Items(ref: "#/components/schemas/Post")),
                        new OA\Property(property: 'pagination', type: 'object', ref: "#/components/schemas/Paginator")
                    ]
                )
            )
        ]
    )]

    #[OA\Get(
        path: '/blog/page/{page}',
        tags: ['blog'],
        parameters: [
            new OA\Parameter('page', 'page', 'blog page', 'path', true)
        ],
        responses: [
            new OA\Response(
                response: 200, 
                description: 'OK',
                content: new OA\JsonContent(
                    properties:[
                        new OA\Property(property: 'items', type: 'array', items: new OA\Items(ref: "#/components/schemas/Post")),
                        new OA\Property(property: 'pagination', type: 'object', ref: "#/components/schemas/Paginator")
                    ]
                )
            )
        ]
    )]
    public function index(Request $request, int $page = 1){
        $data = Post::query()->with(Comment::class, '*', self::COMMENT_TO_LOAD, 'created_at', 'DESC')->paginate($page)->asArray();
        return $this->json($data);
    }

    #[OA\Get(
        path: '/blog/{id}',
        description: null,
        tags: ['blog'],
        parameters: [
            new OA\Parameter('id', 'id', 'blog post id', 'path', true)
        ],
        responses: [
            new OA\Response(
                response: 200, 
                description: 'OK',
                content: new OA\JsonContent(ref: "#/components/schemas/Post")
                )
        ]
    )]
     public function show(int $id){
        $model = Post::find($id)->asArray();
        return $this->json($model);
    }

    #[OA\Post(
        path: '/blog',
        tags: ['blog'],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                required: ["author", "text"],
                properties: [
                    new OA\Property(property: 'author', type: 'string'),
                    new OA\Property(property: 'text', type: 'string')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200, 
                description: 'OK',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'int', description: 'new post id'),
                    ]
                )
            )
        ]
    )]
    public function add(Request $request){
        $post = new Post($request->toArray());
        if ($post->validate()){
            return $this->json($post->insert());
        }else{
            return $this->json($post->getValidateErrors(), 400);
        }
        
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
                properties: [
                    new OA\Property(property: 'author', type: 'string'),
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
        return $this->json(["data" => $res]);
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


    #[OA\Get(
        path: '/blog/{id}/comments',
        description: null,
        tags: ['blog'],
        parameters: [
            new OA\Parameter('id', 'id', 'blog post id', 'path', true)
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK')
        ]
    )]
     public function show_comments(int $id){
        $model = Comment::query()->where(["post_id", "=", $id])->get()->asArray();
        return $this->json($model);
    }
}
