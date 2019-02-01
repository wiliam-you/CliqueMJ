<?php
namespace App\PiplModules\dispencerycustomer\Controllers;
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
class DispenceryCustomerController extends Controller
{

	private $placeholder_img;

	public function __construct()
	{

		$this->placeholder_img = asset('media/front/img/avatar-placeholder.svg');
	}

	public function index()
	{
		return view("dispencerycustomer::customer-list");
	}

	public function getCustomerData()
	{

		$all_customer = Report::where('dispensary_id',Auth::user()->id)->get();

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
                ->make(true);
	}
}