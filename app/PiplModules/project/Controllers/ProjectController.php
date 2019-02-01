<?php

namespace App\PiplModules\project\Controllers;

use Auth;
use Auth\User;
use App\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Storage;
use Datatables;
use App\PiplModules\project\Models\Project;
use App\PiplModules\project\Models\ProjectCategory;
use App\PiplModules\project\Models\ProjectCategorySkill;
use Illuminate\Support\Facades\URL;
use App\PiplModules\admin\Helpers\FileUpload;
use Mail;
use Image;
use App\PiplModules\project\Models\ProjectDocument;
use App\PiplModules\project\Models\ProjectSkill;
use App\PiplModules\admin\Models\Country;
use App\PiplModules\admin\Models\State;
use App\PiplModules\admin\Models\City;

class ProjectController extends Controller {

  private $location_type = 'group'; //values are 'single','group','googleMap'
  private $escrow_applicable = false; //values are true,false
  private $is_crowd_fund = false; //values are true,false

  public function index() {

    return view("project::list");
  }

  public function getProjectData() {

    $all_projects = Project::all();
    return Datatables::of($all_projects)
        ->addColumn("category", function($project) {
          if ($project->category) {
            return $project->category->name;
          } else {
            return "-";
          }
        })
        ->addColumn('Language', function($post) {
          $language = '<button class="btn btn-sm btn-warning dropdown-toggle" type="button" id="langDropDown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Another Language <span class="caret"></span> </button>
            <ul class="dropdown-menu multilanguage" aria-labelledby="langDropDown">';
          if (count(config("translatable.locales_to_display"))) {
            foreach (config("translatable.locales_to_display") as $locale => $locale_full_name) {
              if ($locale != 'en') {
                $language.='<li class="dropdown-item"> <a href="projects/update/' . $post->id . '/' . $locale . '">' . $locale_full_name . '</a></li>';
              }
            }
          }
          return $language;
        })
        ->make(true);
  }

  public function deleteProject($project_id) {
    
    $project = Project::find($project_id);
    if ($project) {
      $project->delete();
      return redirect("admin/projects")->with('status', 'Project deleted successfully!');
    }
  }

  public function deleteSelectedProject($project_id) {
     $project = Project::find($project_id);

    if ($project) {
      $project->delete();
      echo json_encode(array("success" => '1', 'msg' => 'Selected records has been deleted successfully.'));
    } else {
      echo json_encode(array("success" => '0', 'msg' => 'There is an issue in deleting records.'));
    }
  }

  public function getAllTags() {
    
  }

