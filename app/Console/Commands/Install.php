<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loanapp:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Script to install a web app in one go';

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
        //check database connection
        try {
            DB::connection();
        }catch (Exception $exception){
            $this->error('Unable to connect the Database.');
            $this->error('Please update .env file with valid database credentials and run this command again.');
            return;
        }

        //generate app key if it does not exist
        $this->comment('Attempting to install Loan App!');
        if (!env('APP_KEY')){
            $this->info('Generating app key');
            Artisan::call('key:generate');
        }else{
            $this->comment('App key already exists. Skipping this step.');
        }

        //run database migrations
        $this->info('Migrating database...');
        Artisan::call('migrate',['--force' => true]);
        $this->comment('Database migration completed.');

        //run database seeders
        $this->info('Seeding database data...');
        Artisan::call('db:seed',['--force' => true]);
        $this->comment('Database seeders ran successfully.');

        $this->comment('LoanApp installed successfully. Now you can run the app!');

    }
}
