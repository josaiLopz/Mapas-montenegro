<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateUserAudits extends BaseMigration
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
        $table = $this->table('user_audits');
        $table->addColumn('user_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('action', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('old_data', 'text', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('new_data', 'text', [
            'default' => null,
            'null' => false,
        ]);
        $table->create();
    }
}
