<?php
namespace App\PiplModules\product\Controllers;
use App\PiplModules\product\Models\ProductSize;
use Auth;
use App\User;
use App\UserInformation;
use App\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\PiplModules\product\Models\Product;
use App\PiplModules\property\Models\Property;
use Storage;
use Datatables;
class ProductController extends Controller
{

	private $placeholder_img;

	public function __construct()
	{

		$this->placeholder_img = asset('media/front/img/avatar-placeholder.svg');
	}

	public function index()
	{
		return view("product::manage-product");
	}

	public function listDispencary() {
        $all_users = UserInformation::all();
        $all_users=$all_users->sortByDesc('id');
        $registered_users = $all_users->filter(function ($user) {
            return ($user->user_type == 2 && $user->user->is_delete==0);
        });
        return Datatables::of($registered_users)
                        ->addColumn('name', function($regsiter_user) {
                            return $regsiter_user->dispensary_name;
                        })
                        ->make(true);
    }

	public function getProduct($id)
	{
		$user = User::find($id);
		return view("product::list",['user'=>$user]);
	}
        
        public function getProductData($id)
	{

		$all_product = Product::where('user_id',$id)->where('is_delete',0)->get();
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
		// return view("testimonial::list",["testimonials"=>$all_testimonials,'placeholder'=>$this->placeholder_img]);
		
	}
        
	
	public function showUpdateProductPageForm(Request $request,$id)
	{
	

		$product = Product::find($id);
		
		if($product)
		{
					
			
			if($request->method() == "GET" )
			{
				$property = Property::where('status','1')->where('is_delete',0)->get();
				return view("product::edit",["product"=>$product,'properties'=>$property]);
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
					
					return redirect("admin/product/all/".$product->user_id)->with('status','Product updated successfully!');
				}
				
			}
		}
		else
		{
			return redirect("admin/product/all/".$product->user_id);
		}
		
	}
	
	
	
	public function createProduct(Request $request,$id)
	{
	
			if($request->method() == "GET" )
			{
				$property = Property::where('status','1')->where('is_delete',0)->get();
				return view("product::create",['id'=>$id,'properties'=>$property]);
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
					$created_product = Product::create(array("name"=>$request->name,'user_id'=>$id,'property_id' => $request->property_id,'status' => $request->publish_status,'description'=>$request->description));

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
					
					return redirect("admin/product/all/".$id)->with('status','Product created successfully!');
					
				}
				
			}
		
	}
	
	public function deleteProduct(Request $request, $id)
	{
			$product = Product::find($id);
			
			if($product)
			{
				$id = $product->user_id;
				ProductSize::where('product_id',$id)->delete();
                $product->is_delete = 1;
                $product->save();
				return redirect("admin/product/all/".$id)->with('status','Product deleted successfully!');
			}
			else
			{
				return redirect("admin/product/all/".$id);
			}
			
	}
	public function deleteSelectedProduct(Request $request, $id)
	{
			$product = Product::find($id);
			if($product)
			{
                ProductSize::where('product_id',$id)->delete();
                $product->is_delete = 1;
                $product->save();
				echo json_encode(array("success"=>'1','msg'=>'Selected records has been deleted successfully.'));
			}
			else
			{
				 echo json_encode(array("success"=>'0','msg'=>'There is an issue in deleting records.'));
			}
			
	}

	public function deleteProductSize($id)
    {
        $product_size = ProductSize::find($id);
        $id = $product_size->product_id;
        $product_size->delete();
            return redirect('admin/product/update/'.$id);
    }

    public function productWeights(Request $request,$id)
    {
        $product = Product::find($id);
        if($request->method()=='GET')
        {
            return view('product::product-weight-list',['product'=>$product]);
        }
        else
        {
            dd(3);
        }

    }

    public function productWeightData($id)
    {
        $sizes = ProductSize::where('product_id',$id)->orderBy('id','desc')->get();
        $all_customer = UserInformation::where('user_type',3)->where('user_status',1)->get();
        $all_customer = $all_customer->filter(function($customer){
           return $customer->user->is_delete==0;
        });
        return DataTables::of($sizes)
            ->addColumn('deduct', function($size) use($all_customer) {
                $val= csrf_token();
                $a = '<form action="" method="post">';
                $a.='<table><tr><th>Patient Name</th><th>Product Quantity</th></tr>';
                $a.='<input type="hidden" name="_token" value="'.$val.'">';
                $a.='<input type="hidden" name="size_id" value="'.$size->id.'">';
                $a.='<tr><td><select name="customer" required class="form-control">';
                foreach($all_customer as $customer)
                {
                    $a.='<option value="'.$customer->user_id.'">'.$customer->first_name.' '.$customer->last_name.' ('.$customer->user->email.')</option>';
                }

                $a.='</select></td>';
                $a.='<td><input required class="form-control" type="number" min="0" max="'.$size->quantity.'" name="quantity"></td>';
                $a.='<td><input type="submit" value="Deduct" class="btn btn-danger"></td></tr></table></form>';
                return $a;
//                return '<form action="" method="post"><input type="hidden" name="_token" value="'.$val.'"><input type="hidden" name="size_id" value="'.$size->id.'"><input required class="form-control" type="number" min="0" max="'.$size->quantity.'" name="quantity"><input type="submit" value="Deduct" class="btn btn-danger"></form>';
            })
            ->make(true);
    }

    public function productQuantity(Request $request)
    {
        $size = ProductSize::find($request->size_id);
        $size->quantity -= 1;
        $size->save();
        return $size->quantity;
    }
	
	
}