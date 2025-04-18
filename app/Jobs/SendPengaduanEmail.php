<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewPengaduanNotification;
use Illuminate\Support\Facades\Log;


class SendPengaduanEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 60;

    protected $email;
    protected $data;

    public function __construct($email, $data)
    {
        $this->email = $email;
        $this->data = $data;
    }

    public function handle()
    {
        Mail::to($this->email)
            ->send(new NewPengaduanNotification($this->data));
    }

    public function failed(\Exception $exception)
    {
        Log::error("Failed to send email to {$this->email}: " . $exception->getMessage());
    }
}