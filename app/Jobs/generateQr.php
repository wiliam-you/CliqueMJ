<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\PiplModules\advertisement\Models\Advertisement;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Image;
use Illuminate\Support\Facades\Log;

class generateQr extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $created_advertisements = Advertisement::whereIn('unique_code',$this->data)->get();
        foreach ($created_advertisements as $index => $created_advertisement){
            $renderer = new \BaconQrCode\Renderer\Image\Png();
            $renderer->setHeight(256);
            $renderer->setWidth(256);
            $writer = new \BaconQrCode\Writer($renderer);
            $writer->writeFile($created_advertisement, 'storage/app/public/qr-codes/'.$created_advertisement->qr_code.'.png');

            $im = new \Imagick();
            $im->readImage(base_path() .'/storage/app/public/qr-codes/'.$created_advertisement->unique_code.'.png');
            $im->setImageFormat('bmp');
            header("Content-type: image/bmp");
            $im->writeImage(base_path() .'/storage/app/public/qr-codes/'.$created_advertisement->unique_code.'.bmp');

//            $bmp_image = Image::make(base_path() .'/storage/app/public/qr-codes/'.$created_advertisement->qr_code.'.png')->encode('bmp', 75);
//            $bmp_image->save(base_path() .'/storage/app/public/qr-codes/'.$created_advertisement->qr_code.'.bmp');
            unlink(base_path() .'/storage/app/public/qr-codes/'.$created_advertisement->qr_code.'.png');
        }
    }
}
