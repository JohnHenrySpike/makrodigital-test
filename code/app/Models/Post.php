<?
namespace App\Models;

use Model;
use Traits\Paginate;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties:[
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'author', type: 'string'),
        new OA\Property(property: 'text', type: 'string'),
        new OA\Property(property: 'created_at', type: 'string'),
        new OA\Property(property: 'updated_at', type: 'string')
    ]
)]

class Post extends Model
{
    use Paginate;

    protected $rules = [
        'required' => [
            ['author'],
            ['text']
        ],
        'lengthMax' =>[
            ['text', 5000]
        ]
    ];

    protected function boot(){
        $this->setPerPage(2);
    }

    
}
