<?
use Doctrine\DBAL\ParameterType;
/**
 * Summary of QueryBuilder
 */
class QueryBuilder {

    /**
     * @var \Model
     */
    private $model;

    private $query;

    private $connection;

    private $paginator = 'paginator';

    private array $relations = [];

    public function __construct(){
        $this->connection = Db::getConnection();
        $this->query = $this->connection->createQueryBuilder();
    }

    /**
     * Find model by id
     * @param mixed $id
     * @param string $columns
     * @return Collection
     */
    public function find($id, string $columns = '*'){
        $this->query        
        ->where($this->model->getKey(). '= :id')
        ->setParameter('id', $id);
        return $this->get($columns);
    }

    /**
     * @param array $condition [ "author", "=", "some_name" ]
     * @param string $columns
     * @return self
     */
    public function where(array $condition, string $columns = '*'){
        $this->query        
        ->where( $condition[0]." ". $condition[1]. ' ?')
        ->setParameter(0, $condition[2]);
        return $this;
    }

    public function sort(string $column, $order = null){
        $this->query->orderBy($column, $order);
        return $this;
    }

    public function limit(int|null $limit = null){
        if ($limit) {
            $this->query->setMaxResults($limit);
        }
        return $this;
    }

    /**
     * Query executor
     * @param string $columns
     * @return Collection
     */
    public function get(string $columns = '*'){

        $models = $this->getModels($columns);
        
        if (!empty($this->relations)) {
            $models = $this->loadRelations($models);
        }
        return $models;
    }


    /**
     * Summary of loadRelations
     * @param Collection $models
     * @return Collection
     */
    private function loadRelations($models){
        /**
         * @var \Model $model
         * @var \Model $rmodel
         */
        foreach ($models = $models->all() as &$model) {
            foreach ($this->relations as $relation=>$rparam) {
                /**
                 * @var \Model $instance 
                 */
                $instance = new $relation;
                $related_models = $instance->newQuery()
                    ->collect(
                        $instance->newQuery()
                            ->where($model->newQuery()->getWithCondition($instance))
                            ->limit($rparam['limit'])
                            ->sort($rparam['sort'], $rparam['order'])
                            ->query($rparam['columns'])
                    );
                $model->addRelation(strtolower(Helpers::classBaseName($relation))."s", $instance->newCollection($related_models));
            }    
        }
        return $this->getModel()->newCollection($models);
    }

    public function getModels(string $columns = '*'){
        return $this->getModel()->newCollection($this->collect($this->query($columns)));
    }

    private function query(string $columns = '*'){
        $this->query->select($columns)->from($this->model->getTable());
        return $this->query->executeQuery()->fetchAllAssociative();
    }

    private function collect(array $items = []){
        if (empty($items)){
            return [];
        }
        $models = [];
        foreach ($items as $item) {
            $models[] = $this->getModel()->newInstance($item);
        }
        return $models;
    }

    public function paginate(int $currentPage, string $columns = '*')
    {
        if (!method_exists($this->getModel(), $this->paginator)){
            throw new Exception("Model paginate method[".$this->paginator."] not found");
        }

        $total = $this->query->select('COUNT(*) count')->from($this->model->getTable())->fetchOne();

        $perPage = $this->model->getPerPage();
        
        $this->query->setMaxResults($perPage);

        $offset = ($currentPage - 1) * $perPage;

        $this->query->setFirstResult($offset);

        $items = $this->get();
        
        $options = [];

        return $this->getModel()
            ->{$this->paginator}($items, $total, $perPage, $currentPage, $options);
    }

    public function insert($values){
        $this->query
            ->insert($this->model->getTable());
        $this->setInsertingValues($values);
        $this->query->executeStatement();
        return $this->connection->lastInsertId();
    }

    public function update($id, $values){
        $this->query
        ->update($this->model->getTable());
        $this->setUpdatingValues($values);
        $this->query
            ->where($this->model->getKey(). '= :id')
            ->setParameter('id', $id);
        return $this->query->executeStatement();

    }

    public function delete($id){
        $this->query
        ->delete($this->model->getTable())
        ->where($this->model->getKey(). '= :id')
        ->setParameter('id', $id);
        return $this->query->executeStatement();
    }


    /**
     * appending data from related model using suffix "_id" for foreign key;
     * 
     * @param class-string<\Model> $relation
     * @return self
     */
    public function with( $relation, string $columns = '*', ?int $limit = null, ?string $sort = null, string $order = null){
        $this->relations[$relation] = [
            'columns' => $columns,
            'limit' => $limit,
            'sort' => $sort,
            'order' => $order
        ];
        return $this;
    }

    /**
     * Summary of prepareWithCondition
     * @param \Model $relation
     * @return array
     */
    private function getWithCondition($relation){
        return [
            strtolower(Helpers::classBaseName($this->getModel())) . "_id", 
            "=", 
            $this->getModel()->getAttribute('id')
        ];
    }

    private function setInsertingValues($values){
        foreach ($values as $key => $value) {
            $this->query->setValue($key, ':'.$key);
            $this->query->setParameter($key, $value);
        }
    }

    private function setUpdatingValues($values){
        foreach ($values as $key => $value) {
            $this->query->set($key, ':'.$key);
            $this->query->setParameter($key, $value);
        }
    }
    
	/**
	 * @return \Model
	 */
	public function getModel(): \Model {
		return $this->model;
	}
	
	/**
	 * @param \Model $model 
	 * @return self
	 */
	public function setModel($model): self {
		$this->model = $model;
		return $this;
	}
}