<?php
namespace App\PiplModules\contactrequest\Controllers;
use Auth;
use Auth\User;
use App\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Storage;
use App\PiplModules\contactrequest\Models\ContactRequest;
use App\PiplModules\contactrequest\Models\ContactRequestReply;
use App\PiplModules\emailtemplate\Models\EmailTemplate;
use App\PiplModules\admin\Models\GlobalSetting;
use App\PiplModules\contactrequest\Models\ContactRequestCategory;
use Mail;
use Datatables;

class ContactRequestController extends Controller
{


	public function index()
	{

		return view("contactrequest::list");
		
	}
	public function contactRequestData()
	{
		$all_requests = ContactRequest::all();
                $all_requests=$all_requests->sortByDesc('id');
		return Datatables::of($all_requests)
                    
                    ->addColumn('name', function($request){
                        $nameemailphone=$request->contact_name;
                        if(isset($request->contact_email))
                        {
                            $nameemailphone.="/ ".$request->contact_email;
                        }
                        if(isset($request->contact_phone))
                        {
                            $nameemailphone.="/ ".$request->contact_phone;
                        }
                     return $nameemailphone;
                   })
                    ->addColumn('category', function($request){
                        $category_name="";
                        if(isset($request->contact_request_category))
                        {
                             $category_name= $request->category->translate()->name;
                        }else{
                            $category_name="-";
                        }
                        return $category_name;
                   })
                    ->addColumn('is_reply', function($request){
                        if($request->is_reply==0)
                        {
                            return '<span class="alert-danger">Not Replied</span>';
                        }
                      else{
                            return '<span class="alert-success">Replied</span>';
                      }
                   })
                   
                   ->make(true);     
		
	}
	
	public function showContactForm(Request $request)
	{
           
					if($request->method() == "GET" )
					{
							$contact_categories = \App\PiplModules\ContactRequest\Models\ContactRequestCategory::translatedIn(\App::getLocale())->get();
                                                        $arr_user_data = array("name"=>'','email'=>'');
                                                        if(Auth::check())
                                                        {
                                                            $arr_user_data['name']=Auth::user()->userInformation->first_name;
                                                            $arr_user_data['email']=Auth::user()->email;
                                                        }
                                                     
							return view("contactrequest::contact-us-form",array('contact_categories'=>$contact_categories,'user_data'=>$arr_user_data));
					}
					elseif($request->method()=="POST")
					{
                                                        $data = $request->all();
                                                        $validate_response = Validator::make($data,array(
                                                                'name' => 'required',
                                                                'email' => 'required|email',
                                                                'subject' => 'required',
                                                                'message' => 'required',

                                                      ));
							
                                                    if($validate_response->fails())
                                                    {
                                                       return redirect($request->url())->withErrors($validate_response)->withInput();
                                                    }
                                                else
                                                {
                                                        $arr_request_data = array();
                                                        $reference_no = $this->generateReferenceNumber();
                                                        $arr_request_data["contact_name"] = $request->name;
                                                        $arr_request_data["contact_email"] = $request->email;
                                                        $arr_request_data["contact_phone"] = $request->phone;

                                                        if(Auth::check())
                                                        {
                                                                $arr_request_data["contacted_by"] = Auth::user()->id;
                                                        }

                                                        $arr_request_data["contact_subject"] = $request->subject;
                                                        $arr_request_data["contact_message"] = $request->message;
                                                        $arr_request_data["contact_request_category"] = $request->category;
                                                        $arr_request_data["reference_no"] = $reference_no;

                                                        $attachments = array();

                                                        if($request->hasFile('attachment'))
                                                        {

                                                                $uploaded_files = $request->file('attachment');

                                                                foreach($uploaded_files as $uploaded_file)
                                                                {

                                                                 $new_file_name = $uploaded_file->getClientOriginalName();

                                                                Storage::put('public/contact-requests/'.$reference_no."/".$new_file_name,file_get_contents($uploaded_file->getRealPath()));
                                                                $attachments[] = $new_file_name;

                                                                }

                                                                $arr_request_data["contact_attachment"] = $attachments;

                                                        }

                                                        $created_request = ContactRequest::create($arr_request_data);

                                                        $email_template = EmailTemplate::where("template_key",'contact-request')->first();
                                                        $contact_email = GlobalSetting::where('slug','contact-email')->first();

                                                        $arr_keyword_values = array();

                                                        $selected_category_name = "0";

                                                        if(!empty($request->category))
                                                        {
                                                                $category_selected = $created_request->category;

                                                                if($category_selected)
                                                                {
                                                                        $selected_category_name = $category_selected->translate()->name;
                                                                }
                                                        }

                                                        $arr_keyword_values['USER_NAME'] = $request->name;
                                                        $arr_keyword_values['USER_EMAIL'] = $request->email;
                                                        $arr_keyword_values['USER_PHONE'] = $request->phone;
                                                        $arr_keyword_values['CATEGORY'] = $selected_category_name;
                                                        $arr_keyword_values['REQUEST_DATE'] = date("d M, Y H:i A");
                                                        $arr_keyword_values['SUBJECT'] = $request->subject;
                                                        $arr_keyword_values['MESSAGE'] = $request->message;
                                                        $arr_keyword_values['REFERENCE'] = $reference_no;

                                                        Mail::send("emailtemplate::contact-request",$arr_keyword_values, function ($message) use ($email_template,$attachments,$contact_email,$reference_no)  {

                                                                $message->to( $contact_email ->value )->subject($email_template -> subject);

                                                                if(count($attachments)>0)
                                                                {

                                                                        $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."contact-requests".DIRECTORY_SEPARATOR.$reference_no.DIRECTORY_SEPARATOR;

                                                                        foreach($attachments as $attachment)
                                                                        {
                                                                                $pathToFile=$storagePath.$attachment;
                                                                                $message->attach($pathToFile);
                                                                        }
                                                                }

                                                        });

                                                        return redirect($request->url())->with('status','Thanks for contacting us! We will get back to you shortly!');

                                                }
					}
	}
	
