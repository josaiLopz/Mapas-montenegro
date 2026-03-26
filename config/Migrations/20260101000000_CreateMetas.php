<?php
// config/Migrations/20260101000000_CreateMetas.php
use Migrations\AbstractMigration;

class CreateMetas extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('metas');
        $table
            ->addColumn('anio',        'integer', ['limit' => 4])
            ->addColumn('descripcion', 'string',  ['limit' => 100, 'null' => true])
            ->addColumn('valor',       'decimal',  ['precision' => 12, 'scale' => 2, 'default' => 0])
            ->addColumn('user_id',     'integer', ['null' => true])
            ->addColumn('estado_id',   'integer', ['null' => true])
            ->addColumn('created',     'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated',     'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}