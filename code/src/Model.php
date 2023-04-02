<?
use Contracts\Arrayable;
/**
 * Summary of Model
 */
abstract class Model implements Arrayable
{

    protected $key = 'id';

    protected string $table;

    protected array $attributes = [];

    protected $relations = [];

    protected $validateErrors = [];

    protected $rules = [];

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

    public function newQuery()
    {
        $qb = new QueryBuilder();
        $qb->setModel($this);
        return $qb;
    }

    public function newInstance($attributes){
        $model = new static;
        $model->fill($this->attributes);
        $model->setTable($this->getTable());

        $model->fill($attributes);

        return $model;
    }

    public function newCollection(array|null $models = []){
        return new Collection($models);
    }

    public function asArray(){
        return array_merge($this->attributes, $this->relationsToArray());
    }

    public function addRelation($name, $value){
        $this->relations[$name] = $value;
    }

    public function relationsToArray(){
        $ar_relations = [];
        foreach ($this->relations as $key=>$rel) {
            if ($rel instanceof Arrayable) $ar_relations[$key] = $rel->asArray();
        }
        return $ar_relations;
    }

    public function validate(){
        $v = new Valitron\Validator($this->attributes);
        $v->rules($this->rules);
        if (!$res = $v->validate()) {
            $this->validateErrors = $v->errors();
        }
        return $res;
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
     * @return string
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
	public function getValidateErrors() {
		return $this->validateErrors;
	}

	/**
	 * @return array
	 */
	public function getRules() {
		return $this->rules;
	}
	
	/**
	 * @param array $rules 
	 * @return self
	 */
	public function setRules($rules): self {
		$this->rules = $rules;
		return $this;
	}
}