<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\MtSports\Repositories\AccountSuspensionRepositories;
use App\Laravue\Models\AccountSuspension;
use App\Laravue\Services\AccountSuspensionServices;

class unbannedPlayer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unbanned:player';

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
    public function __construct(
        private AccountSuspensionServices $accountSuspensionServices
    )
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
        $unbannedPlayers = AccountSuspension::where('status_id', '1')
            ->where('unbloking_time', '<=', date('Y-m-d h:i:s'))->get();
        if(!empty($unbannedPlayers)){
            foreach($unbannedPlayers as $v){
//                dump($v);
//                dump($v->account_id);
                $this->accountSuspensionServices->sendPlayerUnBanned($v->account_id);
                $v->status_id = 2;
                $v->save();
            }
        }
//        dump($unbannedPlayers);
    }
}
