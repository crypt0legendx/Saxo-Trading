<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class EmailVerificationsCleaner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $name = 'emailverifs:clean';

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
        //DB::delete('delete from `email_verifications` where `created_at` <' . date("YmdHms", time() - 300));
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
