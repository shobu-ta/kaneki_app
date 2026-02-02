<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BusinessDaysTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BusinessDaysTable Test Case
 */
class BusinessDaysTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\BusinessDaysTable
     */
    protected $BusinessDays;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.BusinessDays',
        'app.Products',
        'app.Reservations',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('BusinessDays') ? [] : ['className' => BusinessDaysTable::class];
        $this->BusinessDays = $this->getTableLocator()->get('BusinessDays', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->BusinessDays);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\BusinessDaysTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
