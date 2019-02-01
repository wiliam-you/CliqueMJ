<?php
namespace App\PiplModules\advertisement\Controllers;

use App\Jobs\generateQr;
use App\PiplModules\advertisement\Models\Job;
use App\PiplModules\admin\Helpers\FileUpload;
use App\PiplModules\admin\Models\GlobalSetting;
use App\PiplModules\advertisementbrand\Models\AdvertisementBrand;
use App\PiplModules\webservice\Models\AdvertisementOfferReport;
use App\PiplModules\webservice\Models\PatientAdvertisementOffer;
use App\PiplModules\webservice\Models\PatientRecord;
use App\PiplModules\zone\Models\Zone;
use Auth;
use App\User;
use App\UserInformation;
use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Validator;
use App\PiplModules\advertisement\Models\Advertisement;
use Storage;
use Datatables;
use PDF;
use Image;
use Zipper;
use ZipArchive;
use Illuminate\Support\Facades\File;
use SnappyPDF;

class AdvertisementController extends Controller
{

    public function __construct()
    {

        $this->placeholder_img = asset('media/front/img/avatar-placeholder.svg');
    }

    public function index()
    {
        $advertisements = Advertisement::where('is_notification', '0')->where('is_delete',0)->orderBy('id','desc')->groupBy('brand_id')->get()->pluck('brand_id')->toArray();
        $brand_names = AdvertisementBrand::whereIn('id',$advertisements)->get();
        return view("advertisement::list",['brands'=>$brand_names]);

    }

    public function getAdvertisementData()
    {
        $all_advertisement = Advertisement::where('is_delete',0)->orderBy('id','desc')->with('brand')->get();

        return DataTables::of($all_advertisement)
            ->addColumn('status', function($advertisement){
                return ($advertisement->status>0)? 'Published': 'Unpublished';
            })
            ->addColumn('qr', function($advertisement){
                return '<img width="70px" class="qr-code" src="'.url('/storage/app/public/qr-codes/'.$advertisement->qr_code.'.bmp').'">';
            })
            ->addColumn('expiry', function($advertisement){
                return 'Start Date: '.$advertisement->start_date.'<br>End Date: '.$advertisement->end_date;
            })
            ->addColumn('mj_offer', function($advertisement){
                return $advertisement->is_mj_offer == 1 ? '<label class="label label-success">Yes</label>' : '<label class="label label-danger">No</label>';
            })
            ->addColumn('expiry_status', function($advertisement){
                $add_start_date = Carbon::parse($advertisement->start_date)->format('m/d/Y');
                $add_end_date = Carbon::parse($advertisement->end_date)->format('m/d/Y');
                $currunt_date = Carbon::now()->format('m/d/Y');

                if(strtotime($currunt_date) >= strtotime($add_start_date) && strtotime($currunt_date) <= strtotime($add_end_date))
                {
                    return '<label class="label label-success">Available</label>';
                }
                elseif (strtotime($currunt_date) < strtotime($add_start_date))
                {
                    return '<label class="label label-info">Coming Soon</label>';
                }
                else
                {
                    return '<label class="label label-danger">Expired</label>';
                }
            })
            ->make(true);
    }


