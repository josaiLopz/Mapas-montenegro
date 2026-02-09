<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateSchools extends BaseMigration
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
        $table = $this->table('schools');
        
    $table
        ->addColumn('nombre', 'string', ['limit' => 255])
        ->addColumn('estado', 'string', ['limit' => 100])
        ->addColumn('municipio', 'string', ['limit' => 100])
        ->addColumn('distribuidor_id', 'integer', ['null' => true])
        ->addColumn('tipo', 'string', ['limit' => 50, 'null' => true])
        ->addColumn('sector', 'string', ['limit' => 50, 'null' => true])
        ->addColumn('turno', 'string', ['limit' => 50, 'null' => true])
        ->addColumn('num_alumnos', 'integer', ['null' => true])
        ->addColumn('estatus', 'boolean', ['default' => true])
        ->addColumn('verificada', 'boolean', ['default' => false])
        ->addColumn('editorial_actual', 'string', ['limit' => 100, 'null' => true])
        ->addColumn('venta_montenegro', 'boolean', ['default' => false])
        ->addColumn('competencia', 'string', ['limit' => 100, 'null' => true])
        ->addColumn('fecha_decision', 'date', ['null' => true])
        ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
        ->addColumn('modified', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
        ->addForeignKey('distribuidor_id', 'users', 'id', ['delete'=> 'SET_NULL', 'update'=> 'CASCADE'])
        ->create();
    }
}
