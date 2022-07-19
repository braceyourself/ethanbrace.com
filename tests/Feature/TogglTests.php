<?php

use App\Jobs\CheckToggl;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

it('has toggltests page', function () {
//    Mail::fake();
//    \Illuminate\Support\Facades\Http::fake([
//        '*current' => Http::response([
//            'duration' => -1,
//            'description' => 'test entry'
//        ]),
//        '*' => Http::response([
//            [
//                'duration' => -1,
//                'description' => 'test entry'
//            ]
//        ])
//    ]);

    CheckToggl::dispatchSync();

//    Mail::assertSent(function(\App\Mail\TogglDurationReminder $mail){
//
//    });
});
