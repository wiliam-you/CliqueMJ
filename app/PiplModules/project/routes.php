<?php

Route::group(array('module' => 'Project', 'namespace' => 'App\PiplModules\project\Controllers', 'middleware' => 'web'), function() {
    //Your routes belong to this module.

    Route::get("/admin/projects", "ProjectController@index")->middleware('permission:manage.projects');
    Route::get("/admin/projects-data", "ProjectController@getProjectData")->middleware('permission:manage.projects');

    Route::get("/admin/projects/create", "ProjectController@createProject")->middleware('permission:create.project');
    Route::post("/admin/projects/create", "ProjectController@createProject")->middleware('permission:create.project');
    
    Route::get("/admin/projects/update/{project_id}/{locale?}", "ProjectController@updateProject")->middleware('permission:update.project');
    Route::post("/admin/projects/update/{project_id}/{locale?}", "ProjectController@updateProject")->middleware('permission:update.project');

    Route::delete("/admin/project/delete/{project_id}", "ProjectController@deleteProject")->middleware('permission:delete.project');
    Route::delete("/admin/projects/delete-selected/{project_id}", "ProjectController@deleteSelectedProject")->middleware('permission:delete.project');

    Route::get("admin/project/remove-document/{project_id}/{document_id}", "ProjectController@removeProjectDocument")->middleware('permission:update.project');

    Route::get("/admin/project-categories", "ProjectController@listProjectCategories")->middleware('permission:view.projectCategories');
    Route::get("/admin/project-categories-data", "ProjectController@listProjectCategoriesData")->middleware('permission:view.projectCategories');
    Route::get("/admin/project-categories/create", "ProjectController@createProjectCategories")->middleware('permission:create.projectCategory');
    Route::post("/admin/project-categories/create", "ProjectController@createProjectCategories")->middleware('permission:create.projectCategory');

    Route::get("/admin/project-category/{category_id}/{locale?}", "ProjectController@updateProjectCategory")->middleware('permission:update.projectCategory');
    Route::post("/admin/project-category/{category_id}/{locale?}", "ProjectController@updateProjectCategory")->middleware('permission:update.projectCategory');

    Route::delete("/admin/delete-project-category/{category_id}", "ProjectController@deleteProjectCategory")->middleware('permission:delete.projectCategory');
    Route::delete("/admin/delete-project-selected-category/{category_id}", "ProjectController@deleteSelectedProjectCategory")->middleware('permission:delete.projectCategory');

    /*Project Category Skills*/

    Route::get("/admin/project-category-skills/{category_id}", "ProjectController@listProjectCategorySkills")->middleware('permission:update.projectCategory');
    Route::get("/admin/project-category-skills-data/{category_id}", "ProjectController@listProjectCategorySkillsData")->middleware('permission:update.projectCategory');

    Route::get("/admin/create-category-skills/{category_id}", "ProjectController@createProjectCategorySkill")->middleware('permission:create.projectCategory');
    Route::post("/admin/create-category-skills/{category_id}", "ProjectController@createProjectCategorySkill")->middleware('permission:create.projectCategory');


     Route::get("/admin/project-category-skills/edit/{category_id}/{skill_id}/{locale?}", "ProjectController@updateProjectCategorySkills")->middleware('permission:update.projectCategory');
     Route::post("/admin/project-category-skills/edit/{category_id}/{skill_id}/{locale?}", "ProjectController@updateProjectCategorySkills")->middleware('permission:update.projectCategory');

    Route::delete("/admin/delete-project-category-skill/{category_id}/{skill_id}", "ProjectController@deleteProjectCategorySkill")->middleware('permission:delete.projectCategory');

    Route::delete("/admin/delete-project-category-selected-skills/{skill_id}", "ProjectController@deleteSelectedProjectCategorySkills")->middleware('permission:delete.projectCategory');
    
    /*Project Images*/

    Route::get("/admin/project-images/{project_id}", "ProjectController@listProjectImages")->middleware('permission:update.projectCategory');
    Route::get("/admin/project-images-data/{category_id}", "ProjectController@listProjectImagesData")->middleware('permission:update.projectCategory');

    Route::get("/admin/create-project-image/{project_id}", "ProjectController@createProjectImage")->middleware('permission:create.projectCategory');
    Route::post("/admin/create-project-image/{project_id}", "ProjectController@createProjectImage")->middleware('permission:create.projectCategory');


     Route::get("/admin/project-image/edit/{project_id}/{image_id}/{locale?}", "ProjectController@updateProjectImage")->middleware('permission:update.projectCategory');
     Route::post("/admin/project-image/edit/{project_id}/{image_id}/{locale?}", "ProjectController@updateProjectImage")->middleware('permission:update.projectCategory');

    Route::delete("/admin/delete-project-image/{project_id}/{image_id}", "ProjectController@deleteProjectImage")->middleware('permission:delete.projectCategory');

    Route::delete("/admin/delete-selected-project-images/{image_id}", "ProjectController@deleteSelectedProjectImage")->middleware('permission:delete.projectCategory');

    /* End Project images  */
    
    
    /*Project Documents*/

    Route::get("/admin/project-documents/{project_id}", "ProjectController@listProjectDocuments")->middleware('permission:update.projectCategory');
    Route::get("/admin/project-documents-data/{category_id}", "ProjectController@listProjectDocumentsData")->middleware('permission:update.projectCategory');

    Route::get("/admin/create-project-documents/{project_id}", "ProjectController@createProjectDocuments")->middleware('permission:create.projectCategory');
    Route::post("/admin/create-project-documents/{project_id}", "ProjectController@createProjectDocuments")->middleware('permission:create.projectCategory');


     Route::get("/admin/project-documents/edit/{project_id}/{document_id}/{locale?}", "ProjectController@updateProjectDocuments")->middleware('permission:update.projectCategory');
     Route::post("/admin/project-documents/edit/{project_id}/{document_id}/{locale?}", "ProjectController@updateProjectDocuments")->middleware('permission:update.projectCategory');

    Route::delete("/admin/delete-project-documents/{project_id}/{document_id}", "ProjectController@deleteProjectDocuments")->middleware('permission:delete.projectCategory');

    Route::delete("/admin/delete-selected-project-documents/{document_id}", "ProjectController@deleteSelectedProjectDocuments")->middleware('permission:delete.projectCategory');
    Route::get("/admin/download-document/{file_name}", "ProjectController@downloadProjectDocument")->middleware('permission:delete.projectCategory');

    /* End Project Documents*/

    Route::get("/projects", "ProjectController@viewProjects");
    Route::get("/projects/api/tags", "ProjectController@getAllTags");

    Route::post("/projects/search", function(\Illuminate\Http\Request $request) {

        if (empty($request->searchText))
            return redirect()->back()->with("search-error", "Please enter search value");

        return redirect('/projects/search/' . $request->searchText);
    });

    Route::get("/projects/search/{keyword}", "ProjectController@searchProjectByKeyword");

    Route::get("/projects/tags/{tag_slug}", "ProjectController@viewProjectsForTag");
    Route::get("/projects/categories/{category_slug}", "ProjectController@viewProjectsForCategory");

    Route::get("/project/{project_url}", "ProjectController@viewProject");
    Route::post("/project/{project_url}", "ProjectController@viewProject")->middleware('auth');
    
    Route::get("/admin/cities/getAllCities/{state_id}","ProjectController@getAllCitiesByState")->middleware('permission:view.manage-cities');
});
