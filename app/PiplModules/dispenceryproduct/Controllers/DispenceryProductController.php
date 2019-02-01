<?php
namespace App\PiplModules\dispenceryproduct\Controllers;
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
class DispenceryProductController extends Controller
{

	private $placeholder_img;

	public function __construct()
	{

		$this->placeholder_img = asset('media/front/img/avatar-placeholder.svg');
	}

	public function index()
	{
		return view("dispenceryproduct::manage-product");
	}

	public function getProduct()
	{dd(4);
		return view("dispenceryproduct::list");
	}
        
        public function getProductData()
	{

		$all_product = Product::where('user_id',Auth::user()->id)->where('is_delete',0)->get();
                $all_product=$all_product->sortBy('id');
                return DataTables::of($all_product)
                     ->addColumn('status', function($product){
                      return ($product->status>0)? 'Published': 'Unpublished';
                   })
                    ->addColumn('property', function($product){
                        return $product->property->name;
                    })
                    ->addColumn('quantity', function($product){

                        return '<table>
                                <tr><td><label>'.$product->productSizes[0]->size.': </label></td><td>'.$product->productSizes[0]->quantity.'</td></tr>
                                <tr><td><label>'.$product->productSizes[1]->size.': </label></td><td>'.$product->productSizes[1]->quantity.'</td></tr>
                                <tr><td><label>'.$product->productSizes[2]->size.': </label></td><td>'.$product->productSizes[2]->quantity.'</td></tr>
                                <tr><td><label>'.$product->productSizes[3]->size.': </label></td><td>'.$product->productSizes[3]->quantity.'</td></tr>
                                <tr><td><label>'.$product->productSizes[4]->size.': </label></td><td>'.$product->productSizes[4]->quantity.'</td></tr>
                                </table>';
                      })

                        ->make(true);
	}
        
	
	public function showUpdateProductPageForm(Request $request,$id)
	{
	

		$product = Product::find($id);
		
		if($product)
		{
					
			
			if($request->method() == "GET" )
			{
				$property = Property::where('status','1')->where('is_delete',0)->get();
				return view("dispenceryproduct::edit",["product"=>$product,'properties'=>$property]);
			}
			else
			{

				// validate request
					$data = $request->all();
					$validate_response = Validator::make($data, [
                        'name' => 'required',
                        'price.*' => 'required|numeric',
                        'property_id' => 'required',
                        'photo' => 'sometimes|image',
                        'description' => 'required',
                        'quantity.*' => 'required|numeric',
                ]);
				
				if($validate_response->fails())
				{
							return redirect($request->url())->withErrors($validate_response)->withInput();
				}
				else
				{

                    $product->name = $request->name;
                    $product->property_id = $request->property_id;
                    $product->status = $request->publish_status;
                    $product->description = $request->description;

					if($request->hasFile('photo'))
					{
						
						$uploaded_file = $request->file('photo');
						
						
						$extension = $uploaded_file->getClientOriginalExtension();
							
						$new_file_name = time().".".$extension;
				
						Storage::put('public/product/'.$new_file_name,file_get_contents($uploaded_file->getRealPath()));

						$product->image = $new_file_name;
						
					}

					$product->save();

                    ProductSize::where('product_id',$product->id)->delete();

                    foreach($request->size as $index=>$size)
                    {
                        $product_size = new ProductSize();

                        $product_size->product_id = $product->id;
                        $product_size->size = $size;
                        $product_size->quantity = $request->quantity[$index];
                        $product_size->price = $request->price[$index];

                        $product_size->save();
                    }
					
					return redirect("dispencery/product/list")->with('status','Product updated successfully!');
				}
				
			}
		}
		else
		{
			return redirect("dispencery/product/list");
		}
		
	}
	
	
	
	public function createProduct(Request $request)
	{
	
			if($request->method() == "GET" )
			{
				$property = Property::where('status','1')->where('is_delete',0)->get();
				return view("dispenceryproduct::create",['properties'=>$property]);
			}
			else
			{
				
				// validate request
					$data = $request->all();
					$validate_response = Validator::make($data, [
                        'name' => 'required',
                        'price.*' => 'required|numeric',
                        'property_id' => 'required',
                        'photo' => 'required|image',
                        'description' => 'required',
                        'quantity.*' => 'required|numeric',
                    ]);
				
				if($validate_response->fails())
				{
                                    return redirect($request->url())->withErrors($validate_response)->withInput();
				}
				else
				{
                    $created_product = Product::create(array("name"=>$request->name,'user_id'=>Auth::user()->id,'property_id' => $request->property_id,'status' => $request->publish_status,'description'=>$request->description));

                    if($request->hasFile('photo'))
                    {

                        $uploaded_file = $request->file('photo');


                        $extension = $uploaded_file->getClientOriginalExtension();

                        $new_file_name = time().".".$extension;

                        Storage::put('public/product/'.$new_file_name,file_get_contents($uploaded_file->getRealPath()));

                        $created_product->image = $new_file_name;
                        $created_product->save();

                    }

                    foreach($request->size as $index=>$size)
                    {
                        $product_size = new ProductSize();

                        $product_size->product_id = $created_product->id;
                        $product_size->size = $size;
                        $product_size->quantity = $request->quantity[$index];
                        $product_size->price = $request->price[$index];

                        $product_size->save();
                    }

					
					
					return redirect("/dispencery/product/list")->with('status','Product created successfully!');
					
				}
				
			}
		
	}
	
	public function deleteProduct(Request $request, $id)
	{
			$product = Product::find($id);
			
			if($product)
			{
                $product->is_delete = 1;
				$product->save();
				return redirect("dispencery/product/list")->with('status','Product deleted successfully!');
			}
			else
			{
				return redirect("dispencery/product/list");
			}
			
	}
	public function deleteSelectedProduct(Request $request, $id)
	{
			$product = Product::find($id);
			if($product)
			{
				$product->is_delete = 1;
                $product->save();
				echo json_encode(array("success"=>'1','msg'=>'Selected records has been deleted successfully.'));
			}
			else
			{
				 echo json_encode(array("success"=>'0','msg'=>'There is an issue in deleting records.'));
			}
			
	}

	public function productQuantity(Request $request,$id)
    {
        $product = Product::find($id);
        if($request->method()=='GET')
        {
            return view('dispenceryproduct::product-weight-list',['product'=>$product]);
        }
        else
        {
            $product_size = ProductSize::find($request->size_id);
            $product_id = $product_size->product_id;
            $report = new Report();

            $report->dispensary_id = $product_size->product->user_id;
            $report->patient_id = $request->customer;
            $report->product_id = $product_size->product_id;
            $report->size = $product_size->size;
            $report->quantity = $request->quantity;
            $report->price = $product_size->price;

            $report->save();

            $product_size->quantity -= $request->quantity;
            $product_size->save();

            return redirect('dispencery/product/update/quantity/'.$product_id)->with('status','Product Quantity Deduct Successfully!');
        }

    }
	
	
}