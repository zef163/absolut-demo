<?php

namespace App\Console\Commands;

use App\Models\Reference;
use App\Repositories\ExchangeRatesRepository;
use Illuminate\Console\Command;

class SyncReferences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'references:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize references from CBR';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $result = app(ExchangeRatesRepository::class)->getReferences();

        foreach ($result as $item) {
            app(Reference::class)->addOrUpdate($item);
        }

        return 0;
    }
}
