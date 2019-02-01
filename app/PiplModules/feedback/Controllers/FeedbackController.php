<?php
namespace App\PiplModules\feedback\Controllers;
use Auth;
use App\User;
use App\UserInformation;
use App\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\PiplModules\feedback\Models\Feedback;
use Storage;
use Datatables;
class FeedbackController extends Controller
{

	private $placeholder_img;

	public function __construct()
	{

		$this->placeholder_img = asset('media/front/img/avatar-placeholder.svg');
	}

	public function index()
	{
		return view("feedback::manage-feedback");
	}

    public function listDispencary() {
        $all_users = UserInformation::all();
        $all_users=$all_users->sortByDesc('id');

        $registered_users = $all_users->filter(function ($user) {
            return ($user->user_type == 2 && $user->user->is_delete==0);
        });

        foreach($registered_users as $user)
        {
            $user->feedback_count = $user->user->getCountOfDispensaryNewFeedback->count();
            $user->name = $user->dispensary_name;
        }

        return Datatables::of($registered_users)
            ->make(true);
    }

	public function getFeedback($id)
	{
		$user = userInformation::where('user_id',$id)->first();

        Feedback::where('dispencery_id',$id)->where('is_read',0)->update(['is_read'=>1]);

		return view("feedback::list",['user'=>$user]);
	}
        
        public function getFeedbackData($id)
	{

		$all_feedback = Feedback::where('dispencery_id',$id)->get();

                $all_feedback=$all_feedback->sortBy('id');

                foreach($all_feedback as $feedback)
                {
                    $feedback->name = $feedback->user?$feedback->user->userInformation->first_name.' '.$feedback->user->userInformation->last_name:'-';
                }

                return DataTables::of($all_feedback)
                        ->make(true);
	}
        
	
	public function showUpdateFeedbackPageForm(Request $request,$id)
	{
	

		$feedback = Feedback::find($id);
		
		if($feedback)
		{
					
			
			if($request->method() == "GET" )
			{
				return view("feedback::edit",["feedback"=>$feedback]);
			}
			else
			{
				
				// validate request
					$data = $request->all();
					$validate_response = Validator::make($data, [
						'feedback' => 'required',
                        'rating' => 'required|numeric|min:0|max:5'
																	
				]);
				
				if($validate_response->fails())
				{
							return redirect($request->url())->withErrors($validate_response)->withInput();
				}
				else
				{
					
					$feedback->feedback = $request->feedback;
                    $feedback->rating = $request->rating;
					$feedback->save();

					$this->setRating($feedback->dispencery_id);
					
					return redirect("admin/feedback/all/".$feedback->dispencery_id)->with('status','Feedback updated successfully!');
				}
				
			}
		}
		else
		{
			return redirect("admin/feedback/all/".$feedback->dispencery_id);
		}
		
	}
	
	
	
	public function createFeedback(Request $request,$id)
	{
	
			if($request->method() == "GET" )
			{
				return view("feedback::create",['id'=>$id]);
			}
			else
			{
				
				// validate request
					$data = $request->all();
					$validate_response = Validator::make($data, [
						'feedback' => 'required',
					]);
				
				if($validate_response->fails())
				{
                                    return redirect($request->url())->withErrors($validate_response)->withInput();
				}
				else
				{
					$created_feedback = Feedback::create(array("feedback"=>$request->feedback,'dispencery_id'=>$id));
                    $this->setRating($id);
					return redirect("admin/feedback/all/".$id)->with('status','Feedback created successfully!');
					
				}
				
			}
		
	}
	
	public function deleteFeedback(Request $request, $id)
	{
			$feedback = Feedback::find($id);
			
			if($feedback)
			{
				$id = $feedback->dispencery_id;
                $feedback->delete();
                $this->setRating($id);

                return redirect("admin/feedback/all/".$id)->with('status','Feedback deleted successfully!');
			}
			else
			{
				return redirect("admin/feedback/all/".$id);
			}
			
	}
	public function deleteSelectedFeedback(Request $request, $id)
	{
			$feedback = Feedback::find($id);
			if($feedback)
			{
                $id = $feedback->dispencery_id;
                $feedback->delete();
                $this->setRating($id);
                echo json_encode(array("success"=>'1','msg'=>'Selected records has been deleted successfully.'));
			}
			else
			{
				 echo json_encode(array("success"=>'0','msg'=>'There is an issue in deleting records.'));
			}
			
	}

	public function setRating($id)
    {
        $user = User::find($id);
        $rate = 0;
        $total_count = count($user->feedbackDispencery);
        if($total_count>0)
        {
            foreach($user->feedbackDispencery as $rating)
            {
                $rate += $rating->rating/5;
            }
            $user->userInformation->rating = ($rate/$total_count)*5;
        }
        else
        {
            $user->userInformation->rating = 0;
        }

        $user->userInformation->save();
    }
	
	
}