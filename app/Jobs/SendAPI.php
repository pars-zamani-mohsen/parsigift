<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Controllers\API\CRMController;

class SendAPI implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $request;

    /**
     * SendAPI constructor.
     * @param array $request
     */
    public function __construct(array $request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $CRM_instance = new CRMController();
        $CRM_instance->configuraton();

        // create a new contact
        $contact = $CRM_instance->service->entity($CRM_instance->module);
        foreach($this->request as $key => $item) {
            if (in_array($key, $CRM_instance->getFields()))
                $contact->$key = $item;
        }

        $contactId = $contact->create();
        $CRM_instance->ApiLog('create', $this->request, $contactId);
    }
}