	public function deleteContactRequest($req_id)
	{
		$contact_request = ContactRequest::find($req_id);
		
		if($contact_request)
		{
			$directory = 'public/contact-requests/'.$contact_request->reference_no;
			
			$contact_request->delete();
			// delete associated files from storage
			Storage::deleteDirectory($directory);
			
			return redirect("admin/contact-requests")->with('status','Request deleted successfully!');
		}
		else
		{
			return redirect('admin/contact-requests');
		}
	}
	public function deleteSelectedContactRequest($req_id)
	{
		$contact_request = ContactRequest::find($req_id);
		
		if($contact_request)
		{
			 $directory = 'public/contact-requests/'.$contact_request->reference_no;
			
			 $contact_request->delete();
			 // delete associated files from storage
			 Storage::deleteDirectory($directory);
			 echo json_encode(array("success"=>'1','msg'=>'Selected records has been deleted successfully.'));
                         
                }
		else
		{
			 echo json_encode(array("success"=>'0','msg'=>'There is an issue in deleting records.'));
		}
	}
	
	public function viewContactRequest($reference_no)
	{
		$contact_request = ContactRequest::where('reference_no',$reference_no)->get()->first();
		
		if($contact_request )
		{
			$contact_email = GlobalSetting::where('slug','contact-email')->first();
			return view('contactrequest::view',array('request'=>$contact_request,'contact_email'=>$contact_email ));
		}
		else
		{
			return redirect('admin/contact-requests');
		}
	}
	
	public function postReply(Request $request,$reference_no)
	{
			
			$contact_request = ContactRequest::where('reference_no',$reference_no)->get()->first();
			
			if($contact_request)
			{
					
						
						// validate request
                                                $data = $request->all();
                                                $validate_response = Validator::make($data, array(
                                                    'email' => 'required|email',
                                                    'subject' => 'required',
                                                    'message' => 'required',

                                                ));
						
						if($validate_response->fails())
						{
							return redirect('admin/contact-request/'.$reference_no)->withErrors($validate_response)->withInput();
						}
						else
						{
                                                  
								$arr_request_data = array();
								
								$arr_request_data["reply_subject"] = $request->subject;
								$arr_request_data["reply_email"] = $request->email;
								$arr_request_data["from_user_id"] = Auth::user()->id;
								$arr_request_data["reply_message"] = $request->message;
								$arr_request_data["contact_request_id"] = $contact_request->id;
								//updating contact request is reply flag
                                                                $contact_request->is_reply=1;
                                                                $contact_request->save();
                                                                
								$attachments = array();
							
								if($request->hasFile('attachment'))
								{
									$uploaded_files = $request->file('attachment');
									
									foreach($uploaded_files as $uploaded_file)
									{
									
									 $new_file_name = $uploaded_file->getClientOriginalName();
							
									Storage::put('public/contact-requests/'.$reference_no."/".$new_file_name,file_get_contents($uploaded_file->getRealPath()));
									$attachments[] = $new_file_name;
									
									}
									
									$arr_request_data["reply_attachment"] =$attachments;
									
								}
								
								ContactRequestReply::create($arr_request_data);
								
								$arr_keyword_values = array();
								$arr_keyword_values['MESSAGE'] = $request->message;
								
								Mail::send("emailtemplate::contact-request-reply",$arr_keyword_values, function ($message) use ($request,$attachments,$reference_no,$contact_request)  {
									
									$message->to( $contact_request->contact_email )->subject($request -> subject)->from($request->email);
									
									if(count($attachments)>0)
									{
										
										$storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."contact-requests".DIRECTORY_SEPARATOR.$reference_no.DIRECTORY_SEPARATOR;
										
										foreach($attachments as $attachment)
										{
											$pathToFile=$storagePath.$attachment;
											$message->attach($pathToFile);
										}
									}
									
								});
								
								
								return redirect('admin/contact-requests/')->with('status','Reply posted successfully!');
					}
			}
			else
			{
				return redirect('admin/contact-requests');
			}
		
	}
	
