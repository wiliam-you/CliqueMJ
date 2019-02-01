<?php
namespace App\PiplModules\report\Controllers;
use App\PiplModules\dispenceryproduct\Models\Product;
use App\PiplModules\webservice\Models\AdvertisementOfferReport;
use App\PiplModules\webservice\Models\PatientRecord;
use App\PiplModules\webservice\Models\PatientSharedOffer;
use App\UserInformation;
use App\PiplModules\advertisement\Models\Advertisement;
use Auth;
use App\User;
use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use PhpParser\Node\Expr\AssignOp\Concat;
use Validator;
use App\PiplModules\report\Models\Report;
use Storage;
use Datatables;

class ReportController extends Controller
{

    private $placeholder_img;

    public function __construct()
    {

        $this->placeholder_img = asset('media/front/img/avatar-placeholder.svg');
    }

    public function saleReport()
    {
        $all_product = Product::where('status',1)->where('is_delete',0)->get();
        $all_dispensary = UserInformation::where('user_status',1)->where('user_type',2)->get();
        return view("report::sale-report",['all_product'=>$all_product,'all_dispensary'=>$all_dispensary]);
    }

    public function saleReportData(Request $request)
    {
        $reports = Report::all();

        if($request->product!='')
        {
            $reports=$reports->filter(function($report) use ($request){
                return $report->product_id==$request->product;
            });
        }

        if($request->dispensary!='')
        {
            $reports=$reports->filter(function($report) use ($request){
                return $report->dispensary_id==$request->dispensary;
            });
        }

        if($request->start_date!='' && $request->end_date!='')
        {
            $reports=$reports->filter(function($report) use ($request){
                if(Carbon::parse($request->start_date)->format('m/d/Y')<=Carbon::parse($report->created_at)->format('m/d/Y') && Carbon::parse($request->end_date)->format('m/d/Y')>=Carbon::parse($report->created_at)->format('m/d/Y'))
                {
                    return true;
                }
            });
        }

        foreach($reports as $report)
        {
            $report->weight=$report->size;
            $report->quantity=$report->quantity;
        }

        $reports=$reports->sortBy('id');
        return DataTables::of($reports)
            ->addColumn('dispensary_name', function($report){
                return $report->dispensary->dispensary_name;
            })
            ->addColumn('product_name', function($report){
                return $report->product->name;
            })
//                    ->addColumn('weight', function($report){
//                        return $report->size;
//                    })
            ->addColumn('price', function($report){
                return '$'.$report->price;
            })
            ->addColumn('sold_date', function($report){
                return Carbon::parse($report->created_at)->format('m/d/Y');
            })
            ->addColumn('total_amount', function($report){
                return '$'.$report->price*$report->quantity;
            })
            ->make(true);
    }


    public function patientReport()
    {
        $all_product = Product::where('status',1)->where('is_delete',0)->get();
        $all_patient = UserInformation::where('user_status',1)->where('user_type',3)->get();
        return view("report::patient-report",['all_product'=>$all_product,'all_patient'=>$all_patient]);

    }


    public function patientReportData(Request $request)
    {
        $reports = Report::all();

        if($request->product!='')
        {
            $reports=$reports->filter(function($report) use ($request){
                return $report->product_id==$request->product;
            });
        }

        if($request->patient!='')
        {
            $reports=$reports->filter(function($report) use ($request){
                return $report->patient_id==$request->patient;
            });
        }

        $reports=$reports->sortBy('id');
        return DataTables::of($reports)
            ->addColumn('patient_name', function($report){
                return $report->patient->first_name.' '.$report->patient->last_name;
            })
            ->addColumn('product_name', function($report){
                return $report->product->name;
            })
            ->addColumn('weight', function($report){
                return $report->size;
            })
            ->addColumn('price', function($report){
                return '$'.$report->price;
            })
            ->addColumn('sold_date', function($report){
                return Carbon::parse($report->created_at)->format('m/d/Y');
            })
            ->addColumn('total_amount', function($report){
                return '$'.$report->price*$report->quantity;
            })
            ->make(true);
    }


    public function couponReport()
    {
        return view("report::coupon-report");
    }

    public function couponReportData()
    {
        $users = UserInformation::where('user_type',3)->with('user')->get();

        foreach($users as $user)
        {
            $user->patient_name = $user->first_name.' '.$user->last_name;
        }

        $users=$users->sortBy('id');
        return DataTables::of($users)
//            ->addColumn('patient_name', function($user){
//                return $user->first_name.' '.$user->last_name;
//            })
            ->addColumn('coupon_used', function($user){
                return $user->getTotalCouponUsedCount->count();
            })
            ->addColumn('offer_used', function($user){
                return $user->getTotalOfferUsedCount->count();
            })
            ->addColumn('free_gram', function($user){
                return $user->getTotalFreeGrameCount($user->user_id);
            })
            ->make(true);
    }

    public function shareReport()
    {
        return view("report::share-report");
    }

    public function shareReportData()
    {
        $users = PatientSharedOffer::all();

        foreach($users as $user)
        {
            $user->sender = $user->senderInfo->first_name.' '.$user->senderInfo->last_name;
            $user->receiver = $user->receiverInfo ? $user->receiverInfo->first_name.' '.$user->receiverInfo->last_name : 'Waiting for submit offer code';
            $user->offer = $user->advertiseInfo->brand->name;
        }

        $users=$users->sortBy('id');
        return DataTables::of($users)
            ->make(true);
    }