    public function showUpdateAdvertisementPageForm(Request $request,$id)
    {
        $advertisement = Advertisement::find($id);

        if($advertisement)
        {

            if($request->method() == "GET" )
            {
                $all_zone = Zone::all();
                $all_brand = AdvertisementBrand::all();
                return view("advertisement::edit",["advertisement"=>$advertisement,'all_zone'=>$all_zone,'all_brand'=>$all_brand]);
            }
            else
            {
                // validate request
                $data = $request->all();
                $validate_response = Validator::make($data, [
                    'offer' => 'required',
                    'start_date' => 'required',
                    'photo' => 'sometimes|image|mimes:jpg,png,gif,jpeg',
                    'end_date' => 'required',
                    'customer_limit' => 'required|numeric'

                ]);

                if($validate_response->fails())
                {
                    return redirect($request->url())->withErrors($validate_response)->withInput();
                }
                else
                {

                    $advertisement->brand_id = $request->brand_id;
                    $advertisement->zone_id = $request->zone_id;
                    $advertisement->offer = $request->offer;
                    $advertisement->limit = $request->customer_limit;
                    $advertisement->start_date = $request->start_date;
                    $advertisement->end_date = $request->end_date;
                    $advertisement->status = $request->publish_status;

                    if($request->hasFile('photo'))
                    {

                        $uploaded_file = $request->file('photo');
                        $extension = $uploaded_file->getClientOriginalExtension();

                        $new_file_name = time().".".$extension;

                        Storage::put('public/advertisement/'.$new_file_name,file_get_contents($uploaded_file->getRealPath()));

                        $advertisement->photo = $new_file_name;

                    }

                    $file_name = round(microtime(true) * 1000);
                    $renderer = new \BaconQrCode\Renderer\Image\Png();
                    $renderer->setHeight(256);
                    $renderer->setWidth(256);
                    $writer = new \BaconQrCode\Writer($renderer);
                    $writer->writeFile($advertisement, 'storage/app/public/qr-codes/'.$file_name.'.png');

                    $advertisement->qr_code = $file_name;
                    $advertisement->save();

                    $bmp_image = Image::make(base_path() .'/storage/app/public/qr-codes/'.$file_name.'.png')->encode('bmp', 75);
                    $bmp_image->save(base_path() .'/storage/app/public/qr-codes/'.$file_name.'.bmp');
                    unlink(base_path() .'/storage/app/public/qr-codes/'.$file_name.'.png');

                    return redirect("admin/advertisement/list")->with('success','Clique offer updated successfully!');
                }

            }
        }
        else
        {
            return redirect("admin/advertisement/list");
        }

    }

    public function createAdvertisement(Request $request)
    {
        if($request->method() == "GET" )
        {
            $all_zone = Zone::all();
            $all_brand = AdvertisementBrand::all();
            return view("advertisement::create",['all_zone'=>$all_zone,'all_brand'=>$all_brand]);
        }
        else
        {
            // validate request
            $data = $request->all();
            $validate_response = Validator::make($data, [
                'offer' => 'required',
                'start_date' => 'required',
//                'photo' => 'sometimes|image|mimes:jpg,png,gif,jpeg',
                'photo' => 'required|mimes:jpg,png,gif,jpeg',
                'end_date' => 'required',
                'customer_limit' => 'required|numeric'
            ]);

            if($validate_response->fails())
            {
                return redirect($request->url())->withErrors($validate_response)->withInput();
            }
            else
            {
                $new_file_name = '';
                if($request->hasFile('photo'))
                {

                    $uploaded_file = $request->file('photo');

                    $extension = $uploaded_file->getClientOriginalExtension();

                    $new_file_name = time().".".$extension;

                    Storage::put('public/advertisement/'.$new_file_name,file_get_contents($uploaded_file->getRealPath()));
                }

                $data = array();
                $mj_offer = isset($request->mj_offer) ? 1 : 0;

                for($i=0;$i<$request->customer_limit;$i++)
                {
                    $unique_code = substr(md5(rand()),0,8);
                    $file_name = $unique_code;

                    $data['unique_code'][] =  $unique_code;
                    $data['records'][]    = array(
                        'photo'=>$new_file_name,
                        'unique_code'=>$unique_code,
                        'zone_id'=>$request->zone_id,
                        "brand_id"=>$request->brand_id,
                        'offer'=>$request->offer,
                        'limit'=>$request->customer_limit,
                        'start_date'=>$request->start_date,
                        'status'=>$request->publish_status,
                        'end_date'=>$request->end_date,
                        'is_mj_offer'=>$mj_offer,
                        'qr_code' => $file_name
                    );
                }
                foreach(array_chunk($data['records'],500) as $record){
                    Advertisement::insert($record);
                }

                foreach(array_chunk($data['unique_code'],500) as $uc){
                    $job = (new generateQr($uc))->delay(Carbon::now()->addSecond(5));
                    $this->dispatch($job);
                }

                return redirect("admin/advertisement/list");
            }
        }
    }