 public function updateProject(Request $request, $project_id, $locale = "") {
        $project_data = Project::find($project_id);
        if ($project_data) {
            $translated_project = $project_data->translateOrNew($locale);
            if ($request->method() == "GET") {
             $existing_categories = ProjectCategory::withTranslation()->get();
              $tree = $this->getCategoryTree($existing_categories->toTree(), '&nbsp;&nbsp;');
              $category_skills = ProjectCategorySkill::withTranslation()->get();
             $countries = Country::translatedIn(\App::getLocale())->get();
            $all_states = State::translatedIn(\App::getLocale())->get();
            
                if (isset($locale) && $locale != 'en' && $locale != '') {
                    return view('project::update-language', array('project_data' => $translated_project)
                    );
                } else {
                  $pskills = ProjectSkill::where('project_id',$project_id)
                    ->where('project_category_id',$project_data->project_category_id)
                    ->select('project_category_skill_id')->get();
                  
                  $project_skills=null;
                  if(count($pskills)>0)
                  {
                        foreach($pskills as $obj)
                        {
                        $project_skills[]=$obj->project_category_skill_id;
                        }
                  }
                  
                    return view("project::update", array('project_data'=>$project_data,'tree' => $tree, 'skills' => $category_skills, 'currencies' => $this->getCurrencies(), 'location_type' => $this->location_type,'project_skills'=>$project_skills,'states' => $all_states, "countries" => $countries));
                }
            } else {
      
      //validating requested data        
               if ($locale != 'en' && $locale != '') {
                 $validate_response = Validator::make($request->all(), array(
                'title' => 'required',
                'short_description' => 'required',
                'description' => 'required'
              )
              );
        }else{
          
           if($this->location_type=='group')
      {
      $validate_response = Validator::make($request->all(), array(
            'title' => 'required',
            'short_description' => 'required',
            'description' => 'required',
            'slug' => 'required|unique:projects,slug,' . str_slug($project_id),
            'category' => 'required',
            'budget_min' => 'required',
            'budget_max' => 'required',
            'budget_type' => 'required',
            'start' => 'required',
            'currency' => 'required',
            'status' => 'required',
            'project_location' => 'required',
           'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'zipcode' => 'required'
      
          ), array('slug.required' => 'URL field is required.',
            'slug.unique' => 'URL field already exist in database.'
          )
      );
      }elseif($this->location_type=='single' || $this->location_type=='googleMap')
      {
              $validate_response = Validator::make($request->all(), array(
            'title' => 'required',
            'short_description' => 'required',
            'description' => 'required',
            'slug' => 'required|unique:projects,slug,' . str_slug($project_id),
//            'category' => 'required',
            'budget_min' => 'required',
            'budget_max' => 'required',
            'budget_type' => 'required',
            'start' => 'required',
            'currency' => 'required',
            'status' => 'required',
            'project_location' => 'required',
          ), array('slug.required' => 'URL field is required.',
            'slug.unique' => 'URL field already exist in database.'
         
          )
      );
      }
      
               }

  

      if ($validate_response->fails()) {
        return redirect(URL::previous())
            ->withErrors($validate_response)
            ->withInput();
      } else {
        
           if ($locale != 'en' && $locale != '') {
           $translated_project->title = $request->title;
        $translated_project->description = $request->description;
        $translated_project->short_description= $request->short_description;
        $translated_project->locale = $locale;
        $translated_project->project_id = $project_id;
        $translated_project->save();
           }else{
             
        $data['title'] = $request->title;
        $data['short_description'] = $request->short_description;
        $data['description'] = $request->description;
        $data['created_by'] = 1;
//        $data['project_category_id'] = $request->category;
        $data['start_date'] = $request->start;
        $data['end_date'] = $request->end ? $request->end : null;
        $data['status'] = $request->status;
        $data['is_featured'] = $request->is_featured;
        $data['currency'] = $request->currency;
        $data['tags'] = $request->tags;
        $data['parent_project'] = $request->parent_project ? $request->parent_project : null;
        $data['budget_min'] = $request->budget_min;
        $data['budget_max'] = $request->budget_max ? $request->budget_max : null;
        $data['budget_type'] = $request->budget_type;
        $data['slug'] = $request->slug;
        $data['code'] = $request->code ? $request->code : time();
        $data['project_location'] = $request->project_location ? $request->project_location : null;
         $data['country_id'] = $request->country ? $request->country : null;
        $data['state_id'] = $request->state ? $request->state : null;
        $data['city_id'] = $request->city ? $request->city : null;
        $data['zipcode'] = $request->zipcode ? $request->zipcode : null;
        Project::where('id',$project_id)->update($data);

          
        //inserting project catgory skills
        
        ProjectSkill::where('project_id',$project_id)->delete();
        if($request->has('required_category_skills') && count($request->required_category_skills)>0)
        {
          foreach($request->required_category_skills as $skill){
            $obj= new ProjectSkill();
            $obj->project_id = $project_id;
            $obj->project_category_id=$request->category;
            $obj->project_category_skill_id= $skill;
            $obj->save();
          }
        }
      }
        return redirect(url('admin/projects'))->with('status', 'Project updated successfully!');
      }
                
            }
        } else {
            return redirect('admin/projects');
        }
    }

