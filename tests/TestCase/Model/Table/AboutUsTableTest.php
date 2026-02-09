<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AboutUsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AboutUsTable Test Case
 */
class AboutUsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AboutUsTable
     */
    protected $AboutUs;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.AboutUs',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('AboutUs') ? [] : ['className' => AboutUsTable::class];
        $this->AboutUs = $this->getTableLocator()->get('AboutUs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->AboutUs);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\AboutUsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