    public function runQueue(){
        Artisan::call('queue:listen',['--timeout'=>'0']);
    }

    public function checkQueue(){
        $job = Job::all();
        if(count($job) > 0){
            return 1;
        }
        else{
            return 0;
        }
    }

    public function deleteQr()
    {
        $advertisement = Advertisement::where('id','>','81805')->get();
        $a = 0;
        foreach ($advertisement as $key => $value) {
            // if(strtotime(Carbon::now()->subDay()->format('d-m-Y')) <= strtotime(Carbon::parse($value->created_at)->format('d-m-Y'))){
                
                // if(file_exists(base_path().'/storage/app/public/qr-codes/'.$value->qr_code.'.bmp')){
                //     unlink(base_path().'/storage/app/public/qr-codes/'.$value->qr_code.'.bmp');
                // }
                // $value->delete();
                $a++;
            // }
        }
dd($a);
        dd(strtotime(Carbon::now()->subDay()->format('d-m-Y')) <= strtotime(Carbon::parse($advertisement[count($advertisement)-70000]->created_at)->format('d-m-Y')));

        // dd(strtotime(Carbon::parse($advertisement->created_at)->format('d-m-Y')),Carbon::now()->subDay()->format('d-m-Y'));
    }

    public function deleteAdvertisement(Request $request, $id)
    {
        $advertisement = Advertisement::find($id);

        if($advertisement)
        {
            if(file_exists(base_path().'/storage/app/public/qr-codes/'.$advertisement->qr_code.'.bmp')){
                unlink(base_path().'/storage/app/public/qr-codes/'.$advertisement->qr_code.'.bmp');
            }
            $advertisement->delete();
            return redirect("admin/advertisement/list")->with('success','Clique offer deleted successfully!');
        }
        else
        {
            return redirect("admin/advertisement/list");
        }

    }

    public function setValues($total_record='',$completed_record='',$links=''){
        if($total_record != ''){
            Session::put('total_record',$total_record);
        }

        if($completed_record != '' || $completed_record == 0){
            Session::put('completed_record',$completed_record);
        }



        Session::save();
    }

    public function resetValues(){
        Session::put('total_record',0);
        Session::put('completed_record',0);
        Session::put('links','');
        Session::put('complete',0);
        Session::put('quantity',0);
        Session::put('msg','');
        Session::save();
    }

    public function downloadPdf(){
        $total_record = Session::get('total_record');
        $completed_record = Session::get('completed_record');
        $links = Session::get('links');
        $msg = Session::get('msg');
        $complete = Session::get('complete');
        $qty = Session::get('quantity');
        return array('total'=>$total_record,'done'=>$completed_record,'links'=>$links,'completed'=>$complete,'quantity'=>$qty,'msg'=>$msg);
    }

    public function deleteSelectedAdvertisement(Request $request)
    {
        $advertisements = Advertisement::whereIn('id',$request->ids)->get();
        foreach ($advertisements as $advertisement){
            if($advertisement)
            {
                if(file_exists(base_path().'/storage/app/public/qr-codes/'.$advertisement->qr_code.'.bmp')){
                    unlink(base_path().'/storage/app/public/qr-codes/'.$advertisement->qr_code.'.bmp');
                }
                $advertisement->delete();
                echo json_encode(array("success"=>'1','msg'=>'Selected records has been deleted successfully.'));
            }
            else
            {
                echo json_encode(array("success"=>'0','msg'=>'There is an issue in deleting records.'));
            }
        }
        return 1;
    }
    public function downloadtAdvertisementBmp(Request $request,$id)
    {
        if(!empty($id))
        {
            $advertisements = Advertisement::where('id',$id)->where('is_delete',0)->get();
            if($advertisements[0]->photo =='' || !file_exists(base_path() . '/storage/app/public/advertisement/'.$advertisements[0]->photo)){
                return redirect('admin/advertisement/list')->with('status','Image not found.');
            }
            if(date('Y-m-d', strtotime($advertisements[0]->end_date)) < date('Y-m-d')){
                return redirect('admin/advertisement/list')->with('status','Clique offer expired.');
            }

            $this->downloadBmpFormat($advertisements);
//            $res = response()->download(base_path().'/storage/test.zip')->deleteFileAfterSend(true);
            $res = response()->download(base_path().'/storage/cliquemj-offer.zip')->deleteFileAfterSend(true);
            //unlink(base_path().'/storage/cliquemj-offer.zip');
            return $res;
        }
        return redirect('admin/advertisement/list')->with('status','Clique offer expired or not found');
    }

