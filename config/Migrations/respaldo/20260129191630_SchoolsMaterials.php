<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class SchoolsMaterials extends BaseMigration
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
        $table = $this->table('schools_materials');

        $table
            ->addColumn('school_id', 'integer', ['null' => false])
            ->addColumn('material_id', 'integer', ['null' => false])
            ->addColumn('proyeccion_venta', 'decimal', [
                'precision' => 12,
                'scale' => 2,
                'null' => false,
                'default' => 0,
            ])
            ->addColumn('cierre_2026', 'decimal', [
                'precision' => 12,
                'scale' => 2,
                'null' => false,
                'default' => 0,
            ])
            ->addTimestamps()

            // Para que una escuela no tenga duplicado el mismo material
            ->addIndex(['school_id', 'material_id'], ['unique' => true])

            ->addForeignKey('school_id', 'schools', 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
            ])
            ->addForeignKey('material_id', 'materials', 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
            ])
            ->create();
    }
}
