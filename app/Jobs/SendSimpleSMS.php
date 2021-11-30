<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\AdditionalClasses\CustomKavenegarApi;

class SendSimpleSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $to;
    protected $from;
    protected $date;
    protected $type;
    protected $message;
    protected $localid;

    /**
     * SendSimpleSMS constructor.
     * @param string $from
     * @param array $to
     * @param string $message
     * @param null $date
     * @param null $type
     * @param null $localid
     */
    public function __construct(string $from, array $to, string $message, $date = null, $type = null, $localid = null)
    {
        $this->to = $to;
        $this->from = $from;
        $this->date = $date;
        $this->type = $type;
        $this->message = $message;
        $this->localid = $localid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $api = new CustomKavenegarApi("3239655158695336527076303248434F6446723372673D3D");
        $api->VerifyLookupV2Simple($this->from, $this->to, $this->message, $this->date, $this->type, $this->localid);
    }
}
