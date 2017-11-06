<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class TokenGarbage
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
    public function handle($event)
    {
		
		
        DB::table('oauth_access_tokens')
			
			->whereColumn(
				['user_id', '=',  Auth::id()],
				['id', '=',$event->tokenId],
				['client_id', '=',$event->client_id],	
				['revoked', '=',1],
				)
			
			->delete();
			
			
    }
}
