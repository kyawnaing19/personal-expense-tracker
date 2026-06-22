<?php

namespace App\Console\Commands;

use App\Services\RecurringTransactionService;
use Illuminate\Console\Command;

class ProcessRecurringTransactions extends Command
{

    protected $signature = 'app:process-recurring-transactions';
    protected $descriptions = 'Process due recurring transactions';

     public function __construct(
        private RecurringTransactionService $recurringTransactionService
     )
    {
        parent::__construct();
    }

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->recurringTransactionService->processRecurring();
        $this->info('Recurring transactions processed successfully.');
    }
}
