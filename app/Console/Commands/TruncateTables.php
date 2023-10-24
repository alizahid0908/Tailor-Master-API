<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TruncateTables extends Command
{
    protected $signature = 'truncate:tables';
    protected $description = 'Truncate the sizes and customers tables';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Delete all rows from the sizes table
        DB::table('sizes')->truncate();

        // Delete all rows from the customers table
        DB::table('customers')->truncate();

        $this->info('All data deleted successfully.');
    }
}