    public function advertisementReport()
    {
        /*$users = AdvertisementOfferReport::orderBy('id','desc')->get();
        dd($users);*/
        return view("report::advertisement-report");
    }

    public function advertisementReportData()
    {
        $users = AdvertisementOfferReport::where('is_notification', '0')->orderBy('id','desc')->get();
        foreach($users as $user)
        {
            $user->scan = $user->scanBy->first_name.' '.$user->scanBy->last_name;
            $user->redeem = $user->redeemBy ? $user->redeemBy->first_name.' '.$user->redeemBy->last_name : 'Not yet redeemed';
            $user->offer = $user->advertisement->brand->name;
        }

        return DataTables::of($users)
            ->make(true);
    }

    public function couponOfferViewReport()
    {
        /*$users = PatientRecord::where('is_notification', '0')->orderBy('id','desc')->get();
        dd($users);*/
        return view("report::coupon-offer-view-report");
    }

    public function couponOfferViewReportData()
    {
//        $users = PatientRecord::orderBy('id','desc')->get();
        $users = PatientRecord::where('is_notification', '0')->orderBy('id','desc')->get();
        if(isset($users) && count($users)>0)
        {
            foreach($users as $user)
            {
                $patient_name = isset($user->userInformation->first_name) ? $user->userInformation->first_name:'';
                $patient_name .= isset($user->userInformation->last_name) ? ' '.$user->userInformation->last_name:'';
                $user->patient_name = $patient_name;

                if(isset($user->offer->brand->name))
                {
                    $user->advertisement_name = isset($user->offer_id) && $user->offer_id > 0 ? $user->offer->brand->name : '-';
                }
                else{
                    $user->advertisement_name = '';
                }
                $user->advertisement_view = isset($user->times_of_view_offer) && $user->times_of_view_offer !=''? $user->times_of_view_offer:'';
                $user->coupon_name = isset($user->coupon_name)?$user->coupon_name:'';
                $user->advertisement_mj_view = isset($user->times_of_view_mj_offer) && $user->times_of_view_mj_offer !='' ? $user->times_of_view_mj_offer:'';
//                $user->coupon_view = isset($user->times_of_view_coupon)?$user->times_of_view_coupon:'';
            }
        }
        return DataTables::of($users)
            ->make(true);
    }

    /***
     * Global offer reports
     */
    public function globalOfferViewReport()
    {
        $users = PatientRecord::where('is_notification', '1')->orderBy('id','desc')->get();

//        $users = AdvertisementOfferReport::where('is_notification', '1')->orderBy('id','desc')->get();

        return view("report::global-offer-report");
    }

    public function globalOfferViewReportData()
    {
        /*$users = AdvertisementOfferReport::where('is_notification', '1')->orderBy('id','desc')->get();

        foreach($users as $user)
        {
            $user->scan = isset($user->scanBy) ? $user->scanBy->first_name.' '.$user->scanBy->last_name : '--';
            $user->redeem = $user->redeemBy ? $user->redeemBy->first_name.' '.$user->redeemBy->last_name : 'Not yet redeemed';
            $user->offer = $user->advertisement->brand->name;
        }

        return DataTables::of($users)
            ->make(true);*/

        $users = PatientRecord::where('is_notification', '1')->orderBy('id','desc')->get();
        if(isset($users) && count($users)>0)
        {
            foreach($users as $user)
            {
                $usersAdvReport = AdvertisementOfferReport::where('offer_id', $user->offer_id)->where('redeem_by', '!=' , '0')->where('is_notification', '1')->get();

                $patient_name = isset($user->userInformation->first_name) ? $user->userInformation->first_name:'';
                $patient_name .= isset($user->userInformation->last_name) ? ' '.$user->userInformation->last_name:'';
                $user->patient_name = $patient_name;

                if(isset($user->offer->brand->name))
                {
                    $user->advertisement_name = isset($user->offer_id) && $user->offer_id > 0 ? $user->offer->brand->name : '-';
                }
                else{
                    $user->advertisement_name = '';
                }
//                $user->redeem_count = (isset($usersAdvReport) && count($usersAdvReport) > 0) ? count($usersAdvReport) : '0';
                $user->redeem_count = $user->times_of_redeem_offer;
//                $user->redeem_count = (isset($usersAdvReport) && count($usersAdvReport) > 0) ? count($usersAdvReport) : '0';

                $user->offerName = isset($user->offer) ? $user->offer->offer : '--';

//                $user->advertisement_view = isset($user->times_of_view_offer) && $user->times_of_view_offer !=''?$user->times_of_view_offer:'';
//                $user->advertisement_view = isset($user->times_of_view_offer) && $user->times_of_view_offer !=''?$user->times_of_view_offer:'';
                $user->advertisement_view = isset($user->times_of_view_offer) && $user->times_of_view_offer !='' ? $user->times_of_view_offer : $user->times_of_view_mj_offer;
                $user->coupon_name = isset($user->coupon_name)?$user->coupon_name:'';
                $user->coupon_view = isset($user->times_of_view_coupon)?$user->times_of_view_coupon:'';
            }
        }
        return DataTables::of($users)
            ->make(true);

    }
}