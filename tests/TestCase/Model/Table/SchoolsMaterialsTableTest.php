<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SchoolsMaterialsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SchoolsMaterialsTable Test Case
 */
class SchoolsMaterialsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SchoolsMaterialsTable
     */
    protected $SchoolsMaterials;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.SchoolsMaterials',
        'app.Schools',
        'app.Materials',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('SchoolsMaterials') ? [] : ['className' => SchoolsMaterialsTable::class];
        $this->SchoolsMaterials = $this->getTableLocator()->get('SchoolsMaterials', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->SchoolsMaterials);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\SchoolsMaterialsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\SchoolsMaterialsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