  public function createProject(Request $request) {

    if ($request->method() == "GET") {

      $existing_categories = ProjectCategory::withTranslation()->get();

            $countries = Country::translatedIn(\App::getLocale())->get();
            $all_states = State::translatedIn(\App::getLocale())->get();
      
      
//      $tree = $this->getCategoryTree($existing_categories->toTree(), '&nbsp;&nbsp;');
      $tree = $existing_categories;
      
      $category_skills = ProjectCategorySkill::withTranslation()->get();
      return view("project::create", array('tree' => $tree, 'skills' => $category_skills, 'currencies' => $this->getCurrencies(), 'location_type' => $this->location_type,'states' => $all_states, "countries" => $countries));
    } else {
      //validating requested data
      
 
      if($this->location_type=='group')
      {
      $validate_response = Validator::make($request->all(), array(
            'title' => 'required',
            'short_description' => 'required',
            'description' => 'required',
            'slug' => 'required|unique:projects',
//            'category' => 'required',
            'budget_min' => 'required|numeric|min:1',
            'budget_max' => 'numeric|min:1',
            'budget_type' => 'required',
            'start' => 'required',
            'currency' => 'required',
            'status' => 'required',
            'project_location' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'zipcode' => 'required',
            'images.*'=>'mimes:jpeg,png,jpg'
          ), array('slug.required' => 'URL field is required.',
            'slug.unique' => 'URL field already exist in database.',
            'images.*.mimes' => 'Please select valid image file.',
              'project_location.required'=>'Please enter location.'
          )
      );
      }elseif($this->location_type=='single' || $this->location_type=='googleMap'){
         $validate_response = Validator::make($request->all(), array(
            'title' => 'required',
            'short_description' => 'required',
            'description' => 'required',
            'slug' => 'required|unique:projects',
//            'category' => 'required',
            'budget_min' => 'required|numeric|min:1',
            'budget_max' => 'required|numeric|min:1',
            'budget_type' => 'required',
            'start' => 'required',
            'currency' => 'required',
            'status' => 'required',
            'project_location' => 'required',
               'images.*'=>'mimes:jpeg,png,jpg'
          ), array('slug.required' => 'URL field is required.',
            'slug.unique' => 'URL field already exist in database.',
              'project_location.required'=>'Please enter location.'
          )
      );
      }

      if ($validate_response->fails()) {
        return redirect(URL::previous())
            ->withErrors($validate_response)
            ->withInput();
      } else {
        
        // uploading images  
        if($request->hasFile('images'))
      {
        $image_file_data = FileUpload::upload(
            $request, array("images"), array("image"), array("project_images/"), array(
              array(['resize' => true, 'width' => 120, 'height' => 120, 'destination' => 'project_images/thumb/']
              )
            ), array(
              array("scustom_messages" => array("image" => "Not valid image file", "required" => "Please select image file.")),
            )
        );
        
      }

        // uploading documents
        if($request->hasFile('documents'))
      {
        $document_file_data = FileUpload::upload(
            $request, array("documents"), array("*"), array("project_documents/"), null
            , array(
              array("scustom_messages" => array("documents" => "Not valid document file", "required" => "Please select document file.")),
            )
        );
      }

        $data['title'] = $request->title;
        $data['short_description'] = $request->short_description;
        $data['description'] = $request->description;
        $data['created_by'] = 1;
        $data['project_category_id'] = $request->category ? $request->category:$request->category!=0?$request->category:null;
        $data['start_date'] = $request->start;
        $data['end_date'] = $request->end ? $request->end : null;
        $data['status'] = $request->status;
        $data['is_featured'] = $request->is_featured;
        $data['currency'] = $request->currency;
        $data['tags'] = $request->tags ? $request->tags : null;
        $data['parent_project'] = $request->parent_project ? $request->parent_project : null;
        $data['budget_min'] = $request->budget_min;
        $data['budget_max'] = $request->budget_max ? $request->budget_max : null;
        $data['budget_type'] = $request->budget_type;
        $data['slug'] = $request->slug;
        $data['code'] = $request->code ? $request->code : time();
        $data['project_location'] = $request->project_location ? $request->project_location : null;
        
        $data['country_id'] = $request->country ? $request->country : null;
        $data['state_id'] = $request->state ? $request->state : null;
        $data['city_id'] = $request->city ? $request->city : null;
        $data['zipcode'] = $request->zipcode ? $request->zipcode : null;
        
        $created_project = Project::create($data);
        

        
        $translated_project = $created_project->translateOrNew(\App::getLocale());
        
        $translated_project->title = $request->title;
        $translated_project->description = $request->description;
        $translated_project->short_description= $request->short_description;
        $translated_project->locale = \App:: getLocale();
        $translated_project->project_id = $created_project->id;
        $translated_project->save();
        
        // inserting uploded data of documents
        
           if($request->hasFile('documents'))
      {
        for ($i = 0; $i < count($document_file_data['new_file_name']); $i++) {
          $obj = new ProjectDocument();
          $obj->path = $document_file_data['new_file_name'][$i];
          $obj->name = $document_file_data['original_file_name'][$i];
          $obj->document_type = 'document';
          $obj->description = '';
          $obj->project_id = $created_project->id;
          $obj->save();
        }
      }

        // inserting uploded data of images
      
           if($request->hasFile('images'))
      {
        for ($i = 0; $i < count($image_file_data['new_file_name']); $i++) {

          $obj = new ProjectDocument();
          $obj->path = $image_file_data['new_file_name'][$i];
          $obj->name = $image_file_data['original_file_name'][$i];
          $obj->document_type = 'image';
          $obj->project_id = $created_project->id;
          $obj->description = '';
          $obj->save();
        }
      } 
        //inserting project catgory skills
        if($request->has('required_category_skills') && count($request->required_category_skills)>0)
        {
          foreach($request->required_category_skills as $skill){
            $obj= new ProjectSkill();
            $obj->project_id = $created_project->id;
            $obj->project_category_id=$request->category;
            $obj->project_category_skill_id= $skill;
            $obj->save();
          }
        }
        return redirect(url('admin/projects'))->with('status', 'Project created successfully!');
      }
    }
  }

  public function removeProjectDocument($project_id, $document_id) {
    
  }

  private function removeBlogFileFromStrorage($file_name) {
    
  }

  public function listProjectCategories() {
    return view('project::list-categories');
  }

