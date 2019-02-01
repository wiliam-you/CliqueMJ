<?php
namespace App\PiplModules\advertizesection\Controllers;
use Auth;
use Auth\User;
use App\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\PiplModules\advertizesection\Models\FrontAdvertize;
use Storage;
use Datatables;
class AdvertizeController extends Controller
{

	private $placeholder_img;

	public function __construct()
	{

		$this->placeholder_img = asset('media/front/img/avatar-placeholder.svg');
	}

	public function index()
	{
        return view("advertizesection::list");
		
	}
        
        public function getAdvertizeData()
	{

		$all_advertize = FrontAdvertize::all();
                $all_advertize=$all_advertize->sortBy('id');
                return DataTables::of($all_advertize)
                        ->make(true);
	}
        
	
	public function showUpdateAdvertizePageForm(Request $request,$id)
	{


        $advertize = FrontAdvertize::find($id);
		
		if($advertize)
		{
					
			
			if($request->method() == "GET" )
			{
				return view("advertizesection::edit",["advertize"=>$advertize,'placeholder'=>$this->placeholder_img]);
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

                    $advertize->title = $request->title;
                    $advertize->description = $request->description;
                    if($request->hasFile('photo'))
                    {

                        $uploaded_file = $request->file('photo');


                        $extension = $uploaded_file->getClientOriginalExtension();

                        $new_file_name = time().".".$extension;

                        Storage::put('public/advertize-section/'.$new_file_name,file_get_contents($uploaded_file->getRealPath()));

                        $advertize->image  = $new_file_name;
                    }
                    $advertize->save();
					
					return redirect("admin/advertise-section/list")->with('status','Advertise section updated successfully!');
				}
				
			}
		}
		else
		{
			return redirect("admin/advertise-section/list");
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