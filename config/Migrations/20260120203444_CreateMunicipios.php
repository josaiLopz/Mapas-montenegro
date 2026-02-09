<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateMunicipios extends BaseMigration
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
         $table = $this->table('municipios');
        $table
            ->addColumn('estado_id', 'integer', [
                'null' => false,
            ])
            ->addColumn('nombre', 'string', [
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('created', 'datetime')
            ->addColumn('modified', 'datetime')
            ->addForeignKey('estado_id', 'estados', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION',
            ])
            ->create();
    }
}
