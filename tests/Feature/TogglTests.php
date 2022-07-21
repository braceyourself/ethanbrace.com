<?php

use App\Jobs\CheckToggl;
use App\Mail\TogglDurationReminder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

it('has toggltests page', function () {
    Mail::fake();
    \Illuminate\Support\Facades\Http::fake([
        '*current' => Http::response([
            'duration' => -1,
            'description' => 'test entry'
        ]),
        '*' => Http::response([
            [
                'duration' => 10,
                'description' => 'test entry'
            ],
            [
                'duration' => 20,
                'description' => 'test entry'
            ],
        ])
    ]);

    CheckToggl::dispatchSync();

    Mail::assertSent(TogglDurationReminder::class);
});
