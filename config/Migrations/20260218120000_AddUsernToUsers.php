<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddUsernToUsers extends BaseMigration
{
    public function change(): void
    {
        $table = $this->table('users');

        if (!$table->hasColumn('usern')) {
            $table->addColumn('usern', 'string', [
                'limit' => 100,
                'null' => true,
                'after' => 'email',
            ]);
        }

        if ($table->hasIndex(['email'])) {
            $table->removeIndex(['email']);
        }

        $table->update();

        $this->execute("UPDATE users SET usern = CONCAT('user', id) WHERE usern IS NULL OR usern = ''");

        $table = $this->table('users');

        if ($table->hasColumn('usern')) {
            $table->changeColumn('usern', 'string', [
                'limit' => 100,
                'null' => false,
            ]);
        }

        if (!$table->hasIndex(['usern'])) {
            $table->addIndex(['usern'], ['unique' => true]);
        }

        $table->update();
    }
}
