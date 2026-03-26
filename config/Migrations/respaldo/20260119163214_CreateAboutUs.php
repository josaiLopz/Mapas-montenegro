<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateAboutUs extends BaseMigration
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
        $table = $this->table('about_us');
        $table->addColumn('title', 'string', [
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('content', 'text', [
            'null' => false,
        ]);
        $table->addColumn('image', 'string', [
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('active', 'boolean', [
            'default' => true,
        ]);
        $table->addColumn('created', 'datetime');
        $table->addColumn('updated', 'datetime');

        $table->create();
    }
}
