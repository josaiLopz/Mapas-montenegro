<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MunicipiosTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MunicipiosTable Test Case
 */
class MunicipiosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MunicipiosTable
     */
    protected $Municipios;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Municipios',
        'app.Estados',
        'app.Schools',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Municipios') ? [] : ['className' => MunicipiosTable::class];
        $this->Municipios = $this->getTableLocator()->get('Municipios', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Municipios);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\MunicipiosTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\MunicipiosTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
