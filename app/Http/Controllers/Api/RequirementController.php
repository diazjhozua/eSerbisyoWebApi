<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequirementRequest;
use App\Http\Resources\RequirementResource;
use App\Models\Requirement;

class RequirementController extends Controller
{
    public function index()
    {
        $requirements = Requirement::withCount('certificates')->get();
        return RequirementResource::collection($requirements)->additional(['success' => true]);
    }

    public function store(RequirementRequest $request)
    {
        $requirement = Requirement::create($request->validated());
        $requirement->certificates_count = 0;
        return (new RequirementResource($requirement))->additional(Helper::instance()->storeSuccess('requirement'));
    }

    public function show(Requirement $requirement)
    {
        return (new RequirementResource($requirement->load('certificates')->loadCount('certificates')))->additional(Helper::instance()->itemFound('requirement'));
    }

    public function edit(Requirement $requirement)
    {
        return (new RequirementResource($requirement))->additional(Helper::instance()->itemFound('requirement'));
    }

    public function update(RequirementRequest $request, Requirement $requirement)
    {
        $requirement->fill($request->validated())->save();
        return (new RequirementResource($requirement->load('certificates')->loadCount('certificates')))->additional(Helper::instance()->updateSuccess('requirement'));
    }

    public function destroy(Requirement $requirement)
    {
        $requirement->delete();
        return response()->json(Helper::instance()->destroySuccess('document'));
    }
}
