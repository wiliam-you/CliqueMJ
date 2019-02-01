<?php
namespace App\PiplModules\screenshotsection\Controllers;
use Auth;
use Auth\User;
use App\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\PiplModules\screenshotsection\Models\FrontScreenShot;
use Storage;
use Datatables;
class ScreenShotController extends Controller
{

    public function addScreenShot(Request $request)
    {
        if($request->method()=='GET')
        {
            $images = FrontScreenShot::all();
            return view('screenshotsection::list',['images'=>$images]);
        }
        else
        {
            $data = $request->all();
            $validate_response = Validator::make($data, [
                'uploadFile' => 'required',
            ]);

            if($validate_response->fails())
            {
                return redirect($request->url())->withErrors($validate_response)->withInput();
            }
            else
            {

                foreach ($request->file('uploadFile') as $key => $value) {

                    $screen_shot = new FrontScreenShot();

                    $extension = $value->getClientOriginalExtension();

                    $new_file_name = str_replace(' ','',microtime()).".".$extension;

                    Storage::put('public/screen-shot/'.$new_file_name,file_get_contents($value->getRealPath()));

                    $screen_shot->image = $new_file_name;
                    $screen_shot->save();

                }


                return redirect('admin/screen-shot-section/list')->with('success','Screen Shots Uploaded Successfully');
            }



        }
    }

    public function removeScreenShot($id)
    {
        $screen_shot = FrontScreenShot::find($id);
        $screen_shot->delete();
        return redirect('admin/screen-shot-section/list')->with('success','Screen Shots remove Successfully');
    }
	
}