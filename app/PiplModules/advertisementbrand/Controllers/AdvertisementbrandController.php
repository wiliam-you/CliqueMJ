<?php
namespace App\PiplModules\advertisementbrand\Controllers;
use App\PiplModules\advertisement\Models\Advertisement;
use App\PiplModules\zone\Models\Zone;
use Auth;
use App\User;
use App\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\PiplModules\advertisementbrand\Models\AdvertisementBrand;
use Storage;
use Datatables;
class AdvertisementbrandController extends Controller
{

	private $placeholder_img;

	public function __construct()
	{

		$this->placeholder_img = asset('media/front/img/avatar-placeholder.svg');
	}

	public function index()
	{

		return view("advertisementbrand::list");
		
	}
        
        public function getAdvertisementData()
	{

		$all_advertisementbrand = AdvertisementBrand::all();
        $all_advertisementbrand=$all_advertisementbrand->sortBy('id');
                return DataTables::of($all_advertisementbrand)
                    ->addColumn('status', function($advertisementbrand){
                     return ($advertisementbrand->status>0)? 'Published': 'Unpublished';
                  })
                        ->make(true);
	}
        
	
	public function showUpdateAdvertisementbrandPageForm(Request $request,$id)
	{
	

		$advertisementbrand = AdvertisementBrand::find($id);
		
		if($advertisementbrand)
		{
					
			
			if($request->method() == "GET" )
			{
				return view("advertisementbrand::edit",["advertisementbrand"=>$advertisementbrand,'placeholder'=>$this->placeholder_img]);
			}
			else
			{
				
				// validate request
					$data = $request->all();
					$validate_response = Validator::make($data, [
																	'name' => 'required',
				]);
				
				if($validate_response->fails())
				{
							return redirect($request->url())->withErrors($validate_response)->withInput();
				}
				else
				{

                    $advertisementbrand->name = $request->name;

                    $advertisementbrand->save();
					
					return redirect("admin/advertisementbrand/list")->with('status','Advertisement Brand updated successfully!');
				}
				
			}
		}
		else
		{
			return redirect("admin/testimonials/list");
		}
		
	}
	
	
	
	public function createAdvertisementbrand(Request $request)
	{
	
			if($request->method() == "GET" )
			{
			    return view("advertisementbrand::create");
			}
			else
			{
				
				// validate request
					$data = $request->all();
					$validate_response = Validator::make($data, [
                                        'name' => 'required',
                    ]);
				
				if($validate_response->fails())
				{
                                    return redirect($request->url())->withErrors($validate_response)->withInput();
				}
				else
				{
					
					$created_advertisement = AdvertisementBrand::create(array('name'=>$request->name));

					return redirect("admin/advertisementbrand/list")->with('status','Advertisement brand created successfully!');
					
				}
				
			}
		
	}
	
	public function deleteAdvertisementbrand(Request $request, $id)
	{
        $advertisement = AdvertisementBrand::find($id);
			
			if($advertisement)
			{
                $advertisements = Advertisement::where('brand_id',$advertisement->id)->where('is_delete',0)->get();

                foreach($advertisements as $ad)
                {
                    if(file_exists(url('/storage/app/public/qr-codes/'.$ad->qr_code))){
                        unlink(url('/storage/app/public/qr-codes/'.$ad->qr_code));
                    }
                    $ad->delete();
                }

                $advertisement->delete();
				return redirect("admin/advertisementbrand/list")->with('status','Advertisement brand deleted successfully!');
			}
			else
			{
				return redirect("admin/advertisementbrand/list");
			}
			
	}
	public function deleteSelectedAdvertisementbrand(Request $request, $id)
	{
        $advertisement = AdvertisementBrand::find($id);
			if($advertisement)
			{
                $advertisements = Advertisement::where('brand_id',$advertisement->id)->where('is_delete',0)->get();

                foreach($advertisements as $ad)
                {
                    if(file_exists(url('/storage/app/public/qr-codes/'.$ad->qr_code))){
                        unlink(base_path().'/storage/app/public/qr-codes/'.$ad->qr_code.'.png');
                    }
                    $ad->delete();
                }

                $advertisement->delete();
				echo json_encode(array("success"=>'1','msg'=>'Selected records has been deleted successfully.'));
			}
			else
			{
				 echo json_encode(array("success"=>'0','msg'=>'There is an issue in deleting records.'));
			}
			
	}
	
	
}