  public function listProjectCategoriesData() {

    $all_categories = ProjectCategory::translatedIn(\App::getLocale())->get();

    return Datatables::of($all_categories)
        ->addColumn("parent", function($category) {
          if ($category->parentCat) {
            return $category->parentCat->translate()->name;
          } else {
            return "-";
          }
        })
        ->addColumn('Language', function($category) {
          $language = '<button class="btn btn-sm btn-warning dropdown-toggle" type="button" id="langDropDown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Another Language <span class="caret"></span> </button>
        <ul class="dropdown-menu multilanguage" aria-labelledby="langDropDown">';
          if (count(config("translatable.locales_to_display"))) {
            foreach (config("translatable.locales_to_display") as $locale => $locale_full_name) {
              if ($locale != 'en') {
                $language.='<li class="dropdown-item"> <a href="project-category/' . $category->id . '/' . $locale . '">' . $locale_full_name . '</a></li>';
              }
            }
          }
          return $language;
        })
        ->make(true);
  }

  public function createProjectCategories(Request $request) {

    if ($request->method() == "GET") {
      $existing_categories = ProjectCategory::withTranslation()->get();
      $tree = $this->getCategoryTree($existing_categories->toTree(), '&nbsp;&nbsp;');
      return view("project::create-category", array('categories' => $existing_categories, "tree" => $tree));
    } else {
      $data = $request->all();
      $data['name'] = (trim($data['name']));
      $data['slug'] = (trim($data['slug']));
      $validate_response = Validator::make($data, array(
            'name' => 'required|unique:project_category_translations',
            'slug' => 'required|unique:project_categories',
          ), array('name.required' => 'Please enter name',
            'slug.required' => 'Please enter url',
            'slug.unique' => 'This url is already exists',
      ));

      if ($validate_response->fails()) {
        return redirect($request->url())->withErrors($validate_response)->withInput();
      } else {
        $parent_cat = ProjectCategory::find($request->parent_id);

        $created_category = ProjectCategory::create(array('created_by' => Auth::user()->id, 'parent_id' => $request->parent_id, 'slug' => str_slug($request->slug)));

        $translated_category = $created_category->translateOrNew(\App::getLocale());
        $translated_category->name = $request->name;
        $translated_category->locale = \App:: getLocale();
        $translated_category->project_category_id = $created_category->id;
        $translated_category->save();

        return redirect("admin/project-categories")->with('status', 'Category created successfully!');
      }
    }
  }

  public function updateProjectCategory(Request $request, $category_id, $locale = "") {

    $category = ProjectCategory::find($category_id);

    if ($category) {
      $translated_category = $category->translateOrNew($locale);

      if ($request->method() == "GET") {
        $existing_categories = ProjectCategory::withTranslation()->where('id', '<>', $category_id)->get();

        $tree = $this->getCategoryTree($existing_categories->toTree(), '&nbsp;&nbsp;');

        if (isset($locale) && $locale != 'en' && $locale != '') {

          return view("project::update-language-category", array('category' => $translated_category, 'main_catgeoy_details' => $category));
        } else {
          return view("project::update-category", array('category' => $translated_category, 'parent_id' => $category->parent_id, 'locale' => $locale, 'tree' => $tree, 'main_catgeoy_details' => $category));
        }
      } else {

        $data = $request->all();
        if ($locale != 'en' && $locale != '') {
          $validate_response = Validator::make($data, array(
                'name' => 'required',
          ));
        } else {
          $validate_response = Validator::make($data, array(
                'name' => 'required',
                'slug' => 'required|unique:post_categories,slug,' . str_slug($category->id),
              ), array('name.required' => 'Please enter name',
                'slug.required' => 'Please enter url',
                'slug.unique' => 'This url is already exists',
          ));
        }
        
        if ($validate_response->fails()) {
          return redirect($request->url())->withErrors($validate_response)->withInput();
        } else {
          $translated_category->name = $request->name;

          
          if ($locale != 'en' && $locale != '') {
            $translated_category->project_category_id = $request->parent_id;
            $translated_category->locale = $locale;
            $translated_category->name = $request->name;
          } else {
            $parent_cat = ProjectCategory::find($request->parent_id);
            
            ProjectCategory::where('id',$category_id)->update(['parent_id'=>$request->parent_id,'slug'=>$request->slug]);
            
          }
          $translated_category->save();
          return redirect("admin/project-categories")->with('status', 'Category updated successfully!');
        }
      }
    } else {
      return redirect('admin/project-categories');
    }
  }

  public function deleteProjectCategory($category_id) {

    $category = ProjectCategory::find($category_id);

    if ($category) {
      $category->delete();
      return redirect("admin/project-categories")->with('status', 'Category deleted successfully!');
    }
  }

  public function deleteSelectedProjectCategory($category_id) {
    $category = ProjectCategory::find($category_id);

    if ($category) {
      $category->delete();
      echo json_encode(array("success" => '1', 'msg' => 'Selected records has been deleted successfully.'));
    } else {
      echo json_encode(array("success" => '0', 'msg' => 'There is an issue in deleting records.'));
    }
  }

  public function viewProjects(Request $request, $project_url) {
    
  }

  public function viewProjectsForTag($tag_slug) {
    
  }

  public function viewProjectsForCategory($category_slug) {
    
  }

