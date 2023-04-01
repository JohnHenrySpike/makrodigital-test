<?
namespace Migrations;
class create_comments_table_2 {
    public static function schema(){
        $schema = new \Doctrine\DBAL\Schema\Schema();
        $table = $schema->createTable("comments");
        $table->addColumn("id", "integer", ["unsigned" => true])->setAutoincrement(true);
        $table->addColumn("post_id", "integer", ["unsigned" => true]);
        $table->addColumn("author", "string", ["length" => 32])->setNotnull(true);
        $table->addColumn("text", "text")->setNotnull(true);
        $table->addColumn("created_at", "datetimetz")->setDefault('CURRENT_TIMESTAMP');
        $table->addColumn("updated_at", "datetimetz")->setDefault('CURRENT_TIMESTAMP'); // ON UPDATE CURRENT_TIMESTAMP' not supported https://github.com/doctrine/dbal/issues/3966
        $table->setPrimaryKey(["id"]);

        $table->addForeignKeyConstraint("posts", ["post_id"], ["id"], ["onDelete" => "CASCADE"]);
        
        return $schema;
    }
}