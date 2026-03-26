<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddRoleToUsers extends BaseMigration
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
        $table = $this->table('users');
        $table->addColumn('role', 'string', [
            'default' => 'user',
            'limit' => 255,
            'null' => false,
        ]);
        $table->update();
    }
}
