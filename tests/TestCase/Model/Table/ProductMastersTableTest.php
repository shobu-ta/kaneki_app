<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductMastersTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductMastersTable Test Case
 */
class ProductMastersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductMastersTable
     */
    protected $ProductMasters;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.ProductMasters',
        'app.Products',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('ProductMasters') ? [] : ['className' => ProductMastersTable::class];
        $this->ProductMasters = $this->getTableLocator()->get('ProductMasters', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->ProductMasters);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\ProductMastersTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
