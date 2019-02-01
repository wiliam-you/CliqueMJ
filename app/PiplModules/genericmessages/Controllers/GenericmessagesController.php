<?php
namespace App\PiplModules\genericmessages\Controllers;
use App\PiplModules\admin\Models\GlobalSetting;
use Auth;
use App\User;
use App\UserInformation;
use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\PiplModules\genericmessages\Models\GenericMessage;
use Storage;
use Datatables;
use PDF;
use Image;
use Zipper;

class GenericmessagesController extends Controller
{
    private $placeholder_img;

    public function __construct()
    {

        $this->placeholder_img = asset('media/front/img/avatar-placeholder.svg');
    }

    public function listGenericMsg()
    {
        $generic_messages = GenericMessage::orderBy('id','desc')->get();
        return view("genericmessages::list", compact('generic_messages'));
    }

    public function getGenericData()
    {
        $generic_messages = GenericMessage::orderBy('id','desc')->get();

        return DataTables::of($generic_messages)
            ->addColumn('message', function($messages){
                return $messages->message;
            })
            ->addColumn('created_at', function($messages){
                return $messages->created_at;
            })
            ->make(true);
    }

    public function createGenericMsg(Request $request)
    {
    	if($request->method() == "GET" )
        {
            return view("genericmessages::create");
        }
        else
        {
            // validate request
            $data = $request->all();
            $validate_response = Validator::make($data, [
                'message' => 'required'
            ]);

            if($validate_response->fails())
            {
                return redirect($request->url())->withErrors($validate_response)->withInput();
            }
            else
            {
                $patients = UserInformation::where('user_status',1)->where('user_type','3')->where('is_loggedin','1')->where('token','<>','')->get();
                $message = new GenericMessage;
                $message->message = $request->message;
                $message->view_count = '0';
                $message->save();

                if(count($patients) > 0 && $message->id !="") {
                    foreach ($patients as $k=> $item) {
                        if($item->user_id == 0){
                            continue;
                        }
//                        $msg = $patient_offer->advertisement->is_mj_offer == 1 ? 'You have received an MJ Offer' : 'You have received a Clique Offer';
                        $msg = $request->message;

                        if($item->flag == 1)
                        {
                            $this->iosNotificaton($item->token,$msg, array('flag' => '0', 'global_id' => $message->id));
                            echo $k;
                        }
                        else
                        {
                        	$this->androidNotification($item->token,$msg, array('flag' => '0', 'global_id' => $message->id));
                            echo $k;
                        }
                    }
                }
                return redirect("admin/genericmessages/list")->with('success','Generic message sent successfully!');
            }
        }
    }

    function iosNotificaton($token, $message, $flag) {
    	$url = "https://fcm.googleapis.com/fcm/send";
        $token = $token;
//            $serverKey = 'AIzaSyDYc5bbCe_Gsp97eBIcufaiJt2VhcvAjF4';
        $serverKey = 'AIzaSyAxwB0y744VgFewEOsjhydqYo7IkKQbbSA';
        $title = "Clique";
        $body = $message;
        $notification = array_merge(array('text' => $body, 'sound' => 'default'), $flag);
//        $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
        $arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
        $json = json_encode($arrayToSend);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key='. $serverKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//Send the request
        $response = curl_exec($ch);
//Close request
        if ($response === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }

        curl_close($ch);
        return $response;
    }

    public function androidNotification($token, $message, $flag) {
    	//using fcm
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'registration_ids' => array($token),
            'data' => array_merge(array("message" => $message),$flag)
        );
        $fields = json_encode($fields);
        $headers = array(
//            'Authorization: key=AIzaSyA3DmV3OYuMsl403lGV_N5J-RskPZFrvsg',
//            'Authorization: key=AIzaSyC-53914DxXVX9yaalHJSw7XHmmfVWxid8',
//            'Authorization: key=AIzaSyCD7vxafCjBt53dip86R0RA0dTMMdI8bRE',
            'Authorization: key=AIzaSyCH-DvKVeUVjnRRI6JxfjuT1QaJTUSOyZg',
            'Content-Type: application/json'
        );
        $ch = curl_init ();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        $result = curl_exec($ch);
        // Close connection
        curl_close($ch);
//        echo '<pre>'; print_r($result); die;
        return $result;
    }

}
