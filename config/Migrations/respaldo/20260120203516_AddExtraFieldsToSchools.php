<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddExtraFieldsToSchools extends BaseMigration
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
        ->addColumn('estado_id', 'integer', ['null' => true])
        ->addColumn('municipio_id', 'integer', ['null' => true])
        ->addColumn('lat', 'decimal', [
            'precision' => 10,
            'scale' => 7,
            'null' => true,
        ])
        ->addColumn('lng', 'decimal', [
            'precision' => 10,
            'scale' => 7,
            'null' => true,
        ])
        ->addColumn('grupos', 'integer', ['null' => true])
        ->addColumn('nombre_contacto', 'string', [
            'limit' => 150,
            'null' => true,
        ])
        ->addColumn('telefono_contacto', 'string', [
            'limit' => 20,
            'null' => true,
        ])
        ->addColumn('correo_contacto', 'string', [
            'limit' => 150,
            'null' => true,
        ])
        ->addColumn('notas', 'text', ['null' => true])
        ->addColumn('presupuesto', 'decimal', [
            'precision' => 12,
            'scale' => 2,
            'null' => true,
        ])
        ->addForeignKey('estado_id', 'estados', 'id', ['delete' => 'SET_NULL'])
        ->addForeignKey('municipio_id', 'municipios', 'id', ['delete' => 'SET_NULL'])
        ->update();
    }
}
