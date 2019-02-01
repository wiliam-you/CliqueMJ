<?php
namespace App\PiplModules\dispencarysection\Controllers;
use Auth;
use Auth\User;
use App\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\PiplModules\dispencarysection\Models\FrontDispencary;
use Storage;
use Datatables;
class DispencaryController extends Controller
{

	private $placeholder_img;

	public function __construct()
	{

		$this->placeholder_img = asset('media/front/img/avatar-placeholder.svg');
	}

	public function index()
	{

		return view("dispencarysection::list");
		
	}
        
        public function getDispencaryData()
	{

		$all_property = FrontDispencary::all();
                $all_property=$all_property->sortBy('id');
                return DataTables::of($all_property)
                        ->make(true);
	}
        
	
	public function showUpdateDispencaryPageForm(Request $request,$id)
	{


        $dispencery = FrontDispencary::find($id);
		
		if($dispencery)
		{
					
			
			if($request->method() == "GET" )
			{
				return view("dispencarysection::edit",["dispencery"=>$dispencery,'placeholder'=>$this->placeholder_img]);
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

                    $dispencery->title = $request->title;
                    $dispencery->description = $request->description;
                    $dispencery->save();
					
					return redirect("admin/dispencary-section/list")->with('status','Dispencary section updated successfully!');
				}
				
			}
		}
		else
		{
			return redirect("admin/dispencary-section/list");
		}
		
	}

	
	
}