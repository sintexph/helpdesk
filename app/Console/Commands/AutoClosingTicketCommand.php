<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AutoClosingTicketCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ticket:close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automate Ticket Closing';

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
     * @return mixed
     */
    public function handle()
    {
        \App\Helpers\TicketHelper::auto_close_tickets();
    }
}
