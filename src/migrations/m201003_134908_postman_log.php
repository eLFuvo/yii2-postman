<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-10-04
 * Time: 12:57
 */

use yii\db\Migration;

/**
 * Class m201003_134908_postman_log
 */
class m201003_134908_postman_log extends Migration
{
    protected const TABLE = '{{%postman_log}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;
        $this->createTable(
            self::TABLE,
            [
                'id' => $this->primaryKey(),
                'total' => $this->integer()->notNull()->defaultValue(0),
                'done' => $this->integer()->notNull()->defaultValue(0),
                'status' => $this->smallInteger(1)->notNull()->defaultValue(0),
                'data' => $this->json()->null(),
                'counters' => $this->json()->null(),
                'errors' => $this->json()->null(),
                'createdAt' => $this->integer()->null(),
                'updatedAt' => $this->integer()->null(),
            ],
            $options
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
