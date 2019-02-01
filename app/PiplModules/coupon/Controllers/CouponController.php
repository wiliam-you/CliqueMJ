<?php
namespace App\PiplModules\coupon\Controllers;
use App\PiplModules\property\Models\Property;
use App\PiplModules\zone\Models\Cluster;
use App\PiplModules\zone\Models\Zone;
use App\PiplModules\zone\Models\ZoneDispencery;
use Auth;
use App\User;
use App\UserInformation;
use App\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\PiplModules\coupon\Models\Coupon;
use Storage;
use Datatables;
use PDF;
use Carbon\Carbon;
class CouponController extends Controller
{

    private $placeholder_img;

    public function __construct()
    {

        $this->placeholder_img = asset('media/front/img/avatar-placeholder.svg');
    }

    public function clusterList()
    {
        /*$cluster = Cluster::find(3);
        dd($cluster);*/
        return view("coupon::cluster-list");
    }

    public function index($id)
    {
        $cluster = Cluster::find($id);
        return view("coupon::list",['cluster'=>$cluster]);
    }

    public function getCouponData($id)
    {
        $all_coupon = Coupon::where('cluster_id',$id)->orderBy('id','asc')->get();

        if(count($all_coupon)>0)
        {
            foreach($all_coupon as $index=>$coupon)
            {
                $coupon->sr = $index+1;
            }
        }

        return DataTables::of($all_coupon)
            ->addColumn('coupon_offer', function($coupon){
                return $coupon->coupon_value.' grams free';
                if($coupon->is_expire==0)
                {
                    return '<label class="label label-success">Available</label>';
                }
                else
                {
                    return '<label class="label label-danger">Expired</label>';
                }

            })
            ->addColumn('check', function($coupon){
                if($coupon->is_expire==0)
                {
                    return '<form id="testimonial_delete_'.$coupon->id.'"  method="get" action="'.url("/admin/coupon/delete/".$coupon->id).'"><button onclick="confirmDelete('.$coupon->id.');" class="btn btn-sm btn-danger" type="button">Delete</button></form>';
                }

            })
            ->addColumn('name', function($coupon){
                return $coupon->dispencery->userInformation->dispensary_name;
            })
            ->make(true);
    }

    public function clusterData()
    {
        $clusters = Cluster::where('current_week','>',0)->get();

        $clusters = $clusters->filter(function($query){
            return $query->limit==$query->clusterDispencery->count();
        });

        foreach($clusters as $cluster)
        {
            $cluster->name = $cluster->title;
            $cluster->zone_name = $cluster->zone->title;
        }

        return Datatables::of($clusters)
            ->make(true);
    }


    public function showUpdateCouponPageForm(Request $request,$id)
    {


        $coupon = Coupon::find($id);
        if($coupon)
        {
            if($request->method() == "GET" )
            {
                $properties = Property::all();
                $cluster = Cluster::find($coupon->cluster_id);
                return view("coupon::edit",["coupon"=>$coupon,'properties'=>$properties,'cluster'=>$cluster]);
            }
            else
            {

                // validate request
                $data = $request->all();
                $validate_response = Validator::make($data, [
                    'code' => 'required',
                    'coupon_value' => 'required',
                ]);

                if($validate_response->fails())
                {
                    return redirect($request->url())->withErrors($validate_response)->withInput();
                }
                else
                {

                    $coupon->code = $request->code;
                    $coupon->coupon_value = $request->coupon_value;
                    $coupon->save();

                    return redirect("admin/coupon/list/".$coupon->cluster_id)->with('status','Coupon updated successfully!');
                }

            }
        }
        else
        {
            return redirect("admin/coupon/list/".$coupon->cluster_id);
        }

    }



