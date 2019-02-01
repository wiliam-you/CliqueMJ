<?php
namespace App\PiplModules\zone\Controllers;
use Auth;
use Auth\User;
use App\UserInformation;
use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\PiplModules\zone\Models\Zone;
use App\PiplModules\zone\Models\Cluster;
use App\PiplModules\zone\Models\ClusterDispencery;
use Storage;
use Datatables;
class ZoneController extends Controller
{

    private $placeholder_img;

    public function __construct()
    {

        $this->placeholder_img = asset('media/front/img/avatar-placeholder.svg');
    }

    public function zoneIndex()
    {
        return view("zone::manage-zone");
    }

    public function getZoneData()
    {
        $all_zone = Zone::orderBy('id','desc')->get();

        return DataTables::of($all_zone)
            ->make(true);
    }

    public function createZone(Request $request)
    {
        if ($request->method() == 'GET') {
            return view('zone::create-zone');
        } else {
            $data = $request->all();
            $validate_response = Validator::make($data, [
                'title' => 'required',
            ]);

            if ($validate_response->fails()) {
                return redirect($request->url())->withErrors($validate_response)->withInput();
            } else {
                Zone::create(array("title" => $request->title));

                return redirect('/admin/zone/list')->with('update-user-status', 'Zone created successfully!');

            }
        }
    }

    public function updateZone(Request $request,$id)
    {
        $zone = Zone::find($id);
        if ($request->method() == 'GET') {
            return view('zone::edit-zone',['zone'=>$zone]);
        } else {
            $data = $request->all();
            $validate_response = Validator::make($data, [
                'title' => 'required',
            ]);

            if ($validate_response->fails()) {
                return redirect($request->url())->withErrors($validate_response)->withInput();
            } else {
                $zone->title = $request->title;
                $zone->save();

                return redirect('/admin/zone/list')->with('update-user-status', 'Zone created successfully!');

            }
        }
    }

    public function deleteZone($id)
    {
        $zone = Zone::find($id);
        if($zone)
        {
            $cluster=Cluster::where('zone_id',$zone->id)->get();
            foreach($cluster as $clust)
            {
                $cluster_dispensary = ClusterDispencery::where('cluster_id',$clust->id)->delete();
                $clust->delete();
            }
            $zone->delete();
            return redirect('/admin/zone/list')->with('status','Zone deleted successfully!');
        }
        else
        {
            return redirect('/admin/zone/list');
        }
    }

    public function deleteSelectedZone(Request $request, $id)
    {
        $zone = Zone::find($id);
        if($zone)
        {
            $cluster=Cluster::where('zone_id',$zone->id)->get();
            foreach($cluster as $clust)
            {
                $cluster_dispensary = ClusterDispencery::where('cluster_id',$clust->id)->delete();
                $clust->delete();
            }
            $zone->delete();
            echo json_encode(array("success"=>'1','msg'=>'Selected records has been deleted successfully.'));
        }
        else
        {
            echo json_encode(array("success"=>'0','msg'=>'There is an issue in deleting records.'));
        }

    }

    public function index($id)
    {
        $zone = Zone::find($id);
        return view("zone::manage-cluster",['zone'=>$zone]);
    }

    public function getDispecery($id)
    {
        $cluster = Cluster::find($id);
        return view("zone::list",['id'=>$id,'cluster'=>$cluster]);
    }

    public function getClusterData($id)
    {
        $all_cluster = Cluster::where('zone_id',$id)->orderBy('id','desc')->get();

        return DataTables::of($all_cluster)
            ->make(true);
    }

    public function createCluster(Request $request,$id)
    {
        if ($request->method() == 'GET') {
            return view('zone::create-cluster',['id'=>$id]);
        } else {
            $data = $request->all();
            $validate_response = Validator::make($data, [
                'title' => 'required',
                'limit' => 'required',
            ]);

            if ($validate_response->fails()) {
                return redirect($request->url())->withErrors($validate_response)->withInput();
            } else {
                Cluster::create(array("title" => $request->title, 'limit' => $request->limit,'zone_id'=>$id));

                return redirect('/admin/cluster/list/'.$id)->with('update-user-status', 'Cluster created successfully!');

            }
        }
    }

    public function updateCluster(Request $request,$id)
    {
        $cluster = Cluster::find($id);

        if($request->method()=='GET')
        {
            return view('zone::edit-cluster',['zone'=>$cluster]);
        }
        else
        {
            $data = $request->all();
            $validate_response = Validator::make($data, [
                'title' => 'required',
            ]);

            if ($validate_response->fails()) {
                return redirect($request->url())->withErrors($validate_response)->withInput();
            } else {

                $cluster->title = $request->title;

                $cluster->save();
                return redirect('/admin/cluster/list/'.$cluster->zone_id)->with('update-user-status', 'Cluster updated successfully!');
            }
        }
    }

