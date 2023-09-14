<?php

namespace App\Console\Commands;

use App\Models\UserCode;
use Illuminate\Console\Command;
use Carbon\Carbon;
class DeleteCodeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'code:delete';

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
        $expirationTime = Carbon::now()->subMinutes(1);

        UserCode::where('created_at', '<', $expirationTime)->delete();

        $this->info('Expired verification codes deleted successfully.');
    }
}
