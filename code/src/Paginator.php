<?
use Contracts\Arrayable;
use OpenApi\Attributes as OA;


#[OA\Schema(
    properties:[
        new OA\Property(property: 'total', type: 'integer'),
        new OA\Property(property: 'perPage', type: 'integer'),
        new OA\Property(property: 'currentPage', type: 'integer'),
        new OA\Property(property: 'pages', type: 'integer'),
        new OA\Property(property: 'options', type: 'object')
    ]
)]

class Paginator implements Arrayable{

    private Collection $items;
    private $total;
    private $perPage;
    private $currentPage;
    private $options;

    public function __construct($items = new Collection(), $total = null, $perPage = null, $currentPage = null, $options = null){
        $this->items = $items;
        $this->total = $total;
        $this->perPage = $perPage;
        $this->currentPage = $currentPage;
        $this->options = $options;
    }

    public function asArray(){
        return [
            "items" => $this->items->asArray(),
            "pagination" => [
                "total" => $this->total,
                "perPage" => $this->perPage,
                "currentPage" => $this->currentPage,
                "pages" => ceil($this->total/$this->perPage),
                "options" => $this->options
            ]
        ];
    }
}