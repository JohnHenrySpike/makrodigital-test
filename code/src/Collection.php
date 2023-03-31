<?
use Contracts\Arrayable;

class Collection implements Arrayable{


    protected array $items = [];

    public function __construct(array|null $items = []){
        $this->items = $items;
    }

    public function all(){
        return $this->items;
    }

    public function get($key){
        
    }

    public function first(){
        return count($this->items)>0 ? $this->items[0] : null;
    }

    public function asArray(){
        return $this->map(fn ($value) => $value instanceof Arrayable ? $value->asArray() : $value)->all();
    }

    public function map(callable $callback)
    {
        return new static(Helpers::map($this->items, $callback));
    }

}