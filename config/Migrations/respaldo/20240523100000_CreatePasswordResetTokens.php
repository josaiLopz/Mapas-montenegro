<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreatePasswordResetTokens extends BaseMigration
{
    public function change(): void
    {
        $table = $this->table('password_reset_tokens');
        $table->addColumn('user_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ])
        ->addColumn('token', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ])
        ->addColumn('expires_at', 'datetime', [
            'default' => null,
            'null' => false,
        ])
        ->addTimestamps('created_at', 'updated_at')
        ->addIndex(['token'], ['unique' => true])
        ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
        ->create();
    }
}