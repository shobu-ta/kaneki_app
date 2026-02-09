<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin;

use App\Controller\Admin\BusinessDaysController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Admin\BusinessDaysController Test Case
 *
 * @link \App\Controller\Admin\BusinessDaysController
 */
class BusinessDaysControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.BusinessDays',
        'app.Reservations',
    ];

    /**
     * Test index method
     *
     * @return void
     * @link \App\Controller\Admin\BusinessDaysController::index()
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     * @link \App\Controller\Admin\BusinessDaysController::view()
     */
    public function testView(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     * @link \App\Controller\Admin\BusinessDaysController::add()
     */
    public function testAdd(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     * @link \App\Controller\Admin\BusinessDaysController::edit()
     */
    public function testEdit(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     * @link \App\Controller\Admin\BusinessDaysController::delete()
     */
    public function testDelete(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
