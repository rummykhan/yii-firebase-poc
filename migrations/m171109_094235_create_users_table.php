<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users`.
 */
class m171109_094235_create_users_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'name' => $this->string(32),
            'email' => $this->string(32)->unique(),
            'password' => $this->string(10),
            'realtime_token' => $this->string(255)->null(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('users');
    }
}
