<?
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Query\QueryBuilder;

class Db{
    
    private string $host;
    private string $driver;
    private string $dbname;
    private string $user;
    private string $password;

    private $c;
    
    private function __construct(){
        $attrs = get_class_vars(__CLASS__);
        foreach($this->getConnectionParams() as $k=>$val){
            if (array_key_exists($k, $attrs)){
                $this->$k = $val;
            }
        }
    }

    private function getConnectionParams(){
        return [
            'dbname' => 'test',
            'user' => 'user',
            'password' => '1qa@WS3ed',
            'host' => 'db',
            'driver' => 'pdo_mysql',
        ];
    }

    private function connect(){
        $params = [
            'host' => $this->host,
            'driver' => $this->driver,
            'dbname' => $this->dbname,
            'user' => $this->user,
            'password' => $this->password
        ];
        return DriverManager::getConnection($params);
    }

    public static function raw(string $sql){
        $db = new self();
        return $db->connect()->executeQuery($sql);
    }
    
    public static function newQuery(): QueryBuilder{
        $db = new self();
        return $db->connect()->createQueryBuilder();
    }

    public static function manager(){
        $db = new self();
        return $db->connect()->createSchemaManager();
    }

    public static function platform(){
        $db = new self();
        return $db->connect()->getDatabasePlatform();
    }
    
}