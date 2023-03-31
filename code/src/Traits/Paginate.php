<?
namespace Traits;

use \Paginator;
/**
 * added pagination
 */
trait Paginate
{
    protected int $perPage = 10;

	protected $paginatorClass;
    

	public function paginator($items, $total, $perPage, $currentPage, $options){
		return new ($this->getPaginatorClass())($items, $total, $perPage, $currentPage, $options);
	}
    /**
	 * @return int
	 */
	public function getPerPage(): int {
		return $this->perPage;
	}
	
	/**
	 * @param int $perPage 
	 * @return self
	 */
	public function setPerPage(int $perPage): self {
		$this->perPage = $perPage;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPaginatorClass() {
		return $this->paginatorClass ?? get_class(new Paginator()) ;
	}
	
	/**
	 * @param mixed $paginatorClass 
	 * @return self
	 */
	public function setPaginatorClass($paginatorClass): self {
		$this->paginatorClass = $paginatorClass;
		return $this;
	}
}
