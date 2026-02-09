<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddCctToSchools extends BaseMigration
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
        $this->table('schools')
            ->addColumn('cct', 'string', [
                'limit' => 20,
                'null' => false,
                'after' => 'nombre',
            ])
            ->addIndex(['cct'], [
                'unique' => true,
                'name' => 'IDX_SCHOOLS_CCT_UNIQUE'
            ])
            ->update();
    }
}
