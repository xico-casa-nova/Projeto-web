<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user_details}}`.
 */
class m261103_152955_add_role_column_to_user_details_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            '{{%user_details}}',
            'role',
            "ENUM('user', 'gestor', 'admin') NOT NULL DEFAULT 'user' AFTER `nome_completo`"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user_details}}', 'role');
    }
}