	private function generateReferenceNumber()
	{
		return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',mt_rand(0, 0xffff), mt_rand(0, 0xffff),mt_rand(0, 0xffff),mt_rand(0, 0x0fff) | 0x4000,mt_rand(0, 0x3fff) | 0x8000,mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff) );
		
	}
	
	public function listContactCategories()
	{
		
		return view('contactrequest::list-categories');
		
	}
	public function listContactCategoriesData()
	{
		$all_categories = ContactRequestCategory::translatedIn(\App::getLocale())->get();
                $all_categories=$all_categories->sortBy('id');
		return Datatables::of($all_categories)
               
                ->make(true);
		
	}
	
	public function createContactCategories(Request $request)
	{
			if($request->method()=="GET")
			{
				return view("contactrequest::create-category");	
			}
			else
			{
							$data = $request->all();
							$validate_response = Validator::make($data, array(
                                                            'name' => 'required|unique:contact_request_category_translations',

							));
						
						if($validate_response->fails())
						{
									return redirect($request->url())->withErrors($validate_response)->withInput();
						}
						else
						{
							$created_category = ContactRequestCategory::create(array('created_by'=>Auth::user()->id));
							
							$translated_category = $created_category->translateOrNew(\App::getLocale());
							$translated_category->name = $request->name;
							$translated_category->locale =\App:: getLocale();
							$translated_category->contact_request_category_id =$created_category->id;
							$translated_category->save();
							
							return redirect("admin/contact-request-categories")->with('status','Category created successfully!');
						}
			}
	}
	
	public function updateContactCategory(Request $request,$category_id,$locale="")
	{
			$category = ContactRequestCategory::find($category_id);
			
			if($category)
			{
					$translated_category = $category->translateOrNew($locale);
                                        //  dd($translated_category);
					if($request->method()=="GET")
					{
						return view("contactrequest::update-category",array('category'=>$translated_category));	
					}
					else
					{                               $flag=1;
									$data = $request->all();
                                                                         if (strtoupper($data['name']) != strtoupper($translated_category->name))  {
                                                                             $validate_response = Validator::make($data, array(
                                                                           'name' => 'required|unique:contact_request_category_translations',
																						
                                                                         ));
                                                                             if($validate_response->fails())
                                                                            {
                                                                                return redirect($request->url())->withErrors($validate_response)->withInput();
                                                                            }
                                                                            else
                                                                            {
                                                                                $translated_category->name = $request->name;
									
                                                                                if($locale!='')
                                                                                {
                                                                                        $translated_category->contact_request_category_id =$category->id;
                                                                                        $translated_category->locale =$locale;
                                                                                }

                                                                                $translated_category->save();

                                                                                return redirect("admin/contact-request-categories")->with('status','Category updated successfully!');
                                                                            }
                                                                         }
                                                                        
								else
								{
									
									$translated_category->name = $request->name;
									
									if($locale!='')
									{
										$translated_category->contact_request_category_id =$category->id;
										$translated_category->locale =$locale;
									}
									
									$translated_category->save();
									
									return redirect("admin/contact-request-categories")->with('status','Category updated successfully!');
								}
                                        }}
			
			else
			{
				return redirect('admin/contact-request-categories');
			}
	}
	
	public function deleteContactCategory($category_id)
	{
		$category = ContactRequestCategory::find($category_id);
		
		if($category)
		{
			$category->delete();
			return redirect("admin/contact-request-categories")->with('status','Category deleted successfully!');
		}
		else
		{
			return redirect('admin/contact-request-categories');
		}
	}
	public function deleteSelectedContactCategory($category_id)
	{
		$category = ContactRequestCategory::find($category_id);
		
		if($category)
		{
			$category->delete();
			echo json_encode(array("success"=>'1','msg'=>'Selected records has been deleted successfully.'));
		}
		else
		{
			 echo json_encode(array("success"=>'0','msg'=>'There is an issue in deleting records.'));
		}
	}
	
}