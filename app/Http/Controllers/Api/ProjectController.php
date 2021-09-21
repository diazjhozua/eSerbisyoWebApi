<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Helper\Helper;
use Illuminate\Http\Request;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        $projects = Project::orderBy('created_at','DESC')->get();

        return response()->json([
            'success' => true,
            'projects' => ProjectResource::collection($projects)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::get();


        return response()->json([ 
            'success' => true,
            'projects' => $projects,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequest $request)
    {
      
        $projects = new Project();
        $projects->id = $request->id;
        $projects->name = $request->name;
        $projects->description = $request->description;
        $projects->cost = $request->cost;
        $projects->project_start = $request->project_start;
        $projects->project_end = $request->project_end;
        $projects->location = $request->location;
        $fileName = time().'_'.$request->pdf->getClientOriginalName();
        $filePath = $request->file('pdf')->storeAs('projects', $fileName, 'public');

        $projects->pdf_name = $fileName;
        $projects->file_path = $filePath;

        $projects->is_starting = $request->is_starting;
       

        return response()->json([
            'success' => true,
            'message' => 'New project created succesfully',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $projects = Project::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Found project data',
                'projects' => new ProjectResource($projects)
                
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('projects'));
        }
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $projects = Project::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Found project data',
                'projects' => new ProjectResource($projects)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json([
                'success' => false,
                'message' => 'No project id found',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $projects = Project::findOrFail($id);
            $projects->name = $request->name;
            $projects->description = $request->description;
            $projects->cost = $request->cost;
            $projects->project_start = $request->project_start;
            $projects->project_end = $request->project_end;
            $projects->location = $request->location;
            //check if they want to update the pdf file
            if($request->hasFile('pdf')) {
                Storage::delete('public/projects/'. $projects->pdf_name);
                $fileName = time().'_'.$request->pdf->getClientOriginalName();
                $filePath = $request->file('pdf')->storeAs('projects', $fileName, 'public');

                $projects->pdf_name = $fileName;
                $projects->file_path = $filePath;
            }

            $projects->save();

            return response()->json([
                'success' => true,
                'message' => 'The project is successfully updated',
                'projects' => new ProjectResource($projects)
            ]);

        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('project'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $projects = Project::findOrFail($id);
            Storage::delete('public/projects/'. $projects->pdf_name);
            $projects->delete();
            return response()->json([
                'success' => true,
                'message' => 'The project is successfully deleted',
            ]);
        } catch (ModelNotFoundException $ex){
            return response()->json(Helper::instance()->noItemFound('projects'));
        }
    }
}
