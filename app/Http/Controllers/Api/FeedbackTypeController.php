<?php

namespace App\Http\Controllers\Api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\FeedbackTypeRequest;
use App\Http\Resources\FeedbackTypeResource;
use App\Models\Feedback;
use App\Models\Type;

use Illuminate\Support\Facades\DB;

class FeedbackTypeController extends Controller
{

    public function index()
    {
        $types = Type::withCount(['feedbacks','feedbacks as positive_count' => function ($query) { $query->where('polarity', 'Positive');
        }, 'feedbacks as neutral_count' => function ($query) {
            $query->where('polarity', 'Neutral');
        }, 'feedbacks as negative_count' => function ($query) {
            $query->where('polarity', 'Negative');
        }])->where('model_type', 'Feedback')->orderBy('feedbacks_count', 'DESC')->get();

        $otherTotals = DB::table('feedbacks')->selectRaw('count(*) as feedbacks_count')
            ->selectRaw("count(case when polarity = 'Positive' then 1 end) as positive_count")
            ->selectRaw("count(case when polarity = 'Neutral' then 1 end) as neutral_count")
            ->selectRaw("count(case when polarity = 'Negative' then 1 end) as negative_count")
            ->where('type_id', '=', NULL)->first();

        $types->add(new Type([ 'id' => 0, 'name' => 'Others', 'model_type' => 'Feedback',
            'created_at' => now(), 'updated_at' => now(),
            'feedbacks_count' => $otherTotals->feedbacks_count, 'positive_count' => $otherTotals->positive_count,
            'neutral_count' => $otherTotals->neutral_count, 'negative_count' => $otherTotals->negative_count,
        ]));

        return FeedbackTypeResource::collection($types)->additional(['success' => true]);
    }

    public function store(FeedbackTypeRequest $request)
    {
        $type = Type::create(array_merge($request->validated(), ['model_type' => 'Feedback']));
        $type->feedbacks_count = 0;
        $type->positive_count = 0;
        $type->neutral_count = 0;
        $type->negative_count = 0;

        return (new FeedbackTypeResource($type))->additional(Helper::instance()->storeSuccess('feedback_type'));
    }

    public function show($id)
    {
        if ($id == 0) {
            $otherTotals = DB::table('feedbacks')
            ->selectRaw('count(*) as feedbacks_count')
            ->selectRaw("count(case when polarity = 'Positive' then 1 end) as positive_count")
            ->selectRaw("count(case when polarity = 'Neutral' then 1 end) as neutral_count")
            ->selectRaw("count(case when polarity = 'Negative' then 1 end) as negative_count")
            ->where('type_id', '=', NULL)
            ->first();

            $type = new Type([
                'id' => 0,
                'name' => 'Others',
                'model_type' => 'Feedback',
                'created_at' => now(),
                'updated_at' => now(),
                'feedbacks_count' => $otherTotals->feedbacks_count,
                'positive_count' => $otherTotals->positive_count,
                'neutral_count' => $otherTotals->neutral_count,
                'negative_count' => $otherTotals->negative_count,
                'others' => Feedback::where('type_id', '=', NULL)
                    ->orderBy('created_at', 'DESC')->get()
            ]);

        } else {

            $type = Type::with(['feedbacks' => function ($query) {
                $query->orderBy('created_at', 'DESC');
            }])->withCount(['feedbacks','feedbacks as positive_count' => function ($query) {
                $query->where('polarity', 'Positive');
            }, 'feedbacks as neutral_count' => function ($query) {
                $query->where('polarity', 'Neutral');
            }, 'feedbacks as negative_count' => function ($query) {
                $query->where('polarity', 'Negative');
            }])->where('model_type', 'Feedback')->findOrFail($id);
        }

        return (new FeedbackTypeResource($type))->additional(Helper::instance()->itemFound('feedback_type'));
    }

    public function edit($id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noEditAccess()); }
        $type = Type::where('model_type', 'Feedback')->findOrFail($id);
        return (new FeedbackTypeResource($type))->additional(Helper::instance()->itemFound('feedback_type'));
    }

    public function update(FeedbackTypeRequest $request, $id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noUpdateAccess()); }
        $type = Type::withCount('feedbacks')->where('model_type', 'Feedback')->findOrFail($id);
        $type->fill($request->validated())->save();
        return (new FeedbackTypeResource($type))->additional(Helper::instance()->updateSuccess('feedback_type'));
    }

    public function destroy($id)
    {
        if ($id == 0) { return response()->json(Helper::instance()->noDeleteAccess()); }
        $type = Type::where('model_type', 'Feedback')->findOrFail($id);
        Feedback::where('type_id', $type->id)->update(['type_id' => NULL, 'custom_type' => 'deleted type: '.$type->name]);
        $type->delete();
        return response()->json(Helper::instance()->destroySuccess('feedback_type'));
    }
}
