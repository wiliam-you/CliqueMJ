<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\PiplModules\admin\Helpers\GlobalValues;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class sendEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $all_data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->all_data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $all_data = $this->all_data;
        $site_email = GlobalValues::get('site-email');
        $site_title = GlobalValues::get('site-title');

        foreach ($all_data as $data){
            $data['keywords']['SITE_TITLE'] = $site_title;
            Mail::send($data['view'], $data['keywords'], function ($message) use ($data,$site_email, $site_title) {
                $message->to($data['to'])->subject($data['subject'])->from($site_email, $site_title);
            });
        }
    }
}
