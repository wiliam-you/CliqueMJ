<?php
namespace App\PiplModules\dispenceryreport\Controllers;
use App\PiplModules\report\Models\Report;
use Auth;
use Auth\User;
use App\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\PiplModules\product\Models\Product;
use App\PiplModules\property\Models\Property;
use App\PiplModules\product\Models\ProductSize;
use Storage;
use Datatables;
use Carbon\Carbon;
class DispenceryReportController extends Controller
{

	private $placeholder_img;

	public function __construct()
	{

		$this->placeholder_img = asset('media/front/img/avatar-placeholder.svg');
	}

	public function index()
	{
		return view("dispenceryreport::customer-list");
	}

	public function getCustomerData(Request $request)
	{

		$all_customer = Report::where('dispensary_id',Auth::user()->id)->get();

        if($request->start_date!='' && $request->end_date!='')
        {
            $all_customer=$all_customer->filter(function($report) use ($request){
                if(Carbon::parse($request->start_date)->format('m/d/Y')<=Carbon::parse($report->created_at)->format('m/d/Y') && Carbon::parse($request->end_date)->format('m/d/Y')>=Carbon::parse($report->created_at)->format('m/d/Y'))
                {
                    return true;
                }
            });
        }

        foreach ($all_customer as $customer) {
            $customer->patient_name = $customer->patient->first_name.' '.$customer->patient->last_name;
            $customer->product_name = $customer->product->name;
            $customer->property = $customer->product->property->name;
            $customer->total = '$'.($customer->price*$customer->quantity);
            $customer->quantity = $customer->quantity;
            $customer->price = '$'.$customer->price;
        }

        $all_customer=$all_customer->sortBy('id');
                return DataTables::of($all_customer)
                     ->addColumn('created_at', function($customer){
                      return Carbon::parse($customer->created_at)->format('m/d/Y');
                   })


                ->make(true);
	}
}