    public function downloadNewPdf($id){
        return response()->download(base_path('/storage/pdf/Advertisement-offers-list-'.$id.'.pdf'));
    }
    public function downloadZip(){
        return response()->download(base_path().'/storage/cliquemj-offer.zip');
    }

    public function setQuantity($qty){
        Session::put('quantity',$qty);
        Session::save();
    }

    public function setMessage($msg){
        Session::put('msg',$msg);
        Session::save();
    }

    public function setCompleted($complete){
        Session::put('complete',$complete);
        Session::save();
    }

    public function setLinks($links){
        Session::put('links',$links);
        Session::save();
    }

    public function downloadtAdvertisement(Request $request,$id)
    {
        $this->resetValues();
        $links = array();
        ini_set('memory_limit', '-1');
        /*error_reporting(E_ALL);
        ini_set('display_errors', 'on');*/
        //print_r($request->all());exit();
        if($id == 'multiple')
        {
            $advertisements = Advertisement::where('is_delete',0)->with('brand')->orderBy('id','desc')->get();

            $advertisements = $advertisements->filter(function($advertisement){
                $add_start_date = Carbon::parse($advertisement->start_date)->format('m/d/Y');
                $add_end_date = Carbon::parse($advertisement->end_date)->format('m/d/Y');
                $currunt_date = Carbon::now()->format('m/d/Y');

                return strtotime($currunt_date) >= strtotime($add_start_date) && strtotime($currunt_date) <= strtotime($add_end_date);
            });

            if($request->brand != 'all')
            {
                $advertisements = $advertisements->filter(function($advertisement) use ($request){
                    return $advertisement->brand_id == $request->brand;
                });
            }

            if(isset($advertisements) && count($advertisements)>0)
            {
                if($request->file_type == 'pdf'){
//                    $this->setValues(count($advertisements),0);
                    $this->setQuantity(count($advertisements));
                    foreach ($advertisements->chunk(5000) as $in=>$ad){
                        $ad = collect(array_values($ad->toArray()));
                        $this->downloadPdfFormat($ad,$in);
                        $links[] = '<p> <a href="'.url('/admin/new-pdf/download/'.$in).'"><i class="glyphicon glyphicon-download-alt"></i> Advertisement-offers-list-'.($in+1).'.pdf </a></p>';
                        $this->setLinks($links);
                    }
                    return;
//                    return $this->downloadPdfFormat($advertisements);
                }
                elseif ($request->file_type == 'bmp'){
//                    $this->setValues(count($advertisements),0,'');
                    $this->setQuantity(count($advertisements));
                    $advertisements = collect(array_values($advertisements->toArray()));
                    $this->downloadBmpFormat($advertisements);
//                    $res = response()->download(base_path() . '/storage/test.zip')->deleteFileAfterSend(true);
//                    $res = response()->download(base_path() . '/storage/cliquemj-offer.zip')->deleteFileAfterSend(true);
//                    dd($res);
                    //unlink(base_path().'/storage/test.zip');
                    $links[] = '<p> <a href="'.url('/admin/download/new-zip').'"><i class="glyphicon glyphicon-download-alt"></i> cliquemj-offer.zip </a></p>';
//                    $this->setValues('','',$links);
                    $this->setLinks($links);
                    return;
//                    return $res;
                }
            }
        }
        else
        {
            $advertisements = Advertisement::where('id',$id)->where('is_delete',0)->get();
            if(isset($advertisements) && count($advertisements)>0)
            {
                if($advertisements[0]->photo =='' || !file_exists(base_path() . '/storage/app/public/advertisement/'.$advertisements[0]->photo)){
                    return redirect('admin/advertisement/list')->with('status','Image not found.');
                }
                if(date('Y-m-d', strtotime($advertisements[0]->end_date)) < date('Y-m-d')){
                    return redirect('admin/advertisement/list')->with('status','Clique offer expired.');
                }
                return $this->downloadPdfFormat($advertisements,'','download');
            }
        }
        return redirect('admin/advertisement/list')->with('status','Clique offer expired or not found');
    }

