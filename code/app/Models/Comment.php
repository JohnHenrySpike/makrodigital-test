<?
namespace App\Models;
use Model;
use OpenApi\Attributes as OA;


#[OA\Schema(
    properties:[
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'post_id', type: 'string'),
        new OA\Property(property: 'author', type: 'string'),
        new OA\Property(property: 'text', type: 'string'),
        new OA\Property(property: 'created_at', type: 'string'),
        new OA\Property(property: 'updated_at', type: 'string')
    ]
)]
class Comment extends Model {
    protected $rules = [
        'required' => [
            ['author'],
            ['text']
        ],
        'lengthMax' =>[
            ['text', 200]
        ]
    ];
    
}