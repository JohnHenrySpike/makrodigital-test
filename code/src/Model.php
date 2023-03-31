<?
use Contracts\Arrayable;
abstract class Model implements Arrayable
{

    protected $key = 'id';

    protected string $table;

    protected array $attributes = [];

    private const CREATED_AT = 'created_at';
    private const UPDATED_AT = 'updated_at';

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
        $this->boot();
    }

    protected function boot()
    {
        //
    }

    public function fill($attributes)
    {
        $this->attributes = array_merge($this->attributes, $attributes);
    }

    public static function find(int $id)
    {
        return (new static )->newQuery()->find($id)->first();
    }

    public static function findAll()
    {
        return (new static )->newQuery()->get()->all();
    }

    public static function all(string $columns = '*'){
        return static::query()->get($columns);
    }

    public static function query(){
        return (new static )->newQuery();
    }

    public function insert()
    {
        return $this->newQuery()->insert($this->attributes);
    }

    public function update()
    {
        $this->setUpdateTime();
        return $this->newQuery()->update($this->attributes["id"], $this->attributes);
    }

    public function delete()
    {
        return $this->newQuery()->delete($this->attributes["id"]);
    }

    private function newQuery()
    {
        $qb = new QueryBuilder();
        $qb->setModel($this);
        return $qb;
    }

    public function newInstance($attributes){
        $model = new static();

        $model->setTable($this->getTable());

        $model->fill($attributes);

        return $model;
    }

    public function newCollection(array|null $models = []){
        return new Collection($models);
    }

    public function asArray(){
        return $this->attributes;
    }

    private function setUpdateTime(){
        $time = new \DateTime();
        $time->setTimezone(new \DateTimeZone('Asia/Almaty'));
        $this->attributes[self::UPDATED_AT] = $time->format(DateTime::ATOM);
    }

    public function getTable()
    {
        return $this->table ?? strtolower(Helpers::classBaseName($this) . "s"); //TODO: ".s" need check
    }

    /**
     * @param string $table 
     * @return self
     */
    public function setTable(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key 
     * @return self
     */
    public function setKey($key): self
    {
        $this->key = $key;
        return $this;
    }

    public function __set($key, $value)
    {
        return $this->setAttribute($key, $value);
    }

    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    public function getAttribute($key){
		return isset($this->attributes[$key])?$this->attributes[$key]:null;
	}

    public function setAttribute($key, $value){
		$this->attributes[$key] = $value;
	}

	/**
	 * @return array
	 */
	public function getAttributes(): array {
		return $this->attributes;
	}
}