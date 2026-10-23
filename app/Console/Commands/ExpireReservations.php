<?php

namespace App\Console\Commands;

use App\Services\InventoryService;
use Illuminate\Console\Command;

class ExpireReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire overdue ticket reservations and restore seat counts';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $expiredCount = InventoryService::expireOverdueReservations();
        $this->info("Successfully expired {$expiredCount} overdue reservations.");

        return Command::SUCCESS;
    }
}
