<?php
namespace App\PiplModules\getstartedsection\Controllers;
use Auth;
use Auth\User;
use App\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\PiplModules\getstartedsection\Models\FrontGetStart;
use Storage;
use Datatables;
class GetStartedController extends Controller
{

	private $placeholder_img;

	public function __construct()
	{

		$this->placeholder_img = asset('media/front/img/avatar-placeholder.svg');
	}

	public function index()
	{

		return view("getstartedsection::list");
		
	}
        
        public function getGetStartedData()
	{

		$all_data = FrontGetStart::all();
        $all_data=$all_data->sortBy('id');
                return DataTables::of($all_data)
//                    ->addColumn('status', function($property){
//                     return ($property->status>0)? 'Published': 'Unpublished';
//                  })
                        ->make(true);
		// return view("property::list",["property"=>$all_testimonials,'placeholder'=>$this->placeholder_img]);
		
	}
        
	
	public function showUpdateGetStartedPageForm(Request $request,$id)
	{


        $all_data = FrontGetStart::find($id);
		
		if($all_data)
		{
					
			
			if($request->method() == "GET" )
			{
				return view("getstartedsection::edit",["data"=>$all_data,'placeholder'=>$this->placeholder_img]);
			}
			else
			{
				
				// validate request
					$data = $request->all();
					$validate_response = Validator::make($data, [
					'title' => 'required|max:255',
                    'description' => 'required|max:255',
				]);
				
				if($validate_response->fails())
				{
							return redirect($request->url())->withErrors($validate_response)->withInput();
				}
				else
				{

                    $all_data->title = $request->title;
                    $all_data->description = $request->description;
                    $all_data->save();
					
					return redirect("admin/get-started-section/list")->with('status','Get started section updated successfully!');
				}
				
			}
		}
		else
		{
			return redirect("admin/get-started-section/list");
		}
		
	}
	
	
	
	public function createProperty(Request $request)
	{
	
			if($request->method() == "GET" )
			{
				return view("property::create");
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
					
					$created_property = Property::create(array("name"=>$request->name,'status'=>$request->publish_status));

					return redirect("admin/property/list")->with('status','Property created successfully!');
					
				}
				
			}
		
	}
	
	public function deleteProperty(Request $request, $id)
	{
			$property = Property::find($id);
			
			if($property)
			{
				$property->delete();
				return redirect("admin/Property/list")->with('status','Property deleted successfully!');
			}
			else
			{
				return redirect("admin/Property/list");
			}
			
	}
	public function deleteSelectedProperty(Request $request, $id)
	{
			$property = Property::find($id);
			if($property)
			{
				$property->delete();
				echo json_encode(array("success"=>'1','msg'=>'Selected records has been deleted successfully.'));
			}
			else
			{
				 echo json_encode(array("success"=>'0','msg'=>'There is an issue in deleting records.'));
			}
			
	}
	
	
}