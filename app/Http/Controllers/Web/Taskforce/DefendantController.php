<?php

namespace App\Http\Controllers\Web\Taskforce;

use App\Http\Controllers\Controller;
use App\Http\Requests\DefendantRequest;
use App\Http\Resources\DefendantResource;
use App\Models\Complaint;
use App\Models\Defendant;
use Helper;
use Illuminate\Http\Request;

class DefendantController extends Controller
{
    public function store(DefendantRequest $request)
    {
        $defendant = Defendant::create($request->validated());
        return (new DefendantResource($defendant))->additional(Helper::instance()->storeSuccess('defendant'));
    }

    public function edit(Defendant $defendant)
    {
        return (new DefendantResource($defendant))->additional(array_merge(Helper::instance()->itemFound('defendant')));
    }

    public function update(DefendantRequest $request, Defendant $defendant)
    {
        $defendant->fill($request->validated())->save();
        return (new DefendantResource($defendant))->additional(Helper::instance()->updateSuccess('defendant'));
    }

    public function destroy(Defendant $defendant)
    {
        $defendant->delete();
        return response()->json(Helper::instance()->destroySuccess('defendant'));
    }
}