    public function downloadPdfFormat($advertisements,$pdf_name='',$is_download='')
    {
        $data=[];
        $stores = ['app-store','play-store'];
        foreach ($stores as $store)
        {
            $global_setting = GlobalSetting::where('slug',$store)->first();
            $file_name = $store;
            $renderer = new \BaconQrCode\Renderer\Image\Png();
            $renderer->setHeight(256);
            $renderer->setWidth(256);
            $writer = new \BaconQrCode\Writer($renderer);
            $writer->writeFile($global_setting->value, 'storage/app/public/qr-codes/'.$file_name.'.png');
        }

        if(count($advertisements) >1)
        {
            $completed = Session::get('complete');
            foreach($advertisements as $index=>$advertisement)
            {
                if($advertisement['photo'] =='' || !file_exists(base_path() . '/storage/app/public/advertisement/'.$advertisement['photo'])){
                    continue;
                }
                if(date('Y-m-d', strtotime($advertisement['end_date'])) < date('Y-m-d')){
                    continue;
                }

                $data[$index]['qr_code'] = $advertisement['qr_code'];
                $data[$index]['code'] = $advertisement['unique_code'];
                $data[$index]['brand_name'] = $advertisement['brand']['name'];
                $data[$index]['photo'] = $advertisement['photo'];
                $this->setCompleted($completed++);
            }
        }
        else{
            /*$data[0]['qr_code'] = $advertisements->qr_code;
            $data[0]['code'] = $advertisements->unique_code;
            $data[0]['brand_name'] = $advertisements->brand->name;
            $data[0]['photo'] = $advertisements->photo;*/
            if($advertisements[0]['photo'] =='' || !file_exists(base_path() . '/storage/app/public/advertisement/'.$advertisements[0]['photo'])){

            }
            else if(date('Y-m-d', strtotime($advertisements[0]['end_date'])) < date('Y-m-d')){

            }
            else {
                $data[0]['qr_code'] = $advertisements[0]['qr_code'];
                $data[0]['code'] = $advertisements[0]['unique_code'];
                $data[0]['brand_name'] = $advertisements[0]['brand']['name'];
                $data[0]['photo'] = $advertisements[0]['photo'];
            }
            $this->setCompleted(1);
        }

        set_time_limit(60*10);
        $this->setMessage('Generating PDF. It will take some time please Wait');
        if(file_exists(base_path().'/storage/pdf/Advertisement-offers-list-'.$pdf_name.'.pdf')){
            unlink(base_path().'/storage/pdf/Advertisement-offers-list-'.$pdf_name.'.pdf');
        }
        $pdf = SnappyPDF::loadView('advertisement::advertisement-pdf', ['advertisements'=>$data]);
        if($is_download == 'download'){
            return $pdf->download(base_path().'/storage/pdf/Advertisement-offers-list-'.$pdf_name.'.pdf');
        }else{
            return $pdf->save(base_path().'/storage/pdf/Advertisement-offers-list-'.$pdf_name.'.pdf');
        }
    }

