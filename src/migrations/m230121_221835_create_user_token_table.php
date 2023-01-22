<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_token}}`.
 */
class m230121_221835_create_user_token_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_auth_token}}', [
            'id' => $this->primaryKey(),
            'token' => $this->text()->notNull(),
            'user_id' => $this->string()->notNull(),
            'expires_at' => $this->dateTime()->notNull(),
            'created_at' => $this->dateTime()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_auth_token}}');
    }
}