    public function createCoupon(Request $request,$id)
    {
        $cluster = Cluster::find($id);
        if($request->method() == "GET" )
        {
            return view("coupon::list",['cluster'=>$cluster]);
        }
        else
        {

            // validate request
            $data = $request->all();
            $validate_response = Validator::make($data, [
                'code' => 'required',
            ]);

            if($validate_response->fails())
            {
                return redirect($request->url())->withErrors($validate_response)->withInput();
            }
            else
            {
                $coupons = Coupon::where('cluster_id',$id)->get();

                if(count($coupons)==0)
                {
                    foreach($cluster->clusterDispencery as $disp)
                    {
                        $coupon = Coupon::create(array("code"=>$request->code,'coupon_value'=>0,'cluster_id'=>$id,'is_expire'=>$request->status,'dispensary_id'=>$disp->dispencery_id,'unique_code'=>substr(md5(rand()),0,8)));

                        $file_name = round(microtime(true) * 1000);
                        $renderer = new \BaconQrCode\Renderer\Image\Png();
                        $renderer->setHeight(256);
                        $renderer->setWidth(256);
                        $writer = new \BaconQrCode\Writer($renderer);
                        $writer->writeFile($coupon, 'storage/app/public/qr-codes/'.$file_name.'.png');
                        $coupon->qr_code = $file_name.'.png';
                        $coupon->save();

                        $msg = 'Coupon created successfully.Please provide QR code to the dispensaries';
                    }

                }
                else
                {
                    $coupons = Coupon::where('cluster_id',$id)->get();
                    foreach($coupons as $coupon) {
                        $coupon->code = $request->code;
                        $coupon->coupon_value = 0;
                        $coupon->is_expire = $request->status;
                        $coupon->unique_code = substr(md5(rand()),0,8);
                        $coupon->save();

                        $file_name = round(microtime(true) * 1000);
                        $renderer = new \BaconQrCode\Renderer\Image\Png();
                        $renderer->setHeight(256);
                        $renderer->setWidth(256);
                        $writer = new \BaconQrCode\Writer($renderer);
                        $writer->writeFile($coupon, 'storage/app/public/qr-codes/'.$file_name.'.png');
                        $coupon->qr_code = $file_name.'.png';
                        $coupon->save();

                        $msg = 'Coupon updated successfully. Please provide new QR code to the dispensaries';
                    }
                }

                return redirect("admin/coupon/list/".$id)->with('status',$msg);

            }

        }

    }

    public function deleteCoupon(Request $request, $id)
    {
        $coupon = Coupon::find($id);
        if($coupon)
        {
            $coupon->is_expire = 1;
            $coupon->save();
            return redirect("admin/coupon/list")->with('status','Coupon delete successfully!');
        }
        else
        {
            return redirect("admin/coupon/list")->with('error','Something went wrong');
        }

    }
    public function deleteSelectedCoupon(Request $request, $id)
    {
        $coupon = Coupon::find($id);
        if($coupon)
        {
            $coupon->delete();
            echo json_encode(array("success"=>'1','msg'=>'Selected records has been deleted successfully.'));
        }
        else
        {
            echo json_encode(array("success"=>'0','msg'=>'There is an issue in deleting records.'));
        }

    }

    public function getPdf($id)
    {
        $coupons = Coupon::where('cluster_id',$id)->get();
        $data=[];
        foreach($coupons as $index=>$coupon)
        {
            $data[$index]['qr_code'] = $coupon->qr_code;
            $data[$index]['code'] = $coupon->unique_code;
            $data[$index]['dispensary_name'] = $coupon->dispencery->userInformation->dispensary_name;
        }
        $cluster = Cluster::find($id);
        $cluster_name = $cluster->title;
        $zone = $cluster->zone->title;

        $pdf = PDF::loadView('coupon::coupons-pdf', ['coupons'=>$data,'disp_name'=>$cluster_name,'zone'=>$zone]);
        return $pdf->download($cluster_name.'-coupons-list.pdf');
        return redirect('admin/coupon/list/'.$id);
    }
}