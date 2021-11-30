<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\AdditionalClasses\CustomKavenegarApi;

class SendArraySimpleSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $to;
    protected $date;
    protected $type;
    protected $from;
    protected $localid;
    protected $message;

    /**
     * SendArraySimpleSMS constructor.
     * @param array $from
     * @param array $to
     * @param array $message
     * @param null $date
     * @param null $type
     * @param null $localid
     */
    public function __construct(array $from, array $to, array $message, $date = null, $type = null, $localid = null)
    {
        $this->to = $to;
        $this->date = $date;
        $this->type = $type;
        $this->from = $from;
        $this->localid = $localid;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $api = new CustomKavenegarApi("3239655158695336527076303248434F6446723372673D3D");
        $api->VerifyLookupV2ArraySimple($this->from, $this->to, $this->message, $this->date, $this->type, $this->localid);
    }
}
