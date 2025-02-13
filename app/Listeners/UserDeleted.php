<?php

namespace App\Listeners;

use App\Laravue\Models\CustomerServiceOperators as CustomerServiceOperatorsModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\UserDeleted as UserDeleteEvent;

class UserDeleted
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UserDeleteEvent $event)
    {
        // clear all permission for user
        $event->user->syncPermissions([]);
        // clear all roles for user
        $event->user->syncRoles([]);
        $deleteOperator = CustomerServiceOperatorsModel::find($event->user->id);
        if($deleteOperator){
            $deleteOperator->delete();
        }
    }
}
