<?php
namespace App\PiplModules\property\Controllers;
use App\PiplModules\product\Models\Product;
use Auth;
use Auth\User;
use App\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\PiplModules\property\Models\Property;
use Storage;
use Datatables;
class PropertyController extends Controller
{

	private $placeholder_img;

	public function __construct()
	{

		$this->placeholder_img = asset('media/front/img/avatar-placeholder.svg');
	}

	public function index()
	{

		return view("property::list");
		
	}
        
        public function getPropertyData()
	{

		$all_property = Property::where('is_delete',0)->get();
                $all_property=$all_property->sortBy('id');
                return DataTables::of($all_property)
                    ->addColumn('status', function($property){
                     return ($property->status>0)? 'Published': 'Unpublished';
                  })
                        ->make(true);
	}
        
	
	public function showUpdatePrpoertyPageForm(Request $request,$id)
	{
	

		$property = Property::find($id);
		
		if($property)
		{
					
			
			if($request->method() == "GET" )
			{
				return view("property::edit",["property"=>$property,'placeholder'=>$this->placeholder_img]);
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
					
					$property->name = $request->name;
					$property->status = $request->publish_status;
					$property->save();
					
					return redirect("admin/property/list")->with('status','Property updated successfully!');
				}
				
			}
		}
		else
		{
			return redirect("admin/property/list");
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
                $property->is_delete = 1;
				$property->save();
                $products = Product::where('property_id',$property->id)->get();
                foreach($products as $product)
                {
                    $product->is_delete = 1;
                    $product->save();
                }

				return redirect("admin/property/list")->with('status','Property deleted successfully!');
			}
			else
			{
				return redirect("admin/property/list");
			}
			
	}
	public function deleteSelectedProperty(Request $request, $id)
	{
			$property = Property::find($id);
			if($property)
			{
				$property->is_delete = 1;
				$property->save();

                $products = Product::where('property_id',$property->id)->get();
                foreach($products as $product)
                {
                    $product->is_delete = 1;
                    $product->save();
                }

				echo json_encode(array("success"=>'1','msg'=>'Selected records has been deleted successfully.'));
			}
			else
			{
				 echo json_encode(array("success"=>'0','msg'=>'There is an issue in deleting records.'));
			}
			
	}
	
	
}