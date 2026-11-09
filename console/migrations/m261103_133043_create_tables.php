<?php

use yii\db\Migration;

class m261103_133043_create_tables extends Migration
{
   public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        // 1. Tabela LOCAIS
        $this->createTable('{{%locais}}', [
            'id' => $this->primaryKey(),
            'nome' => $this->string(255)->notNull(),
            'descricao' => $this->text(),
            'foto_capa_url' => $this->string(500),
            'morada' => $this->text()->notNull(),
            'cidade' => $this->string(100)->notNull(),
            'codigo_postal' => $this->string(20),
            'capacidade_total' => $this->integer()->notNull()->defaultValue(0),
            'preco_aluguer_base' => $this->decimal(10, 2)->notNull()->defaultValue(0.00),
            'ativo' => $this->boolean()->notNull()->defaultValue(true),
            'criado_em' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'atualizado_em' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        // 2. Tabela LOCAL_FOTOS
        $this->createTable('{{%local_fotos}}', [
            'id' => $this->primaryKey(),
            'local_id' => $this->integer()->notNull(),
            'foto_url' => $this->string(500)->notNull(),
            'legenda' => $this->string(255),
            'ordem' => $this->integer()->notNull()->defaultValue(0),
            'criado_em' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_local_fotos_locais',
            '{{%local_fotos}}',
            'local_id',
            '{{%locais}}',
            'id',
            'CASCADE'
        );

        // 3. Tabela TIPOS_EVENTO
        $this->createTable('{{%tipos_evento}}', [
            'id' => $this->primaryKey(),
            'nome' => $this->string(100)->notNull()->unique(),
            'descricao' => $this->text(),
            'preco_base_pessoa' => $this->decimal(10, 2)->notNull()->defaultValue(0.00),
            'ativo' => $this->boolean()->notNull()->defaultValue(true),
            'criado_em' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        // 4. Tabela EVENTOS
        $this->createTable('{{%eventos}}', [
            'id' => $this->primaryKey(),
            'cliente_id' => $this->integer()->notNull(),
            'local_id' => $this->integer()->notNull(),
            'tipo_evento_id' => $this->integer()->notNull(),
            'titulo' => $this->string(255)->notNull(),
            'descricao' => $this->text(),
            'num_estimado_pessoas' => $this->integer()->notNull()->defaultValue(0),
            'data_inicio' => $this->timestamp()->notNull(),
            'data_fim' => $this->timestamp()->notNull(),
            'estado' => "ENUM('rascunho', 'agendado', 'em_execucao', 'concluido', 'cancelado') NOT NULL DEFAULT 'rascunho'",
            'taxa_fixa_organizacao' => $this->decimal(10, 2)->notNull()->defaultValue(100.00),
            'taxa_plataforma_percentual' => $this->decimal(5, 2)->notNull()->defaultValue(5.00),
            'criado_em' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'atualizado_em' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->addForeignKey('fk_eventos_cliente', '{{%eventos}}', 'cliente_id', '{{%user}}', 'id');
        $this->addForeignKey('fk_eventos_local', '{{%eventos}}', 'local_id', '{{%locais}}', 'id');
        $this->addForeignKey('fk_eventos_tipo', '{{%eventos}}', 'tipo_evento_id', '{{%tipos_evento}}', 'id');
        $this->createIndex('idx_eventos_disponibilidade', '{{%eventos}}', ['local_id', 'data_inicio', 'data_fim']);

        // 5. Tabela PRODUTOS
        $this->createTable('{{%produtos}}', [
            'id' => $this->primaryKey(),
            'nome' => $this->string(255)->notNull(),
            'categoria' => "ENUM('catering', 'fotografia', 'animacao', 'decoracao', 'outros') NOT NULL DEFAULT 'outros'",
            'tipo_cobranca' => "ENUM('fixo', 'por_pessoa', 'por_hora', 'por_unidade') NOT NULL DEFAULT 'fixo'",
            'preco' => $this->decimal(10, 2)->notNull()->defaultValue(0.00),
            'ativo' => $this->boolean()->notNull()->defaultValue(true),
            'criado_em' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        // 6. Tabela FATURAS
        $this->createTable('{{%faturas}}', [
            'id' => $this->primaryKey(),
            'evento_id' => $this->integer()->notNull(),
            'cliente_id' => $this->integer()->notNull(),
            'estado' => "ENUM('pendente', 'paga', 'vencida', 'anulada') NOT NULL DEFAULT 'pendente'",
            'valor_liquido' => $this->decimal(12, 2)->notNull()->defaultValue(0.00),
            'valor_iva' => $this->decimal(10, 2)->notNull()->defaultValue(0.00),
            'valor_total' => $this->decimal(12, 2)->notNull()->defaultValue(0.00),
            'data_emissao' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'data_vencimento' => $this->date()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('fk_faturas_evento', '{{%faturas}}', 'evento_id', '{{%eventos}}', 'id');
        $this->addForeignKey('fk_faturas_cliente', '{{%faturas}}', 'cliente_id', '{{%user}}', 'id');

        // 7. Tabela FATURA_LINHAS
        $this->createTable('{{%fatura_linhas}}', [
            'id' => $this->primaryKey(),
            'fatura_id' => $this->integer()->notNull(),
            'produto_id' => $this->integer()->null(),
            'descricao' => $this->string(255)->notNull(),
            'valor_pago' => $this->decimal(12, 2)->notNull()->defaultValue(0.00),
            'percentual_iva' => $this->decimal(5, 2)->notNull()->defaultValue(23.00),
            'valor_iva' => $this->decimal(10, 2)->notNull()->defaultValue(0.00),
        ], $tableOptions);

        $this->addForeignKey('fk_fatura_linhas_fatura', '{{%fatura_linhas}}', 'fatura_id', '{{%faturas}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_fatura_linhas_produto', '{{%fatura_linhas}}', 'produto_id', '{{%produtos}}', 'id', 'SET NULL');
    }

    public function safeDown()
    {
        $this->dropTable('{{%fatura_linhas}}');
        $this->dropTable('{{%faturas}}');
        $this->dropTable('{{%produtos}}');
        $this->dropTable('{{%eventos}}');
        $this->dropTable('{{%tipos_evento}}');
        $this->dropTable('{{%local_fotos}}');
        $this->dropTable('{{%locais}}');
    }
}
