<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class SchoolChangeRequests extends BaseMigration
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
          $table = $this->table('school_change_requests');

        $table
            ->addColumn('school_id', 'integer')
            ->addColumn('requested_by', 'integer')
            ->addColumn('approved_by', 'integer', ['null' => true])
            ->addColumn('type', 'string', ['limit' => 20]) // coords | edit
            ->addColumn('payload', 'json')
            ->addColumn('status', 'string', ['limit' => 20, 'default' => 'pending']) // pending|approved|rejected
            ->addColumn('comment', 'text', ['null' => true])
            ->addColumn('approved_at', 'datetime', ['null' => true])
            ->addTimestamps();

        $table->addIndex(['school_id']);
        $table->addIndex(['status']);
        $table->addIndex(['requested_by']);
        $table->addIndex(['approved_by']);

        // Opcional: llaves foráneas si tu BD las soporta y usas InnoDB
        // $table->addForeignKey('school_id', 'schools', 'id', ['delete' => 'CASCADE']);
        // $table->addForeignKey('requested_by', 'users', 'id');
        // $table->addForeignKey('approved_by', 'users', 'id');

        $table->create();
    }
}
