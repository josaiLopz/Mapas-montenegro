<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class Materials extends BaseMigration
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
        $table = $this->table('materials');

        $table
            ->addColumn('nivel', 'string', [
                'limit' => 80,
                'null' => false,
                'default' => 'Primaria',
            ])
            ->addColumn('nombre', 'string', [
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('activo', 'boolean', [
                'null' => false,
                'default' => true,
            ])
            ->addTimestamps()
            ->addIndex(['nivel'])
            ->addIndex(['nombre'])
            ->create();
    }
}