    public function downloadBmpFormat($advertisements)
    {
        $this->setMessage('Processing...');
        $files = array();
        $completed_record = 0;

        if(count($advertisements)>1)
        {
            $csvs = "Name, QR Code \n";//Column headers
            foreach ($advertisements as $advertisement)
            {
                $csvs.= $advertisement['qr_code'].', '.$advertisement['qr_code'].'.bmp'."\n";

                if($advertisement['photo'] =='' || !file_exists(base_path() . '/storage/app/public/advertisement/'.$advertisement['photo'])){
                    continue;
                }
                if(date('Y-m-d', strtotime($advertisement['end_date'])) < date('Y-m-d')){
                    continue;
                }
                if(file_exists(base_path().'/storage/app/public/qr-codes/'.$advertisement['qr_code'].'.bmp'))
                {
                    $files[] = base_path().'/storage/app/public/qr-codes/'.$advertisement['qr_code'].'.bmp';
                }
                $completed_record += 1;
                $this->setCompleted($completed_record);
            }

            $csv_file_handler = fopen(base_path().'/storage/app/public/qr-codes/0000csvfile.csv','w');
            fwrite ($csv_file_handler, $csvs);
            fclose ($csv_file_handler);
            $files[] = base_path().'/storage/app/public/qr-codes/0000csvfile.csv';
        }
        else{
            if($advertisements[0]['photo'] =='' || !file_exists(base_path() . '/storage/app/public/advertisement/'.$advertisements[0]['photo'])){

            }
            else if(date('Y-m-d', strtotime($advertisements[0]['end_date'])) < date('Y-m-d')){

            }
            $files[] = base_path().'/storage/app/public/qr-codes/'.$advertisements[0]['qr_code'].'.bmp';

            $csv = "Name, QR Code \n";//Column headers
            $csv.= $advertisements[0]['qr_code'].','.$advertisements[0]['qr_code'].'.bmp'."\n";
            $csv_handler = fopen(base_path().'/storage/app/public/qr-codes/0000csvfile.csv','w');
            fwrite($csv_handler,$csv);
            fclose($csv_handler);
            $files[] = base_path().'/storage/app/public/qr-codes/0000csvfile.csv';
            $this->setCompleted(1);
        }
        $this->setMessage('Now system generating zip file. It will take some time please Wait');
        return Zipper::make(base_path().'/storage/cliquemj-offer.zip')->add($files)->close();

    }

    public function listGlobalOffer()
    {
        $globalOffers = Advertisement::where('is_notification', '1')->orderBy('id','desc')->get();
        return view("advertisement::list-global-offers", compact('globalOffers'));

    }

    public function listGlobalOfferData()
    {
        $globalOffers = Advertisement::where('is_notification', '1')->orderBy('id','desc')->get();
        return DataTables::of($globalOffers)
            ->addColumn('offer', function($offers){
                return $offers->offer;
            })
            ->addColumn('qr_code', function($offers){
                return $offers->qr_code;
            })
            ->addColumn('is_mj_offer', function($offers){
                return $offers->is_mj_offer == 1 ? '<label class="label label-success">Yes</label>' : '<label class="label label-danger">No</label>';
            })
            ->addColumn('start_date', function($offers){
                return date("Y-m-d", strtotime($offers->start_date));
            })
            ->addColumn('end_date', function($offers){
                return date("Y-m-d", strtotime($offers->end_date));
            })
            ->addColumn('created_at', function($offers){
                return $offers->created_at;
            })
            ->make(true);
    }

