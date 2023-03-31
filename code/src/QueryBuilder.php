<?
class QueryBuilder {

    /**
     * @var \Model
     */
    private $model;

    private $query;

    private $paginator = 'paginator';


    public function __construct(){
        $this->query = $this->newBuilder();
    }

    private function newBuilder(){
        return Db::newQuery();
    }

    public function find($id, string $columns = '*'){
        $this->query        
        ->where($this->model->getKey(). '= :id')
        ->setParameter('id', $id);
        return $this->get($columns);
    }

    public function get(string $columns = '*'){
        $this->query->select($columns)->from($this->model->getTable());
        $res = $this->query->executeQuery();
        if ($res->rowCount()>0){
            return $this->getModel()->newCollection($this->collect($res->fetchAllAssociative()));
        }else{
            return $this->getModel()->newCollection();
        }
    }

    private function collect(array $items){
        $models = [];
        foreach ($items as $item) {
            $models[] = $this->getModel()->newInstance($item);
        }
        return $models;
    }

    public function paginate(int $currentPage, string $columns = '*', )
    {
        if (!method_exists($this->getModel(), $this->paginator)){
            throw new Exception("Model paginate method[".$this->paginator."] not avalible");
        }

        $total = $this->query->select('COUNT(*) count')->from($this->model->getTable())->fetchOne();

        $perPage = $this->getModel()->getPerPage();

        $this->query->setMaxResults($perPage);

        $offset = ($currentPage - 1) * $perPage;

        $this->query->setFirstResult($offset);

        $items = $this->query->select($columns)->from($this->model->getTable())->fetchAllAssociative();

        $items = $this->getModel()->newCollection($this->collect($items));

        $options = [];

        return $this->getModel()
            ->{$this->paginator}($items, $total, $perPage, $currentPage, $options);
    }

    public function insert($values){
        $this->query
        ->insert($this->model->getTable());
        $this->setInsertingValues($values);
        return $this->query->executeStatement();
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