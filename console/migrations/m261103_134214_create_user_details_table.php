<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_details}}`.
 */
class m261103_134214_create_user_details_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_details}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'nome_completo' => $this->string(255)->notNull(),
            'foto_perfil_url' => $this->string(500),
            'telefone' => $this->string(30),
            'nif' => $this->string(20),
            'morada' => $this->text(),
            'cidade' => $this->string(100),
            'codigo_postal' => $this->string(20),
            'criado_em' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'atualizado_em' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        // Chave estrangeira com remoção em cascata (se apagar o user, apaga os detalhes)
        $this->addForeignKey(
            'fk_user_details_user',
            '{{%user_details}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%user_details}}');
    }
}
