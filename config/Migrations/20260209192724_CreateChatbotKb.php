<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateChatbotKb extends BaseMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/4/en/migrations.html#the-change-method
     *
     * @return void
     */
    public function change(): void
    {
        $this->table('kb_articles')
            ->addColumn('title', 'string', ['limit' => 255])
            ->addColumn('module', 'string', ['limit' => 120, 'null' => true])
            ->addColumn('tags', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('body', 'text')
            ->addColumn('is_active', 'boolean', ['default' => true])
            ->addColumn('created', 'datetime')
            ->addColumn('modified', 'datetime')
            ->create();

        $this->table('kb_chunks')
            ->addColumn('article_id', 'integer')
            ->addColumn('chunk_text', 'text')
            ->addColumn('meta', 'text', ['null' => true]) // JSON opcional
            ->addColumn('created', 'datetime')
            ->addIndex(['article_id'])
            ->create();

        // FULLTEXT en MySQL (InnoDB)
        $this->execute("ALTER TABLE kb_chunks ADD FULLTEXT INDEX ft_chunk_text (chunk_text)");
    }
}
