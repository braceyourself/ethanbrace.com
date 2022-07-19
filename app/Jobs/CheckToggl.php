<?php

namespace App\Jobs;

use App\Mail\TogglDurationReminder;
use App\Notifications\ToggleMessage;
use App\Notifications\ToggleNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class CheckToggl implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handle()
    {
        /** @var PendingRequest $client */
        $client = app('toggl');

        $current = $client->get('me/time_entries/current')->body();

        if($current){
            return;
        }

        $current_diff = now()->unix() + $current->get('duration');


        $all = $client->get('me/time_entries', [
            'since' => now()->subMonths(2)->unix(),
        ])->collect()
            ->filter(function ($value) use ($current) {
                if ($value['duration'] < 0) {
                    return false;
                }

                return $value['description'] === $current->get('description');
            })
            ->sum('duration');

        $diff = now()
            ->addSeconds($current_diff)
            ->addSeconds($all)
            ->diffInHours();



        Mail::to(['ethan@modernmcguire.com'])->send(new TogglDurationReminder($current->get('description'), $diff));
    }
}
