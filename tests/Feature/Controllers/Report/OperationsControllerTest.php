<?php
/**
 * OperationsControllerTest.php
 * Copyright (c) 2017 thegrumpydictator@gmail.com
 * This software may be modified and distributed under the terms of the Creative Commons Attribution-ShareAlike 4.0 International License.
 *
 * See the LICENSE file for details.
 */

declare(strict_types = 1);

namespace Tests\Feature\Controllers\Report;


use FireflyIII\Helpers\Collector\JournalCollectorInterface;
use FireflyIII\Models\Transaction;
use FireflyIII\Models\TransactionType;
use Tests\TestCase;

class OperationsControllerTest extends TestCase
{
    /**
     * @covers \FireflyIII\Http\Controllers\Report\OperationsController::expenses
     * @covers \FireflyIII\Http\Controllers\Report\OperationsController::getExpenseReport
     * @covers \FireflyIII\Http\Controllers\Report\OperationsController::groupByOpposing
     */
    public function testExpenses()
    {
        $transactions = factory(Transaction::class, 10)->make();
        $collector    = $this->mock(JournalCollectorInterface::class);
        $collector->shouldReceive('setAccounts')->andReturnSelf();
        $collector->shouldReceive('setRange')->andReturnSelf();
        $collector->shouldReceive('setTypes')->withArgs([[TransactionType::WITHDRAWAL, TransactionType::TRANSFER]])->andReturnSelf();
        $collector->shouldReceive('withOpposingAccount')->andReturnSelf();
        $collector->shouldReceive('enableInternalFilter')->andReturnSelf();
        $collector->shouldReceive('getJournals')->andReturn($transactions);


        $this->be($this->user());
        $response = $this->get(route('report-data.operations.expenses', ['1', '20160101', '20160131']));
        $response->assertStatus(200);
    }

    /**
     * @covers \FireflyIII\Http\Controllers\Report\OperationsController::income
     * @covers \FireflyIII\Http\Controllers\Report\OperationsController::getIncomeReport
     */
    public function testIncome()
    {
        $transactions = factory(Transaction::class, 10)->make();
        $collector    = $this->mock(JournalCollectorInterface::class);
        $collector->shouldReceive('setAccounts')->andReturnSelf();
        $collector->shouldReceive('setRange')->andReturnSelf();
        $collector->shouldReceive('setTypes')->withArgs([[TransactionType::DEPOSIT, TransactionType::TRANSFER]])->andReturnSelf();
        $collector->shouldReceive('withOpposingAccount')->andReturnSelf();
        $collector->shouldReceive('enableInternalFilter')->andReturnSelf();
        $collector->shouldReceive('getJournals')->andReturn($transactions);

        $this->be($this->user());
        $response = $this->get(route('report-data.operations.income', ['1', '20160101', '20160131']));
        $response->assertStatus(200);
    }

    /**
     * @covers \FireflyIII\Http\Controllers\Report\OperationsController::operations
     */
    public function testOperations()
    {
        $this->be($this->user());
        $response = $this->get(route('report-data.operations.operations', ['1', '20160101', '20160131']));
        $response->assertStatus(200);
    }

}
