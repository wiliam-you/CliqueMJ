<?php

namespace App\Http\Controllers\Api;

//use GuzzleHttp\Psr7\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Response;
use \Illuminate\Http\Response as Res;
use \Illuminate\Http\Request;
use Innoflame\Users\Models\User;
use Innoflame\Users\Models\UserRole;
use Innoflame\Activities\Models\Activity;
use Innoflame\Ads\Models\Ad;
use Innoflame\Ads\Models\AdTrans;
use Innoflame\Countries\Models\Country;
use Innoflame\Countries\Models\CountryTrans;
use Innoflame\Countries\Models\Area;
use Innoflame\Countries\Models\AreaTrans;
use Innoflame\Media\Models\Media;
use Innoflame\Stores\Models\Store;
use Innoflame\Stores\Controllers\StoresApiController as StoreApi;
use Innoflame\Stores\Models\StoreTrans;
use Innoflame\Stores\Models\StoreImage;
use Innoflame\Ratings\Models\Rating;
use Innoflame\Ratings\Models\RatingTrans;
use Innoflame\Banners\Models\Banner;
use Innoflame\Pages\Models\Page;
use Innoflame\Pages\Models\PageTrans;
use Auth;
use Lang;
use Innoflame\Settings\Models\Setting;
use Config;
use Mail;
use Innoflame\Emailtemplates\Models\EmailTemplate;
use Innoflame\Emailtemplates\Models\EmailTemplateTrans;

/**
 * Class ApiController
 * @package App\Modules\Api\Lesson\Controllers
 */
