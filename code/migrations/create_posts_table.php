<?
namespace Migrations;
class create_posts_table {
    public static function schema(){
        $schema = new \Doctrine\DBAL\Schema\Schema();
        $table = $schema->createTable("posts");
        $table->addColumn("id", "integer", ["unsigned" => true])->setAutoincrement(true);
        $table->addColumn("title", "string", ["length" => 32])->setNotnull(true);
        $table->addColumn("text", "string")->setNotnull(true);
        $table->addColumn("created_at", "datetimetz")->setDefault('CURRENT_TIMESTAMP');
        $table->addColumn("updated_at", "datetimetz")->setDefault('CURRENT_TIMESTAMP'); // ON UPDATE CURRENT_TIMESTAMP' not supported https://github.com/doctrine/dbal/issues/3966
        $table->setPrimaryKey(["id"]);
        
        return $schema;
    }
}