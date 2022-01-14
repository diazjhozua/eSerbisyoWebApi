<?php

namespace App\Http\Controllers\Web\Certification;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequirementRequest;
use App\Http\Resources\RequirementResource;
use App\Models\Requirement;
use Helper;
use Illuminate\Http\Request;

class RequirementController extends Controller
{
    public function index()
    {
        $requirements = Requirement::withCount('certificates')->get();

        return view('admin.certification.requirements.index', compact('requirements'));
    }

    public function store(RequirementRequest $request)
    {
        if(request()->ajax()) {
            $requirement = Requirement::create($request->validated());
            $requirement->certificates_count = 0;
            return (new RequirementResource($requirement))->additional(Helper::instance()->storeSuccess('requirement'));
        }
    }

    public function edit(Requirement $requirement)
    {
        if(request()->ajax()) {
            return (new RequirementResource($requirement))->additional(Helper::instance()->itemFound('requirement'));
        }
    }

    public function update(RequirementRequest $request, Requirement $requirement)
    {
        if(request()->ajax()) {
            $requirement->fill($request->validated())->save();
            return (new RequirementResource($requirement->load('certificates')->loadCount('certificates')))->additional(Helper::instance()->updateSuccess('requirement'));
        }
    }

    public function destroy(Requirement $requirement)
    {
        if(request()->ajax()) {
            $requirement->delete();
            return response()->json(Helper::instance()->destroySuccess('requirement'));
        }
    }
}
