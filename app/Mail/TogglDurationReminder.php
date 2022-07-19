<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Psy\Util\Str;

class TogglDurationReminder extends Mailable
{
    use Queueable, SerializesModels;

    private $task;
    private $duration;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($task, $duration)
    {
        $this->task = $task;
        $this->duration = round($duration / 60, 2);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Toggl duration reminder')
            ->markdown('notifications::email', [
                'introLines' => [
                    "You have been working on task '$this->task' for $this->duration " . \Illuminate\Support\Str::of('hour')->plural($this->duration) . '.',
                ],
                'outroLines' => [

                ],
                'level' => 'warning',
            ]);

    }
}