  private function getCategoryTree($nodes, $prefix = "-") {

    $arr_cats = array();
    $traverse = function ($categories, $prefix) use (&$traverse, &$arr_cats ) {

      foreach ($categories as $category) {


        $arr_cats[] = new categoryTreeHolder($prefix . ' ' . $category->name, $category->id);

        $traverse($category->children, $prefix . $prefix);
      }
    };

    $traverse($nodes, $prefix);

    return $arr_cats;
  }

  public function listProjectCategorySkills(Request $request, $category_id) {
    return view('project::list-category-skills', array('category_id' => $category_id));
  }
  
  public function listProjectDocuments(Request $request, $project_id) {
    
    $project_title=Project::find($project_id)->title;
    return view('project::list-project-documents', array('project_id' => $project_id,'project_title'=>$project_title));
  }

  public function listProjectDocumentsData(Request $request, $project_id) {
    $all_documents = ProjectDocument::where('project_id', $project_id)
      ->where('document_type', 'document')
      ->get();
    
    
    return Datatables::of($all_documents)
        ->make(true);
  }
  
  public function createProjectDocuments(Request $request, $project_id) {
    $project_title=Project::find($project_id)->title;
    if ($request->method() == "GET") {
      return view("project::create-project-document", array('project_id' => $project_id,'project_title'=>$project_title));
    } else {
      $data = $request->all();
      $validate_response = Validator::make($data, array(
            'document' => 'required',
          ));
      if ($validate_response->fails()) {
        return redirect($request->url())->withErrors($validate_response)->withInput();
      } else {
        $document_file_data = FileUpload::upload(
            $request, array("document"), "*", array("project_documents/"),null, null
        );

        
        $document_file_data = array(
            'project_id' => $project_id,
            'name' => $document_file_data['original_file_name'][0],
            'path' => $document_file_data['new_file_name'][0],
            'document_type' => 'document',
        );

        if ($request->has('description')) {
          $document_file_data['description'] = $request->description;
        }

        ProjectDocument::create($document_file_data);
        return redirect("admin/project-documents/" . $project_id)->with('status', 'Project document created successfully!');
      }
    }
  }
  
  public function updateProjectDocuments(Request $request, $project_id, $document_id, $locale = "") {
    $document = ProjectDocument::find($document_id);
$project_title=Project::find($project_id)->title;
    if ($document) {
      if ($request->method() == "GET") {
        return view("project::update-project-document", array('project_id' => $project_id,'document_data'=>$document,'project_title'=>$project_title));
      } else {
        $arr_document_data=array();
        if($request->hasFile('document'))
        {
          
          unlink(base_path().'/storage/app/project_documents/'.$document->path);
        $document_file_data = FileUpload::upload(
            $request, array("document"), "document", array("project_documents/"), null, null
        );
        $arr_document_data = array(
            'name' => $document_file_data['original_file_name'][0],
            'path' => $document_file_data['new_file_name'][0],
            'document_type' => 'document',
        );
        }
        if ($request->has('description')) {
          $arr_document_data['description'] = $request->description;
        }
        if(count($arr_document_data)>0)
        {
        ProjectDocument::where('id',$document_id)->update($arr_document_data);
        }
        return redirect("admin/project-documents/" . $project_id)->with('status', 'Project document updated successfully!');
      }
    } else {
      return redirect('admin/project-documents/' . $project_id);
    }
  }
  
  public function deleteProjectDocuments(Request $request, $project_id, $document_id) {
    $document_data = ProjectDocument::find($document_id);

    if ($document_data) {
      unlink(base_path().'/storage/app/project_documents/'.$document_data->path);
      $document_data->delete();
      return redirect("admin/project-documents/" . $project_id)->with('status', 'Document deleted successfully!');
    }
  }
  
  public function deleteSelectedProjectDocuments(Request $request, $document_id) {
    $document_data = ProjectDocument::find($document_id);

    if ($document_data) {
      unlink(base_path().'/storage/app/project_documents/'.$document_data->path);
      $document_data->delete();
      echo json_encode(array("success" => '1', 'msg' => 'Selected records has been deleted successfully.'));
    } else {
      echo json_encode(array("success" => '0', 'msg' => 'There is an issue in deleting records.'));
    }
  }
  
