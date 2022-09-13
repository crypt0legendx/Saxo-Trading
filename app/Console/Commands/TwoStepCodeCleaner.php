<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TwoStepCodeCleaner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $name = 'twostep:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        // DB::delete('delete from `twostep_auth` where `created_at` <' . date("YmdHms", time() - 300));
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return 0;
    }

}
