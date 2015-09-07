<?php

use yii\db\Schema;

class m150907_040101_create_company_table extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%company}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(45)',
            'description' => Schema::TYPE_STRING . '(45)',
            'created_at' => Schema::TYPE_INTEGER . '(11) DEFAULT 0',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT 0',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%company}}');
    }
}
