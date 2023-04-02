<?
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Component\Dotenv\Dotenv;

class Db{
    
    private static function getParams(){
        return require(__DIR__."/../config/db.php"); //TODO: need move to .env file
    }

    public static function getConnection(){
        return DriverManager::getConnection(self::getParams());
    }

    public static function raw(string $sql){
        return static::getConnection()->executeQuery($sql);
    }
    
    public static function newQuery(): QueryBuilder{
        return static::getConnection()->createQueryBuilder();
    }

    public static function manager(){
        return static::getConnection()->createSchemaManager();
    }

    public static function platform(){
        return static::getConnection()->getDatabasePlatform();
    }
    
}