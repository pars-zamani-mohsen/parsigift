<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\AdditionalClasses\CustomKavenegarApi;

class VerifyLookupV2SendSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $receptor;
    protected $template;
    protected $type;
    protected $token;
    protected $token2;
    protected $token3;
    protected $token10;

    /**
     * VerifyLookupV2SendSMS constructor.
     * @param $receptor
     * @param $template
     * @param null $type
     * @param $token
     * @param $token2
     * @param $token3
     * @param $token10
     */
    public function __construct($receptor, $template, $type = null, $token, $token2, $token3, $token10)
    {
        $this->receptor = $receptor;
        $this->template = $template;
        $this->type = $type;
        $this->token = $token;
        $this->token2 = $token2;
        $this->token3 = $token3;
        $this->token10 = $token10;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $api = new CustomKavenegarApi("3239655158695336527076303248434F6446723372673D3D");
        $api->VerifyLookupV2($this->receptor, $this->template, $this->type, $this->token, $this->token2, $this->token3, $this->token10);
    }
}