class ApiController extends BaseController {

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->storeApi = new StoreApi;
//        $this->beforeFilter('auth', ['on' => 'post']);
    }

    public function setMailSettings() {
        Config::set('mail.driver', Setting::find(5)->value);
        Config::set('mail.host', Setting::find(6)->value);
        Config::set('mail.port', Setting::find(7)->value);
        Config::set('mail.username', Setting::find(8)->value);
        Config::set('mail.password', Setting::find(9)->value);
        Config::set('mail.from.address', Setting::find(10)->value);
        Config::set('mail.from.name', Setting::find(11)->value);
        Config::set('mail.encryption', Setting::find(12)->value);
    }

    /**
     * @var int
     */
    protected $statusCode = Res::HTTP_OK;

    /**
     * @return mixed
     */
    public function getStatusCode() {
        return $this->statusCode;
    }

    /**
     * @param $message
     * @return json response
     */
    public function setStatusCode($statusCode) {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function respondCreated($message, $data = null) {
        return $this->respond([
                    'status' => 'success',
                    'status_code' => Res::HTTP_CREATED,
                    'message' => $message,
                    'data' => $data
        ]);
    }

    /**
     * @param Paginator $paginate
     * @param $data
     * @return mixed
     */
    protected function respondWithPagination(Paginator $paginate, $data, $message) {
        $data = array_merge($data, [
            'paginator' => [
                'total_count' => $paginate->total(),
                'total_pages' => ceil($paginate->total() / $paginate->perPage()),
                'current_page' => $paginate->currentPage(),
                'limit' => $paginate->perPage(),
            ]
        ]);
        return $this->respond([
                    'status' => 'success',
                    'status_code' => Res::HTTP_OK,
                    'message' => $message,
                    'data' => $data
        ]);
    }

    public function respondNotFound($message = 'Not Found!') {
        return $this->respond([
                    'status' => 'error',
                    'status_code' => Res::HTTP_NOT_FOUND,
                    'message' => $message,
        ]);
    }

    public function respondInternalError($message) {
        return $this->respond([
                    'status' => 'error',
                    'status_code' => Res::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => $message,
        ]);
    }

    public function respondValidationError($message, $errors) {
        return $this->respond([
                    'status' => 'error',
                    'status_code' => Res::HTTP_UNPROCESSABLE_ENTITY,
                    'message' => $message,
                    'data' => $errors
        ]);
    }

    public function respond($data, $headers = []) {
        return Response::json($data, $this->getStatusCode(), $headers);
    }

    public function respondWithError($message) {
        return $this->respond([
                    'status' => 'error',
                    'status_code' => Res::HTTP_UNAUTHORIZED,
                    'message' => $message,
        ]);
    }

    /*
     * web-services start here
     */
    public function index(Request $request) {
        if (isset($request->per_page)) {
            $page = $request->per_page;
        } else {
            $page = 8;
        }
        $curr_date = date("Y-m-d H:i:s");
        $start = date("Y-m-d H:i:s", strtotime($curr_date . "-125 day"));
        $end = date("Y-m-d H:i:s", strtotime($curr_date));
        $latestStores = Store::where('active', 1)->whereBetween('created_at', [$start, $end])->orderBy('id', 'desc')->paginate($page);
        //Most visited stores
        $visitedStores = Store::where('active', 1)->where('view_count', '>=', 1)->orderBy('view_count', 'desc')->paginate($page);
        //Best rated stores
        $ratedStores = Store::where('active', 1)->where('rating_count', '>=', 1)->orderBy('rating_count', 'desc')->paginate($page);
        //Ads
        $ads['top_left'] = AdTrans::where('position', 1)->where('start_at', '<=', date("Y-m-d H:i:s"))->where('end_at', '>=', date("Y-m-d H:i:s"))->where('lang', Lang::getlocale())->first();
        $ads['top_right'] = AdTrans::where('position', 2)->where('start_at', '<=', date("Y-m-d H:i:s"))->where('end_at', '>=', date("Y-m-d H:i:s"))->where('lang', Lang::getlocale())->first();
        $ads['bottom_left'] = AdTrans::where('position', 3)->where('start_at', '<=', date("Y-m-d H:i:s"))->where('end_at', '>=', date("Y-m-d H:i:s"))->where('lang', Lang::getlocale())->first();
        $ads['bottom_right'] = AdTrans::where('position', 4)->where('start_at', '<=', date("Y-m-d H:i:s"))->where('end_at', '>=', date("Y-m-d H:i:s"))->where('lang', Lang::getlocale())->first();
        $banners = Banner::where('active', '1')->whereDate('start_date', '<=', date('Y-m-d'))->whereDate('end_date', '>=', date('Y-m-d'))->get();
        return response(array(
            'error' => false,
            'latestStores' => $latestStores->toArray(),
            'visitedStores' => $visitedStores->toArray(),
            'ratedStores' => $ratedStores->toArray(),
            'ads' => $ads,
            'banners' => $banners->toArray(),
                ), 200);
    }

    //function for login
    public function login(Request $request) {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'confirmed' => 1,
            'active' => 1
        ];
//        if (Auth::check(['email' => $request->email, 'password' => $request->password])) {
        if (Auth::attempt($credentials, $request->has('remember'))) {
//            $user = Student::where('email',$request->email)->first();
            $user = Auth::user();
            $user->api_token = str_random(60);
            $user->save();
            return response([
                'status' => Res::HTTP_OK,
                'response_time' => microtime(true) - LARAVEL_START,
                'userData' => $user
                    ], Res::HTTP_OK);
        }

        return response([
            'status' => Res::HTTP_BAD_REQUEST,
            'response_time' => microtime(true) - LARAVEL_START,
            'error' => trans('project.invalid_login_data'),
            'request' => $request->all()
                ], Res::HTTP_BAD_REQUEST);
    }

    private function generateReferenceNumber() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
    }

    /**
     * Auth register
     *
     * @var view
     */
    public function register(Request $request) {
        if (isset($request) && $request->email != '' && $request->password) {
            $activation_code = $this->generateReferenceNumber();
            $user = new User;
            $user->name = $request->name;
            $user->country_id = $request->country;
            $user->area_id = $request->area;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->iso2 = $request->iso2;
            $user->confirmed = false;
            $user->active = false;
            $user->password = bcrypt($request->password);
            if ($request->phone_show) {
                $user->phone_show = true;
            }
            if ($request->email_show) {
                $user->email_show = true;
            }
            $user->confirmation_code = $activation_code;

            $user->save();
            $user_id = $user->id;

            if ($request->avatar) {
                // $request->avatar = $request->avatar->storeAs('avatars', $request->phone.'-'.date('Y-m-d-h:i:sa').'.jpg');
                $request->request->add(['files' => $request->avatar]);
                $media = new MediaAPI;
                $media = $media->uploadFiles($request, $user_id);
                $options['media']['main_image_id'] = $media[0]->id;
                $user->options = $options;
                $user->save();
            }

            // User role
            $userRole = new UserRole;
            $userRole->user_id = $user->id;
            $userRole->role_id = 5; // member
            $userRole->save();

            // Send sms validation
//        $this->sendConfirmationCode($user->phone, $user->confirmation_code);

            \Session::flash('alert-class', 'alert-success');
            \Session::flash('message', trans('project.registration_successfull1') . '"' . $request->email . '"' . trans('project.registration_successfull2'));

            //Assign values to all macros
            $arr_keyword_values = array();
            //Assign values to all macros
            $arr_keyword_values['username'] = $request->name;
            $arr_keyword_values['email'] = $request->email;
            $arr_keyword_values['password'] = $request->password;
            $arr_keyword_values['VERIFICATION_LINK'] = action('WebsiteController@verifyUserEmail', $activation_code);
            $arr_keyword_values['SITE_TITLE'] = Setting::find(1)->value;

            $name = Setting::find(1)->value;
            $email = Setting::find(3)->value;
            $this->setMailSettings();
//                    dd($message);
            //user mail setting
            $emailtemplateUser = EmailTemplateTrans::where(array('template_key' => 'active-user', 'lang' => Lang::getlocale()))->first();
//        $emailtemplateAdmin = EmailTemplateTrans::where(array('template_key'=> 'admin-contacted', 'lang'=>Lang::getlocale()))->first();
            $admin_user = User::where('id', 1)->first();
            try {
                Mail::send('emails.active-user' . '-' . Lang::getlocale(), $arr_keyword_values, function ($message) use ($request, $email, $name, $emailtemplateUser) {
//                    $message->from($email, $name);
                    $message->from($email, $name);
                    $message->to($request->email);
                    $message->subject($emailtemplateUser->subject);
                });
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
            return response([
                'status' => Res::HTTP_OK,
                'response_time' => microtime(true) - LARAVEL_START,
                'userData' => $user,
                'msg' => trans('project.check_your_email'),
                    ], Res::HTTP_OK);
        }
        return response([
            'status' => Res::HTTP_BAD_REQUEST,
            'response_time' => microtime(true) - LARAVEL_START,
            'error' => trans('project.invalid_login_data'),
            'request' => $request->all()
                ], Res::HTTP_BAD_REQUEST);
    }

    /*
     * function to manage dashboard
     */

    public function dashboard(Request $request) {
        if (isset($request) && $request->user_id) {
            $item = User::findOrFail($request->user_id);
            $countries = Country::where('active', 1)->get();
            $areas = Area::where('country_id', $item->country_id)->get();
            $edit = 1;
            if (isset($item->country_id)) {
                $county = Country::findOrFail($item->country_id);
                $item['country_name'] = isset($county) ? $county->trans->name : "";
            } else {
                $item['country_name'] = "";
            }
            if (isset($item->area_id)) {
                $area = Area::find($item->area_id);
                $item['area_name'] = isset($area) ? $area->trans->name : "";
            } else {
                $item['area_name'] = "";
            }
            return response([
                'status' => Res::HTTP_OK,
                'response_time' => microtime(true) - LARAVEL_START,
                'userData' => $item,
                'countries' => $countries,
                'areas' => $areas,
                'msg' => Res::HTTP_OK,
                    ], Res::HTTP_OK);
            return view('website.profile.dashboard', compact('item', 'countries', 'areas'));
        } else {
            return response([
                'status' => Res::HTTP_BAD_REQUEST,
                'response_time' => microtime(true) - LARAVEL_START,
                'error' => trans('project.something_went_wrong'),
                'request' => $request->all()
                    ], Res::HTTP_BAD_REQUEST);
        }
    }

    /**
     * User profile
     *
     * @var view
     */
    public function profile(Request $request) {
        if (isset($request) && $request->user_id) {
            $item = User::findOrFail($request->user_id);
            $countries = Country::where('active', 1)->get();
            $areas = Area::where('country_id', $item->country_id)->get();
            $edit = 1;
            if (isset($item->country_id)) {
                $county = Country::findOrFail($item->country_id);
                $item['country_name'] = isset($county) ? $county->trans->name : "";
            } else {
                $item['country_name'] = "";
            }
            if (isset($item->area_id)) {
                $area = Area::find($item->area_id);
                $item['area_name'] = isset($area) ? $area->trans->name : "";
            } else {
                $item['area_name'] = "";
            }
            return response([
                'status' => Res::HTTP_OK,
                'response_time' => microtime(true) - LARAVEL_START,
                'userData' => $item,
                'countries' => $countries,
                'areas' => $areas,
                'msg' => Res::HTTP_OK,
                    ], Res::HTTP_OK);
            return view('website.profile.dashboard', compact('item', 'countries', 'areas'));
        } else {
            return response([
                'status' => Res::HTTP_BAD_REQUEST,
                'response_time' => microtime(true) - LARAVEL_START,
                'error' => trans('project.something_went_wrong'),
                'request' => $request->all()
                    ], Res::HTTP_BAD_REQUEST);
        }
    }

    //update profile
    public function updateProfile(Request $request) {
        if (isset($request->user_id) && $request->user_id != '') {
            $user = User::findOrFail($request->user_id);
            if (isset($user) && $user->name != "") {
                $user_id = Auth::user()->id;
                if ($request->main_image_id) {
                    $options['media']['main_image_id'] = $request->main_image_id;
                    $user->options = $options;
                }
                $user->name = $request->name;
                $user->phone = $request->phone;
                $user->iso2 = $request->iso2;
                $user->country_id = $request->country;
                $user->area_id = $request->area;
                $user->save();
                return response([
                    'status' => Res::HTTP_OK,
                    'response_time' => microtime(true) - LARAVEL_START,
                    'userData' => $user,
                    'msg' => trans('Core::operations.updated_successfully'),
                        ], Res::HTTP_OK);
            } else {
                return response([
                    'status' => Res::HTTP_BAD_REQUEST,
                    'response_time' => microtime(true) - LARAVEL_START,
                    'error' => trans('project.record_not_found'),
                    'request' => $request->all()
                        ], Res::HTTP_BAD_REQUEST);
            }
        }
        return response([
            'status' => Res::HTTP_BAD_REQUEST,
            'response_time' => microtime(true) - LARAVEL_START,
            'error' => trans('project.something_went_wrong'),
            'request' => $request->all()
                ], Res::HTTP_BAD_REQUEST);
    }

    //change password
    public function updatePassword(Request $request) {
        if (isset($request->user_id) && $request->user_id != '') {
            $user = User::findOrFail($request->user_id);
            if (isset($user) && $user->name != "") {
                $user_id = $request->user_id;
                if ($request->main_image_id) {
                    $options['media']['main_image_id'] = $request->main_image_id;
                    $user->options = $options;
                }
                if ($request->password) {
                    $user->password = bcrypt($request->password);
                }
                $user->save();
                return response([
                    'status' => Res::HTTP_OK,
                    'response_time' => microtime(true) - LARAVEL_START,
                    'userData' => $user,
                    'msg' => trans('Core::operations.updated_successfully'),
                        ], Res::HTTP_OK);
            } else {
                return response([
                    'status' => Res::HTTP_BAD_REQUEST,
                    'response_time' => microtime(true) - LARAVEL_START,
                    'error' => trans('project.record_not_found'),
                    'request' => $request->all()
                        ], Res::HTTP_BAD_REQUEST);
            }
        }
        return response([
            'status' => Res::HTTP_BAD_REQUEST,
            'response_time' => microtime(true) - LARAVEL_START,
            'error' => trans('project.something_went_wrong'),
            'request' => $request->all()
                ], Res::HTTP_BAD_REQUEST);
    }

    /*
     * Add Place
     */

    public function addStore(Request $request) {
        if (isset($request->user_id) && $request->user_id != '') {
            $activities = Activity::where('active', 1)->get();
            $countries = Country::where('active', 1)->get();
            $areas = Area::where('active', 1)->get();
            return response([
                'status' => Res::HTTP_OK,
                'response_time' => microtime(true) - LARAVEL_START,
                'activities' => $activities,
                'countries' => $countries,
                'areas' => $areas,
                'msg' => Res::HTTP_OK,
                    ], Res::HTTP_OK);
        } else {
            return response([
                'status' => Res::HTTP_BAD_REQUEST,
                'response_time' => microtime(true) - LARAVEL_START,
                'error' => trans('project.something_went_wrong'),
                'request' => $request->all()
                    ], Res::HTTP_BAD_REQUEST);
        }
    }

    /*
     * Create Place
     */

    public function saveStore(Request $request) {
        if (isset($request) && $request->user_id != "") {
//            $store = $this->api->createStore($request);
//            $store = $store->getData();
            $store = new Store;
            $author = $request->user_id;
            $store->created_by = $author;
            $store->updated_by = $author;
            $store->activity_id = $request->activity_id;

//        $store->active = false;
//        if ($request->active) {
            $store->active = true;
//        }
            $store->save();
            // Translation
            $languages = ['en', 'ar'];
            foreach ($languages as $lang) {
//            foreach ($request->language as $langCode) {
                $name = 'name_' . $lang;
                $storeTrans = new StoreTrans;
                $storeTrans->store_id = $store->id;
                $storeTrans->name = ucfirst(strtolower($request->$name));
                $storeTrans->country = $request->country;
                $storeTrans->area = $request->area;
                $storeTrans->location = $request->location;
                $storeTrans->store_lat = $request->store_lat;
                $storeTrans->store_lng = $request->store_lng;
                // Media
//            $storeTrans->main_image = $request->main_image_id;
                $options['media']['main_image_id'] = $request->main_image_id;
                $storeTrans->options = $options;

                $storeTrans->is_instagram = false;
                if ($request->is_instagram) {
                    $storeTrans->is_instagram = true;
                }
                $storeTrans->instagram_media = $request->instagram_media;
                $storeTrans->is_additional_media = false;
                if ($request->is_additional_media) {
                    $storeTrans->is_additional_media = true;
                }
                $storeTrans->facebook_media = $request->facebook_media;
                $storeTrans->google_media = $request->google_media;
                $storeTrans->youtube_media = $request->youtube_media;
                $storeTrans->twitter_media = $request->twitter_media;
                $storeTrans->snapchat_media = $request->snapchat_media;
                $storeTrans->googleplus_media = $request->googleplus_media;
                $storeTrans->website_url = $request->website_url;
                $storeTrans->email = $request->email;
                $storeTrans->active = false;
                if ($request->active) {
                    $storeTrans->active = true;
                }
                $storeTrans->is_approved = false;
                if ($request->is_approved) {
                    $storeTrans->is_approved = true;
                }
                $storeTrans->is_featured = false;
                if ($request->is_featured) {
                    $storeTrans->is_featured = true;
                }
                $storeTrans->is_social = false;
                if ($request->is_social) {
                    $storeTrans->is_social = true;
                }
                $storeTrans->provider = false;
                if ($request->provider) {
                    $storeTrans->provider = $request->provider;
                }
                $storeTrans->lang = $langCode;
                $storeTrans->save();
                $storeTrans->order_id = $storeTrans->id;
                $storeTrans->save();
            }
            $options = array();
            if (isset($request->gallery_images)) {
                $gallery_images = $request->gallery_images;
                foreach ($gallery_images as $key => $value) {
                    $gal = new Gallery;
                    $gal->store_id = $store->id;
                    $options["media"]["gallery_images"] = $value;
                    $gal->options = $options;
                    $gal->save();
                }
            }
            return response([
                'status' => Res::HTTP_OK,
                'response_time' => microtime(true) - LARAVEL_START,
                'msg' => trans('Stores::stores.saved_successfully'),
                    ], Res::HTTP_OK);
        } else {
            return response([
                'status' => Res::HTTP_BAD_REQUEST,
                'response_time' => microtime(true) - LARAVEL_START,
                'error' => trans('project.something_went_wrong'),
                'request' => $request->all()
                    ], Res::HTTP_BAD_REQUEST);
        }
    }

    /*
     * Add Place
     */

    public function editStore(Request $request) {
        if (isset($request->user_id) && $request->user_id != '') {
            $id = $request->store_id;
            $item = Store::find($id);
            if (isset($item)) {
                $activities = Activity::where('active', 1)->get();
                $trans = StoreTrans::where('store_id', $id)->get()->keyBy('lang')->toArray();
                $countries = Country::where('active', 1)->get();
                $areas = Area::where(array('country_id' => $item->trans->country, 'active' => 1))->get();
                $edit = 1;
                $gallery_count = \Innoflame\Stores\Models\StoreImage::where('store_id', $id)->get();
                if (isset($gallery_count)) {
                    for ($i = 0; $i < count($gallery_count); $i++) {
                        $media_id = ($item->images{$i}->options['media']['gallery_images']);
                        $temp_image = Media::where('id', $media_id)->first();
                        $gallery_images[$i]['media_id'] = $gallery_count[$i]->id;
                        $gallery_images[$i]['file'] = $temp_image->file;
                    }
                } else {
                    $gallery_images = "";
                }
                return response([
                    'status' => Res::HTTP_OK,
                    'response_time' => microtime(true) - LARAVEL_START,
                    'storeData' => $item,
                    'activities' => $activities,
                    'countries' => $countries,
                    'areas' => $areas,
                    'msg' => Res::HTTP_OK,
                        ], Res::HTTP_OK);
            } else {
                return response([
                    'status' => Res::HTTP_BAD_REQUEST,
                    'response_time' => microtime(true) - LARAVEL_START,
                    'error' => trans('project.record_not_found'),
                    'request' => $request->all()
                        ], Res::HTTP_BAD_REQUEST);
            }
        } else {
            return response([
                'status' => Res::HTTP_BAD_REQUEST,
                'response_time' => microtime(true) - LARAVEL_START,
                'error' => trans('project.something_went_wrong'),
                'request' => $request->all()
                    ], Res::HTTP_BAD_REQUEST);
        }
    }

    /*
     * Create Place
     */

    public function updateStore(Request $request) {
        if (isset($request) && $request->user_id != "") {
//            $store = $this->api->createStore($request);
//            $store = $store->getData();
            $id = $request->store_id;
            $store = Store::find($id);
            if (isset($store)) {
                $author = $request->user_id;
                $store->created_by = $author;
                $store->updated_by = $author;
                $store->activity_id = $request->activity_id;

//        $store->active = false;
//        if ($request->active) {
                $store->active = true;
//        }
                $store->save();
                // Translation
                $languages = ['en', 'ar'];
                foreach ($languages as $lang) {
//            foreach ($request->language as $langCode) {
                    $name = 'name_' . $lang;
                    $storeTrans = StoreTrans::where('store_id', $store->id)->where('lang', $lang)->first();
                    $storeTrans->store_id = $store->id;
                    $storeTrans->name = ucfirst(strtolower($request->$name));
                    $storeTrans->country = $request->country;
                    $storeTrans->area = $request->area;
                    $storeTrans->location = $request->location;
                    $storeTrans->store_lat = $request->store_lat;
                    $storeTrans->store_lng = $request->store_lng;
                    // Media
//            $storeTrans->main_image = $request->main_image_id;
                    $options['media']['main_image_id'] = $request->main_image_id;
                    $storeTrans->options = $options;

                    $storeTrans->is_instagram = false;
                    if ($request->is_instagram) {
                        $storeTrans->is_instagram = true;
                    }
                    $storeTrans->instagram_media = $request->instagram_media;
                    $storeTrans->is_additional_media = false;
                    if ($request->is_additional_media) {
                        $storeTrans->is_additional_media = true;
                    }
                    $storeTrans->facebook_media = $request->facebook_media;
                    $storeTrans->google_media = $request->google_media;
                    $storeTrans->youtube_media = $request->youtube_media;
                    $storeTrans->twitter_media = $request->twitter_media;
                    $storeTrans->snapchat_media = $request->snapchat_media;
                    $storeTrans->googleplus_media = $request->googleplus_media;
                    $storeTrans->website_url = $request->website_url;
                    $storeTrans->email = $request->email;
                    $storeTrans->active = false;
                    if ($request->active) {
                        $storeTrans->active = true;
                    }
                    $storeTrans->is_approved = false;
                    if ($request->is_approved) {
                        $storeTrans->is_approved = true;
                    }
                    $storeTrans->is_featured = false;
                    if ($request->is_featured) {
                        $storeTrans->is_featured = true;
                    }
                    $storeTrans->is_social = false;
                    if ($request->is_social) {
                        $storeTrans->is_social = true;
                    }
                    $storeTrans->provider = false;
                    if ($request->provider) {
                        $storeTrans->provider = $request->provider;
                    }
                    $storeTrans->lang = $langCode;
                    $storeTrans->save();
                    $storeTrans->order_id = $storeTrans->id;
                    $storeTrans->save();
                }
                $options = array();
                if (isset($request->gallery_images)) {
                    $gallery_images = $request->gallery_images;
                    foreach ($gallery_images as $key => $value) {
                        $gal = new Gallery;
                        $gal->store_id = $store->id;
                        $options["media"]["gallery_images"] = $value;
                        $gal->options = $options;
                        $gal->save();
                    }
                }
                return response([
                    'status' => Res::HTTP_OK,
                    'response_time' => microtime(true) - LARAVEL_START,
                    'msg' => trans('Stores::stores.saved_successfully'),
                        ], Res::HTTP_OK);
            } else {
                return response([
                    'status' => Res::HTTP_BAD_REQUEST,
                    'response_time' => microtime(true) - LARAVEL_START,
                    'error' => trans('project.record_not_found'),
                    'request' => $request->all()
                        ], Res::HTTP_BAD_REQUEST);
            }
        } else {
            return response([
                'status' => Res::HTTP_BAD_REQUEST,
                'response_time' => microtime(true) - LARAVEL_START,
                'error' => trans('project.something_went_wrong'),
                'request' => $request->all()
                    ], Res::HTTP_BAD_REQUEST);
        }
    }

    /*
     * function to list all places for a user
     */

    function listPlaces(Request $request) {
        if (isset($request->user_id) && $request->user_id != '') {
            $author = $request->user_id;
            $request->request->add(['paginate' => 20]);
            $request->request->add(['author' => $author]);
            $items = $this->storeApi->listItemsFront($request);
            return response([
                'status' => Res::HTTP_OK,
                'response_time' => microtime(true) - LARAVEL_START,
                'places' => $items,
                'msg' => Res::HTTP_OK,
                    ], Res::HTTP_OK);
        } else {
            return response([
                'status' => Res::HTTP_BAD_REQUEST,
                'response_time' => microtime(true) - LARAVEL_START,
                'error' => trans('project.something_went_wrong'),
                'request' => $request->all()
                    ], Res::HTTP_BAD_REQUEST);
        }
    }

    /*
     * delete place using store id
     */

    public function deletePlaces(Request $request) {
        if (isset($request->user_id) && $request->user_id != '') {
            $ids = $request->store_ids; // in comma separated format
            if (isset($item)) {
                DB::table("stores")->whereIn('id', explode(",", $ids))->delete();
                return response([
                    'status' => Res::HTTP_OK,
                    'response_time' => microtime(true) - LARAVEL_START,
                    'msg' => Res::HTTP_OK,
                        ], Res::HTTP_OK);
            } else {
                return response([
                    'status' => Res::HTTP_BAD_REQUEST,
                    'response_time' => microtime(true) - LARAVEL_START,
                    'error' => trans('project.record_not_found'),
                    'request' => $request->all()
                        ], Res::HTTP_BAD_REQUEST);
            }
        } else {
            return response([
                'status' => Res::HTTP_BAD_REQUEST,
                'response_time' => microtime(true) - LARAVEL_START,
                'error' => trans('project.something_went_wrong'),
                'request' => $request->all()
                    ], Res::HTTP_BAD_REQUEST);
        }
        $item = Store::findOrFail($id);
        return view('Stores::stores.confirm-delete', compact('item'));
    }

    /**
     * Search Result Page
     * */
    public function searchResult(Request $request) {
        //input fields should be like below, if user search
        $request->request->add(['paginate' => 20]);
        $items = $this->storeApi->listItemsFrontSearch($request);
        if (isset($request->name) && $request->name != "") {
            $storeData = Store::where(array('id' => $request->name))->first();
            if (isset($storeData)) {
                if (isset(Auth::user()->id)) {
                    if (Auth::user()->id != $storeData->created_by) {
                        $storeData->search_count = $storeData->search_count + 1;
                        $storeData->save();
                    }
                } else {
                    $storeData->search_count = $storeData->search_count + 1;
                    $storeData->save();
                }
            }
        }

        $activities = Activity::where('active', 1)->get();
        $countries = Country::where('active', 1)->get();
        if (isset($request->country)) {
            $areas = Area::where(array('country_id' => $request->country, 'active' => 1))->get();
        } else {
            $areas = [];
        }
        return response([
            'status' => Res::HTTP_OK,
            'response_time' => microtime(true) - LARAVEL_START,
            'storeData' => $items,
            'activitiesData' => $activities,
            'countriesData' => $countries,
            'areaData' => $areas,
            'msg' => Res::HTTP_OK,
                ], Res::HTTP_OK);
    }

    /**
     * Place Detail Page
     * */
    public function storeDetail(Request $request) {
        if (isset($request->store_id) && $request->store_id != "") {
            $id = $request->store_id; // place id
            $item = Store::find($id);
            if (isset($request->user_id)) {
                if ($request->user_id != $item->created_by) {
//                $item->search_count = $item->search_count+1;
                    $item->view_count = $item->view_count + 1;
                    $item->save();
                }
            } else {
//            $item->search_count = $item->search_count+1;
                $item->view_count = $item->view_count + 1;
                $item->save();
            }
            $is_rated = "";
            if (isset($request->user_id)) {
                $is_rated = Rating::where(array("store_id" => $id, "created_by" => $request->user_id))->first();
            }
            $item = Store::findOrFail($id);
            $gallery_count = \Innoflame\Stores\Models\StoreImage::where('store_id', $id)->get();
            if (isset($gallery_count)) {
                for ($i = 0; $i < count($gallery_count); $i++) {
                    $media_id = ($item->images{$i}->options['media']['gallery_images']);
                    $temp_image = Media::where('id', $media_id)->first();
                    $gallery_images[$i]['media_id'] = $gallery_count[$i]->id;
                    $gallery_images[$i]['file'] = $temp_image->file;
                }
            } else {
                $gallery_images = "";
            }
            return response([
                'status' => Res::HTTP_OK,
                'response_time' => microtime(true) - LARAVEL_START,
                'storeData' => $item,
                'gallery_images' => $gallery_images,
                'is_rated' => $is_rated,
                'msg' => Res::HTTP_OK,
                    ], Res::HTTP_OK);
        } else {
            return response([
                'status' => Res::HTTP_BAD_REQUEST,
                'response_time' => microtime(true) - LARAVEL_START,
                'error' => trans('project.something_went_wrong'),
                'request' => $request->all()
                    ], Res::HTTP_BAD_REQUEST);
        }
    }

    //rate now
    public function rateNow(Request $request) {
        if (isset($request->store_id) && $request->store_id != "") {
            $store = Store::find($request->store_id);
            if (isset($store)) {
                $store->avg_rating = $store->avg_rating + $request->rating_val;
                $store->rating_count = $store->rating_count + 1;
                $store->save();
            }
            $rating = new Rating;
            if ($request->author) {
                $author = $request->author;
            } else {
                $author = $request->user_id;
            }

            $rating->created_by = $author;
            $rating->updated_by = $author;
            $rating->store_id = $request->store_id;

            $rating->active = false;
            if ($request->active) {
                $rating->active = true;
            }
            $rating->save();
            // Translation
            $languages = ['en', 'ar'];
            foreach ($languages as $lang) {
                $ratingTrans = new RatingTrans;
                $ratingTrans->rating_id = $rating->id;
                $ratingTrans->rating = $request->rating_val;
                $ratingTrans->comment = $request->store_comment;
                $ratingTrans->lang = $lang;
                $ratingTrans->save();
            }
            return response([
                'status' => Res::HTTP_OK,
                'response_time' => microtime(true) - LARAVEL_START,
                'msg' => Res::HTTP_OK,
                    ], Res::HTTP_OK);
        } else {
            return response([
                'status' => Res::HTTP_BAD_REQUEST,
                'response_time' => microtime(true) - LARAVEL_START,
                'error' => trans('project.something_went_wrong'),
                'request' => $request->all()
                    ], Res::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Forgot password user validation.
     *
     */
    public function forgotPassword(Request $request) {
        if (isset($request->email) && $request->email != "") {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response([
                    'status' => Res::HTTP_BAD_REQUEST,
                    'response_time' => microtime(true) - LARAVEL_START,
                    'error' => trans('project.user_dosnt_match'),
                    'request' => $request->all()
                        ], Res::HTTP_BAD_REQUEST);
            }
            $user->confirmation_code = str_random(40);
            $user->save();
            // Send email
            $name = Setting::find(1)->value;
            $email = Setting::find(3)->value;
            //Assign values to all macros
            $arr_keyword_values['userName'] = $user->name;
            $arr_keyword_values['siteTitle'] = $name;
            $arr_keyword_values['RESET_LINK'] = action('WebsiteController@resetPassword', [base64_encode($user->id), $user->confirmation_code]);
            $arr_keyword_values['contactDate'] = date("jS F Y h:i:s A");

            $this->setMailSettings();
            //user mail setting
            $emailtemplateUser = EmailTemplateTrans::where(array('template_key' => 'reset-password', 'lang' => Lang::getlocale()))->first();
            $admin_user = User::where('id', 1)->first();
            $section_user = \Innoflame\Contactus\Models\ContactusSection::where('id', $request->section)->first();
            try {
                Mail::send('emails.reset-password' . '-' . Lang::getlocale(), $arr_keyword_values, function ($message) use ($request, $email, $name, $admin_user, $emailtemplateUser) {
                    $message->from($email, $name);
                    $message->to($request->email);
                    $message->subject($emailtemplateUser->subject);
                });
            } catch (\Exception $e) {
//                dd($e->getMessage());
                return response([
                    'status' => Res::HTTP_BAD_REQUEST,
                    'response_time' => microtime(true) - LARAVEL_START,
                    'error' => $e->getMessage(),
                    'request' => $request->all()
                        ], Res::HTTP_BAD_REQUEST);
            }
            return response([
                'status' => Res::HTTP_OK,
                'response_time' => microtime(true) - LARAVEL_START,
                'msg' => trans('project.check_your_email'),
                    ], Res::HTTP_OK);
        } else {
            return response([
                'status' => Res::HTTP_BAD_REQUEST,
                'response_time' => microtime(true) - LARAVEL_START,
                'error' => trans('project.something_went_wrong'),
                'request' => $request->all()
                    ], Res::HTTP_BAD_REQUEST);
        }
    }

    /*
     * show contact page
     */

    public function showContactUsPage(Request $request) {
        $sections = \Innoflame\Contactus\Models\ContactusSection::where(array('active' => "1"))->get();
        return response([
            'status' => Res::HTTP_OK,
            'response_time' => microtime(true) - LARAVEL_START,
            'msg' => Res::HTTP_OK,
                ], Res::HTTP_OK);
    }

    /*
     * create contact
     */

    public function createContact(Request $request) {
        if (isset($request->store_name) && $request->store_name != "") {
// Send email
            //Assign values to all macros
            $arr_keyword_values['userName'] = $request->store_name;
            $arr_keyword_values['message'] = $request->contact_message;
            $arr_keyword_values['contactDate'] = date("jS F Y h:i:s A");

            $name = Setting::find(1)->value;
            $email = Setting::find(3)->value;
            $this->setMailSettings();
//                    dd($message);
            //user mail setting
            $emailtemplateUser = EmailTemplateTrans::where(array('template_key' => 'user-contacted', 'lang' => Lang::getlocale()))->first();
            $emailtemplateAdmin = EmailTemplateTrans::where(array('template_key' => 'admin-contacted', 'lang' => Lang::getlocale()))->first();
            $admin_user = User::where('id', 1)->first();
            $section_user = \Innoflame\Contactus\Models\ContactusSection::where('id', $request->section)->first();
            try {
                Mail::send('emails.user-contacted' . '-' . Lang::getlocale(), $arr_keyword_values, function ($message) use ($request, $admin_user, $emailtemplateUser, $section_user) {
//                    $message->from($email, $name);
                    $message->from($request->contact_email, $request->store_name);
                    $message->to($section_user->trans->email);
                    $message->cc($admin_user->email);
                    $message->subject($emailtemplateUser->subject);
                });
            } catch (\Exception $e) {
                return response([
                    'status' => Res::HTTP_BAD_REQUEST,
                    'response_time' => microtime(true) - LARAVEL_START,
                    'error' => $e->getMessage(),
                    'request' => $request->all()
                        ], Res::HTTP_BAD_REQUEST);
            }
            /* $admin_user = User::where('id',1)->first();
              try {
              Mail::send('emails.user-contacted'.'-'.Lang::getlocale(), $arr_keyword_values, function ($message) use ($admin_user,$request, $name,$email, $emailtemplateAdmin) {
              $message->from($email, $name);
              $message->to($admin_user->email);
              $message->subject($emailtemplateAdmin->subject);
              });
              } catch (\Exception $e) {
              dd($e->getMessage());
              } */
            $contact_data = new \Innoflame\Contactus\Models\Contactus();
            $contact_data->section_id = "";
            if ($request->section) {
                $contact_data->section_id = $request->section;
                $contact_data->active = "1";
            }
            $contact_data->save();

//            $contact_data = \Innoflame\Contactus\Models\Contactus::create();
            $obj = new \Innoflame\Contactus\Models\ContactusTrans();
            $obj->lang = Lang::getlocale();
            $obj->contact_id = $contact_data->id;
            $obj->section_id = $contact_data->section_id;
            $obj->contact_email = $request->contact_email;
            $obj->store_name = $request->store_name;
            $obj->store_url = $request->store_url;
            $obj->other_info = $request->other_info;
            $obj->is_read = "0";
            $obj->is_reply = "0";
            $obj->contact_message = $request->contact_message;
            $obj->contact_phone = $request->contact_phone;
            $obj->reference_no = time();
            $obj->save();
            return response([
                'status' => Res::HTTP_OK,
                'response_time' => microtime(true) - LARAVEL_START,
                'msg' => trans('project.contactus_success'),
                    ], Res::HTTP_OK);
        } else {
            return response([
                'status' => Res::HTTP_BAD_REQUEST,
                'response_time' => microtime(true) - LARAVEL_START,
                'error' => trans('project.something_went_wrong'),
                'request' => $request->all()
                    ], Res::HTTP_BAD_REQUEST);
        }
    }

    public function faqs(Request $request) {
        $item = \Innoflame\Faqs\Models\Faq::all();
        return response([
            'status' => Res::HTTP_OK,
            'response_time' => microtime(true) - LARAVEL_START,
            'faqs' => $item,
            'msg' => Res::HTTP_OK,
                ], Res::HTTP_OK);
    }
    /*
         * function for static pages global
         */
    function cmsPage(Request $request) {
        if (isset($request->page_name) && $request->page_name != "") {

        }
    }
    /*
     * function for static pages
     */
    //terms
    function terms(Request $request) {
        $item = Page::where(array('page_url'=> 'terms', 'published'=>'1'))->first();
        if(isset($item)) {
            $arr_page = [
              'id' => $item->id,
              'page_url' => $item->page_url,
              'title' => $item->trans->title,
              'body' => $item->trans->body
            ];
        return response([
            'status' => Res::HTTP_OK,
            'response_time' => microtime(true) - LARAVEL_START,
            'terms' => $arr_page,
            'msg' => Res::HTTP_OK,
        ], Res::HTTP_OK);
        }else{
            return response([
                'status' => Res::HTTP_BAD_REQUEST,
                'response_time' => microtime(true) - LARAVEL_START,
                'error' => trans('project.something_went_wrong'),
                'request' => $request->all()
            ], Res::HTTP_BAD_REQUEST);
        }
    }
    //about us
    function aboutUs(Request $request) {
        $item = Page::where(array('page_url'=> 'about-us', 'published'=>'1'))->first();
        if(isset($item)) {
            $arr_page = [
                'id' => $item->id,
                'page_url' => $item->page_url,
                'title' => $item->trans->title,
                'body' => $item->trans->body
            ];
            return response([
                'status' => Res::HTTP_OK,
                'response_time' => microtime(true) - LARAVEL_START,
                'terms' => $arr_page,
                'msg' => Res::HTTP_OK,
            ], Res::HTTP_OK);
        }else{
            return response([
                'status' => Res::HTTP_BAD_REQUEST,
                'response_time' => microtime(true) - LARAVEL_START,
                'error' => trans('project.something_went_wrong'),
                'request' => $request->all()
            ], Res::HTTP_BAD_REQUEST);
        }
    }
    function privacyPolicy(Request $request) {
        $item = Page::where(array('page_url'=> 'privacy-policy', 'published'=>'1'))->first();
        if(isset($item)) {
            $arr_page = [
                'id' => $item->id,
                'page_url' => $item->page_url,
                'title' => $item->trans->title,
                'body' => $item->trans->body
            ];
            return response([
                'status' => Res::HTTP_OK,
                'response_time' => microtime(true) - LARAVEL_START,
                'terms' => $arr_page,
                'msg' => Res::HTTP_OK,
            ], Res::HTTP_OK);
        }else{
            return response([
                'status' => Res::HTTP_BAD_REQUEST,
                'response_time' => microtime(true) - LARAVEL_START,
                'error' => trans('project.something_went_wrong'),
                'request' => $request->all()
            ], Res::HTTP_BAD_REQUEST);
        }
    }
}