  public function downloadProjectDocument($file_name) {
		$dir = base_path() . '/storage/app/project_documents/'. $file_name;
		if (file_exists($dir)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . basename($dir));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($dir));
			ob_clean();
			flush();
			readfile($dir);
			exit;
		} else
           return redirect(URL::previous())->with('status','Unable to download document.');
      }
  public function listProjectImages(Request $request, $project_id) {
    $project_title=Project::find($project_id)->title;
    return view('project::list-project-images', array('project_id' => $project_id,'project_title'=>$project_title));
  }

  public function listProjectImagesData(Request $request, $project_id) {
    $all_images = ProjectDocument::where('project_id', $project_id)
      ->where('document_type', 'image')
      ->get();
    return Datatables::of($all_images)
        ->make(true);
  }
  
  public function createProjectImage(Request $request, $project_id) {
    $project_title=Project::find($project_id)->title;
    if ($request->method() == "GET") {
      return view("project::create-project-image", array('project_id' => $project_id,'project_title'=>$project_title));
    } else {
      $data = $request->all();
      $validate_response = Validator::make($data, array(
            'image' => 'required|mimes:jpeg,png,jpg',
          ));
      if ($validate_response->fails()) {
        return redirect($request->url())->withErrors($validate_response)->withInput();
      } else {
        $image_file_data = FileUpload::upload(
            $request, array("image"), "image", array("project_images/"), (
            ['resize' => true, 'width' => 120, 'height' => 120, 'destination' => 'project_images/thumb/']
            ), null
        );

        
        $arr_image_data = array(
            'project_id' => $project_id,
            'name' => $image_file_data['original_file_name'][0],
            'path' => $image_file_data['new_file_name'][0],
            'document_type' => 'image',
        );

        if ($request->has('description')) {
          $arr_image_data['description'] = $request->description;
        }

        ProjectDocument::create($arr_image_data);

        return redirect("admin/project-images/" . $project_id)->with('status', 'Project image created successfully!');
      }
    }
  }
  
  public function updateProjectImage(Request $request, $project_id, $image_id, $locale = "") {
    $image = ProjectDocument::find($image_id);
    $project_title=Project::find($project_id)->title;
    if ($image) {


      if ($request->method() == "GET") {
        return view("project::update-project-image", array('project_id' => $project_id,'image_data'=>$image,'project_title'=>$project_title));
      } else {
      
        $arr_image_data=array();
        if($request->hasFile('image'))
        {
          unlink(base_path().'/storage/app/project_images/'.$image->path);
        $image_file_data = FileUpload::upload(
            $request, array("image"), "image", array("project_images/"), (
            ['resize' => true, 'width' => 120, 'height' => 120, 'destination' => 'project_images/thumb/']
            ), null
        );
        $arr_image_data = array(
            
            'name' => $image_file_data['original_file_name'][0],
            'path' => $image_file_data['new_file_name'][0],
            'document_type' => 'image',
        );

        }
        
        
        if ($request->has('description')) {
          $arr_image_data['description'] = $request->description;
        }

       if(count($arr_image_data)>0)
       {
        ProjectDocument::where('id',$image_id)->update($arr_image_data);
       }
        
        return redirect("admin/project-images/" . $project_id)->with('status', 'Project image updated successfully!');
      }
    } else {
      return redirect('admin/project-images/' . $project_id);
    }
  }
  
  public function deleteProjectImage(Request $request, $project_id, $image_id) {
    $image_data = ProjectDocument::find($image_id);

    if ($image_data) {
      unlink(base_path().'/storage/app/project_images/'.$image_data->path);
      $image_data->delete();
      return redirect("admin/project-images/" . $project_id)->with('status', 'Image deleted successfully!');
    }
  }
  
  public function deleteSelectedProjectImage(Request $request, $image_id) {
    $image_data = ProjectDocument::find($image_id);

    if ($image_data) {
      unlink(base_path().'/storage/app/project_images/'.$image_data->path);
      $image_data->delete();
      echo json_encode(array("success" => '1', 'msg' => 'Selected records has been deleted successfully.'));
    } else {
      echo json_encode(array("success" => '0', 'msg' => 'There is an issue in deleting records.'));
    }
  }
  public function listProjectCategorySkillsData(Request $request, $category_id) {
    $all_skills = ProjectCategorySkill::where('project_category_id', $category_id)->get();

    return Datatables::of($all_skills)
        ->addColumn('Language', function($skill) use($category_id) {
          $language = '<button class="btn btn-sm btn-warning dropdown-toggle" type="button" id="langDropDown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Another Language <span class="caret"></span> </button>
    <ul class="dropdown-menu multilanguage" aria-labelledby="langDropDown">';
          if (count(config("translatable.locales_to_display"))) {
            foreach (config("translatable.locales_to_display") as $locale => $locale_full_name) {
              if ($locale != 'en') {
                $language.='<li class="dropdown-item"> <a href="edit/' . $category_id . "/" . $skill->id . '/' . $locale . '">' . $locale_full_name . '</a></li>';
              }
            }
          }
          return $language;
        })
        ->make(true);
  }

  
  public function createProjectCategorySkill(Request $request, $category_id) {

    if ($request->method() == "GET") {

      return view("project::create-category-skill", array('category_id' => $category_id));
    } else {
      $data = $request->all();
      $data['name'] = (trim($data['name']));
      $data['description'] = (trim($data['description']));

      $validate_response = Validator::make($data, array(
            'name' => 'required|unique:project_category_skill_translations',
          ), array('name.required' => 'Please enter name'
      ));

      if ($validate_response->fails()) {
        return redirect($request->url())->withErrors($validate_response)->withInput();
      } else {


        $created_category = ProjectCategorySkill::create(
            array(
                'created_by' => Auth::user()->id,
                'project_category_id' => $category_id
            )
        );

        $translated_category = $created_category->translateOrNew(\App::getLocale());
        $translated_category->name = $request->name;
        $translated_category->description = $request->description;
        $translated_category->locale = \App:: getLocale();
        $translated_category->project_category_skill_id = $created_category->id;
        $translated_category->save();

        return redirect("admin/project-category-skills/" . $category_id)->with('status', 'Skill created successfully!');
      }
    }
  }

  
  public function updateProjectCategorySkills(Request $request, $category_id, $skill_id, $locale = "") {

    $skill = ProjectCategorySkill::find($skill_id);

    if ($skill) {
      $translated_skill = $skill->translateOrNew($locale);
      

      if ($request->method() == "GET") {
        return view("project::update-language-category-skill", array('skill' => $translated_skill, 'category_id' => $category_id));
      } else {
        $data = $request->all();
        $validate_response = Validator::make($data, array(
              'name' => 'required|unique:project_category_skill_translations,name,'.$translated_skill->id,
        ));
        if ($validate_response->fails()) {
          return redirect($request->url())->withErrors($validate_response)->withInput();
        } else {

          if ($locale != 'en' && $locale != '') {
            $translated_skill->project_category_skill_id = $skill_id;
            $translated_skill->locale = $locale;
          }

          $translated_skill->name = $request->name;
          $translated_skill->description = $request->description;
          $translated_skill->save();
          
          $skill->updated_at=time();
          $skill->save();
          return redirect('admin/project-category-skills/' . $category_id);
        }
      }
    } else {
      return redirect('admin/project-category-skills/' . $category_id);
    }
  }

  
  public function deleteProjectCategorySkill(Request $request, $category_id, $skill_id) {
    $skill = ProjectCategorySkill::find($skill_id);

    if ($skill) {
      $skill->delete();
      return redirect("admin/project-category-skills/" . $category_id)->with('status', 'Skill deleted successfully!');
    }
  }

  
  public function deleteSelectedProjectCategorySkills(Request $request, $skill_id) {
    $skill = ProjectCategorySkill::find($skill_id);

    if ($skill) {
      $skill->delete();
      echo json_encode(array("success" => '1', 'msg' => 'Selected records has been deleted successfully.'));
    } else {
      echo json_encode(array("success" => '0', 'msg' => 'There is an issue in deleting records.'));
    }
  }

  public function viewProject() {
    
  }

  public function searchProjectByKeyword($keyword) {
    
  }

  private function getCategoryTreeList($nodes, $prefix = "</li><li>", $include_anchor = false) {
    
  }

  private static function getCurrencies() {
    return array("AED" => "UAE dirham", "AFN" => "Afghan afghani", "ALL" => "Albanian lek", "AMD" => "Armenian dram", "ANG" => "Netherlands Antillean guilder", "AOA" => "Angolan kwanza", "ARS" => "Argentine peso", "AUD" => "Australian dollar", "AWG" => "Aruban florin", "AZN" => "Azerbaijani manat", "BAM" => "Bosnia and Herzegovina convertible mark", "BBD" => "Barbadian dollar", "BDT" => "Bangladeshi taka", "BGN" => "Bulgarian lev", "BHD" => "Bahraini dinar", "BIF" => "Burundi franc", "BMD" => "Bermudian dollar", "BND" => "Brunei dollar", "BOB" => "Bolivian boliviano", "BRL" => "Brazilian real", "BSD" => "Bahamian dollar", "BTN" => "Bhutanese ngultrum", "BWP" => "Botswana pula", "BYN" => "Belarusian ruble", "BZD" => "Belize dollar", "CAD" => "Canadian dollar", "CDF" => "Congolese franc", "CHF" => "Swiss franc", "CLP" => "Chilean peso", "CNY" => "Chinese Yuan Renminbi", "COP" => "Colombian peso", "CRC" => "Costa Rican colon", "CUP" => "Cuban peso", "CVE" => "Cape Verdean escudo", "CZK" => "Czech koruna", "DJF" => "Djiboutian franc", "DKK" => "Danish krone", "DOP" => "Dominican peso", "DZD" => "Algerian dinar", "EGP" => "Egyptian pound", "ERN" => "Eritrean nakfa", "ETB" => "Ethiopian birr", "EUR" => "European euro", "FJD" => "Fijian dollar", "FKP" => "Falkland Islands pound", "GBP" => "Pound sterling", "GEL" => "Georgian lari", "GGP" => "Guernsey Pound", "GHS" => "Ghanaian cedi", "GIP" => "Gibraltar pound", "GMD" => "Gambian dalasi", "GNF" => "Guinean franc", "GTQ" => "Guatemalan quetzal", "GYD" => "Guyanese dollar", "HKD" => "Hong Kong dollar", "HNL" => "Honduran lempira", "HRK" => "Croatian kuna", "HTG" => "Haitian gourde", "HUF" => "Hungarian forint", "IDR" => "Indonesian rupiah", "ILS" => "Israeli new shekel", "IMP" => "Manx pound", "INR" => "Indian rupee", "IQD" => "Iraqi dinar", "IRR" => "Iranian rial", "ISK" => "Icelandic krona", "JEP" => "Jersey pound", "JMD" => "Jamaican dollar", "JOD" => "Jordanian dinar", "JPY" => "Japanese yen", "KES" => "Kenyan shilling", "KGS" => "Kyrgyzstani som", "KHR" => "Cambodian riel", "KMF" => "Comorian franc", "KPW" => "North Korean won", "KRW" => "South Korean won", "KWD" => "Kuwaiti dinar", "KYD" => "Cayman Islands dollar", "KZT" => "Kazakhstani tenge", "LAK" => "Lao kip", "LBP" => "Lebanese pound", "LKR" => "Sri Lankan rupee", "LRD" => "Liberian dollar", "LSL" => "Lesotho loti", "LYD" => "Libyan dinar", "MAD" => "Moroccan dirham", "MDL" => "Moldovan leu", "MGA" => "Malagasy ariary", "MKD" => "Macedonian denar", "MMK" => "Myanmar kyat", "MNT" => "Mongolian tugrik", "MOP" => "Macanese pataca", "MRO" => "Mauritanian ouguiya", "MUR" => "Mauritian rupee", "MVR" => "Maldivian rufiyaa", "MWK" => "Malawian kwacha", "MXN" => "Mexican peso", "MYR" => "Malaysian ringgit", "MZN" => "Mozambican metical", "NAD" => "Namibian dollar", "NGN" => "Nigerian naira", "NIO" => "Nicaraguan cordoba", "NOK" => "Norwegian krone", "NPR" => "Nepalese rupee", "NZD" => "New Zealand dollar", "OMR" => "Omani rial", "PEN" => "Peruvian sol", "PGK" => "Papua New Guinean kina", "PHP" => "Philippine peso", "PKR" => "Pakistani rupee", "PLN" => "Polish zloty", "PYG" => "Paraguayan guarani", "QAR" => "Qatari riyal", "RON" => "Romanian leu", "RSD" => "Serbian dinar", "RUB" => "Russian ruble", "RWF" => "Rwandan franc", "SAR" => "Saudi Arabian riyal", "SBD" => "Solomon Islands dollar", "SCR" => "Seychellois rupee", "SDG" => "Sudanese pound", "SEK" => "Swedish krona", "SGD" => "Singapore dollar", "SHP" => "Saint Helena pound", "SLL" => "Sierra Leonean leone", "SOS" => "Somali shilling", "SRD" => "Surinamese dollar", "SSP" => "South Sudanese pound", "STD" => "Sao Tome and Principe dobra", "SYP" => "Syrian pound", "SZL" => "Swazi lilangeni", "THB" => "Thai baht", "TJS" => "Tajikistani somoni", "TMT" => "Turkmen manat", "TND" => "Tunisian dinar", "TOP" => "Tongan pa’anga", "TRY" => "Turkish lira", "TTD" => "Trinidad and Tobago dollar", "TWD" => "New Taiwan dollar", "TZS" => "Tanzanian shilling", "UAH" => "Ukrainian hryvnia", "UGX" => "Ugandan shilling", "USD" => "United States dollar", "UYU" => "Uruguayan peso", "UZS" => "Uzbekistani som", "VEF" => "Venezuelan bolivar", "VND" => "Vietnamese dong", "VUV" => "Vanuatu vatu", "WST" => "Samoan tala", "XAF" => "Central African CFA franc", "XCD" => "East Caribbean dollar", "XDR" => "SDR (Special Drawing Right)", "XOF" => "West African CFA franc", "XPF" => "CFP franc", "YER" => "Yemeni rial", "ZAR" => "South African rand", "ZMW" => "Zambian kwacha");
  }
  
    public function getAllCitiesByState($state_id) {
        $cities = City::where('state_id', $state_id)->translatedIn(\App::getLocale())->get();
        $select_value = '<option value="">--Select--</option>';
        if ($cities) {
            foreach ($cities as $key => $value) {

                $select_value.='<option value="' . $value->id . '">' . $value->name . '</option>';
            }
        }
        echo $select_value;
        exit;

        //return view('admin::list-states');
    }

}

class categoryTreeHolder {

  public $display = '';
  public $id = '';
  public $slug = '';

  public function __construct($display, $id, $slug = '') {
    $this->id = $id;
    $this->display = $display;
    $this->slug = $slug;
  }

}
