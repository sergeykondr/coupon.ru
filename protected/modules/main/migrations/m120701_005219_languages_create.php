<?
class m120701_005219_languages_create extends DbMigration
{
    public function safeUp()
    {
        $this->execute("DROP TABLE IF EXISTS `languages`;");

        $this->execute("
            CREATE TABLE `languages` (
                `id` char(2) NOT NULL COMMENT 'ID',
                `name` varchar(15) NOT NULL COMMENT 'Название',
                PRIMARY KEY (`id`),
                UNIQUE KEY `name` (`name`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }


    public function safeDown()
    {
        return false;
    }
}