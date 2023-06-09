<?
namespace App\Controllers;
use App\Models\Comment;
use Controller;
use Symfony\Component\HttpFoundation\Request;

use OpenApi\Attributes as OA;
class CommentController extends Controller{

    #[OA\Post(
        path: '/comment/{post_id}',
        tags: ['comment'],
        parameters: [
            new OA\Parameter('post_id', 'post_id', 'blog post id', 'path', true)
        ],
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
                        new OA\Property(property: 'data', type: 'int', description: 'new comment id'),
                    ]
                )
            )
        ]
    )]
    public function add(Request $request, int $post_id){
        $comment = new Comment($request->toArray());
        $comment->setAttribute("post_id", $post_id);
        if ($comment->validate()){
            return $this->json($comment->insert());
        }else{
            return $this->json($comment->getValidateErrors(), 400);
        }
    }

    #[OA\Put(
        path: '/comment/{id}',
        tags: ['comment'],
        security:['auth'],
        parameters: [
            new OA\Parameter('id', 'id', 'comment id', 'path', true)
        ],
        requestBody: new OA\RequestBody(
                content: new OA\JsonContent(
                properties: [
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
        $model = Comment::find($id);
        $model->fill($request->toArray());
        $res = $model->update();
        return $this->json(["data" => $res]);
    }
}