    public function createGlobalOffer(Request $request)
    {
        if($request->method() == "GET" )
        {
            $all_zone = Zone::all();
            $all_brand = AdvertisementBrand::all();
            return view("advertisement::create-global",['all_zone'=>$all_zone,'all_brand'=>$all_brand]);
        }
        else
        {
            // validate request
            $data = $request->all();
            $validate_response = Validator::make($data, [
                'offer' => 'required',
                'start_date' => 'required',
                'end_date' => 'required'
            ]);

            if($validate_response->fails())
            {
                return redirect($request->url())->withErrors($validate_response)->withInput();
            }
            else
            {
                $patients = UserInformation::where('user_status',1)->where('user_type',3)->where('is_loggedin','1')->get();
                $new_file_name = '';
                if($request->hasFile('photo'))
                {
                    $uploaded_file = $request->file('photo');
                    $extension = $uploaded_file->getClientOriginalExtension();
                    $new_file_name = time().".".$extension;
                    Storage::put('public/advertisement/'.$new_file_name, file_get_contents($uploaded_file->getRealPath()));
                }

                $unique_code = substr(md5(rand()),0,8);
                $mj_offer = isset($request->mj_offer) ? 1 : 0;

                $created_advertisement = new Advertisement;
                $created_advertisement->photo = $new_file_name;
                $created_advertisement->unique_code = $unique_code;
                $created_advertisement->brand_id = $request->brand_id;
                $created_advertisement->offer = $request->offer;
                $created_advertisement->status = '1';
                $created_advertisement->start_date = $request->start_date;
                $created_advertisement->end_date = $request->end_date;
                $created_advertisement->is_mj_offer = $mj_offer;
                $created_advertisement->is_notification = '1';
                $created_advertisement->save ();

                $file_name = $unique_code;
                $renderer = new \BaconQrCode\Renderer\Image\Png();
                $renderer->setHeight(256);
                $renderer->setWidth(256);
                $writer = new \BaconQrCode\Writer($renderer);
                $writer->writeFile($created_advertisement, 'storage/app/public/qr-codes/'.$file_name.'.png');
                $created_advertisement->qr_code = $file_name;
                $created_advertisement->save();

                $bmp_image = Image::make(base_path() .'/storage/app/public/qr-codes/'.$file_name.'.png')->encode('bmp', 75);
                $bmp_image->save(base_path() .'/storage/app/public/qr-codes/'.$file_name.'.bmp');
                unlink(base_path() .'/storage/app/public/qr-codes/'.$file_name.'.png');

                if(isset($patients)) {
                    foreach ($patients as $k=> $item) {
                        if($item->user_id == 0){
                            continue;
                        }
                        $patientsAdvOrder = new PatientAdvertisementOffer;
                        $patientsAdvOrder->advertisement_id = $created_advertisement->id;
                        $patientsAdvOrder->patient_id = $item->user_id;
                        $patientsAdvOrder->is_used = '0';
                        $patientsAdvOrder->is_notification = '1';
                        $patientsAdvOrder->save();

                        $advertisement_report = new AdvertisementOfferReport;
                        $advertisement_report->scan_by = $item->user_id;
                        $advertisement_report->offer_id = $created_advertisement->id;
                        $advertisement_report->is_notification = '1';
                        $advertisement_report->save();

                        $msg = $mj_offer == 1 ? 'You have received an MJ Offer' : 'You have received a Clique Offer';
                        $is_mj_offer = $mj_offer == 1 ? '2' : '1';
                        if($item->flag == 1)
                        {
                            $this->iosNotificaton($item->token,$msg, array('flag' => $is_mj_offer, 'global_id'=>$created_advertisement->id));
                        }
                        else
                        {
                            $this->androidNotification($item->token,$msg, array('flag' => $is_mj_offer, 'global_id'=>$created_advertisement->id));
                        }

                    }
                }
                return redirect("admin/advertisement/create-global-offer")->with('success','Clique global offer sent successfully!');
            }
        }
    }

    function iosNotificaton($token, $message, $flag) {
        $url = "https://fcm.googleapis.com/fcm/send";
        $token = $token;
        $serverKey = 'AIzaSyAxwB0y744VgFewEOsjhydqYo7IkKQbbSA';
        $title = "Clique";
        $body = $message;
        $notification = array_merge(array('text' => $body, 'sound' => 'default'), $flag);
        $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
        $json = json_encode($arrayToSend);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key='. $serverKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//Send the request
        $response = curl_exec($ch);
//Close request
        if ($response === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }

        curl_close($ch);
        return $response;
    }

    public function androidNotification($token, $message, $flag) {
        //using fcm
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'registration_ids' => array($token),
            'data' => array_merge(array("message" => $message),$flag)
        );
        $fields = json_encode ( $fields );
        $headers = array(
            'Authorization: key=AIzaSyCH-DvKVeUVjnRRI6JxfjuT1QaJTUSOyZg',
            'Content-Type: application/json'
        );
        $ch = curl_init ();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        $result = curl_exec($ch);
        // Close connection
        curl_close($ch);
        return $result;
    }

}
