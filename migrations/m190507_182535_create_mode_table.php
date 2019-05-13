<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%mode}}`.
 */
class m190507_182535_create_mode_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%mode}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%mode}}');
    }
}
