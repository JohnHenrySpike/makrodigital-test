<?
namespace App\Models;

use Model;
use Traits\Paginate;
class Post extends Model
{
    use Paginate;

    protected function boot(){
        $this->setPerPage(2);
    }
}