    public function deleteCluster($id)
    {
        $cluster = Cluster::find($id);
        if($cluster)
        {
            ClusterDispencery::where('cluster_id',$cluster->id)->delete();
            $id=$cluster->zone_id;
            $cluster->delete();
            return redirect('/admin/cluster/list/'.$id)->with('update-user-status','cluster deleted successfully!');
        }
        else
        {
            return redirect('/admin/cluster/list');
        }
    }

    public function deleteSelectedCluster(Request $request, $id)
    {
        $cluster = Cluster::find($id);
        if($cluster)
        {
            ClusterDispencery::where('cluster_id',$cluster->id)->delete();
            $cluster->delete();
            echo json_encode(array("success"=>'1','msg'=>'Selected records has been deleted successfully.'));
        }
        else
        {
            echo json_encode(array("success"=>'0','msg'=>'There is an issue in deleting records.'));
        }

    }

    public function getDispenceryData($id)
    {

        $all_data = ClusterDispencery::where('cluster_id',$id)->get();
        if(count($all_data)>0)
        {
            foreach($all_data as $index=>$dis)
            {
                $dis['sr'] = $index+1;
            }
        }

        return DataTables::of($all_data)
            ->addColumn('name', function($data){
                return $data->dispencery->userInformation->dispensary_name;
            })

            ->make(true);
    }


    public function showUpdateClusterPageForm(Request $request,$id)
    {


        $cluster_list = ClusterDispencery::find($id);

        if ($cluster_list) {


            if ($request->method() == "GET") {
                $dispencery = UserInformation::where('user_type', '2')->where('user_status', '1')->get();
                return view("zone::edit", ['id'=>$cluster_list->cluster_id,"cluster_dispencery" => $cluster_list, 'dispencery' => $dispencery]);
            }
            else
            {
                $data = $request->all();
                $validate_response = Validator::make($data, [
                    'dispencery_id' => 'required',
                ]);

                if ($validate_response->fails()) {
                    return redirect($request->url())->withErrors($validate_response)->withInput();
                } else {

                    $cluster_list->dispencery_id = $request->dispencery_id;

                    $cluster_list->save();

                    return redirect("admin/dispencery/create/" . $cluster_list->cluster_id)->with('status', 'Cluster list updated successfully!');
                }

            }


        } else {
            return redirect("admin/dispencery/create/" . $cluster_list->cluster_id);
        }

    }




    public function addDispencery(Request $request,$id)
    {

        if($request->method() == "GET" )
        {
            $dispencery = UserInformation::where('user_type','2')->where('user_status','1')->get();
            $dispencery = $dispencery->filter(function ($user) {
                return ($user->user->is_delete==0);
            });
            return view("zone::create",['id'=>$id,'dispencery'=>$dispencery]);
        }
        else
        {
            $data = $request->all();
            $validate_response = Validator::make($data, [
                'dispencery_id' => 'required',
            ]);

            if ($validate_response->fails()) {
                return redirect($request->url())->withErrors($validate_response)->withInput();
            } else {

                ClusterDispencery::create(array("cluster_id"=>$id,'dispencery_id'=>$request->dispencery_id));

                $cluster = Cluster::find($id);
                if(count($cluster->clusterDispencery)==$cluster->limit)
                {
                    $cluster->current_week = 1;
                    $cluster->cluster_start_date = Carbon::now()->format('m/d/Y');
                    $cluster->save();
                }

                return redirect("admin/dispencery/create/".$id)->with('status','Cluster list created successfully!');
            }

        }

    }

    public function deleteClusterDispencery(Request $request, $id)
    {
        $cluster_dispencery = ClusterDispencery::find($id);

        if($cluster_dispencery)
        {
            $id = $cluster_dispencery->cluster_id;
            $cluster_dispencery->cluster->current_week = 0;
            $cluster_dispencery->cluster->cluster_start_date = '';
            $cluster_dispencery->cluster->save();
            $cluster_dispencery->delete();
            return redirect("admin/dispencery/create/".$id)->with('status','Dispencery deleted successfully!');
        }
        else
        {
            return redirect("admin/dispencery/create/".$id);
        }

    }
    public function deleteSelectedClusterDispencery(Request $request, $id)
    {
        $cluster_dispencery = ClusterDispencery::find($id);
        if($cluster_dispencery)
        {
            $cluster_dispencery->delete();
            echo json_encode(array("success"=>'1','msg'=>'Selected records has been deleted successfully.'));
        }
        else
        {
            echo json_encode(array("success"=>'0','msg'=>'There is an issue in deleting records.'));
        }

    }


}