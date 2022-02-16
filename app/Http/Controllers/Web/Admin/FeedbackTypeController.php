<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\FeedbackTypeRequest;
use App\Http\Requests\Report\FeedbackReportRequest;
use App\Http\Requests\Report\FeedbackTypeReportRequest;
use App\Http\Requests\Web\RespondFeedbackRequest;
use App\Http\Resources\FeedbackTypeResource;
use App\Models\Feedback;
use App\Models\Type;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;

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

        return view('admin.information.feedback-types.index')->with('types', $types);
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
            ->selectRaw("count(case when status = 'Pending' then 1 end) as pending_count")
            ->selectRaw("count(case when status = 'Noted' then 1 end) as noted_count")
            ->selectRaw("count(case when status = 'Ignored' then 1 end) as ignored_count")
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
                'pending_count' => $otherTotals->pending_count,
                'noted_count' => $otherTotals->noted_count,
                'ignored_count' => $otherTotals->ignored_count,
                'feedbacks_count' => $otherTotals->feedbacks_count,
                'positive_count' => $otherTotals->positive_count,
                'neutral_count' => $otherTotals->neutral_count,
                'negative_count' => $otherTotals->negative_count,
                'feedbacks' => Feedback::where('type_id', '=', NULL)
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
            }, 'feedbacks as pending_count' => function ($query) {
                $query->where('status', 'Pending');
            }, 'feedbacks as noted_count' => function ($query) {
                $query->where('status', 'Noted');
            }, 'feedbacks as ignored_count' => function ($query) {
                $query->where('status', 'Ignored');
            }])->where('model_type', 'Feedback')->findOrFail($id);
        }

        return view('admin.information.feedback-types.show')->with('type', $type);
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
        $type = Type::with(['feedbacks' => function ($query) {
            $query->orderBy('created_at', 'DESC');
        }])->withCount(['feedbacks','feedbacks as positive_count' => function ($query) {
            $query->where('polarity', 'Positive');
        }, 'feedbacks as neutral_count' => function ($query) {
            $query->where('polarity', 'Neutral');
        }, 'feedbacks as negative_count' => function ($query) {
            $query->where('polarity', 'Negative');
        }, 'feedbacks as pending_count' => function ($query) {
            $query->where('status', 'Pending');
        }, 'feedbacks as noted_count' => function ($query) {
            $query->where('status', 'Noted');
        }, 'feedbacks as ignored_count' => function ($query) {
            $query->where('status', 'Ignored');
        }])->where('model_type', 'Feedback')->findOrFail($id);

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

    public function report($date_start,  $date_end, $sort_column, $sort_option) {

        $title = 'Report - No data';
        $description = 'No data';

        try {
            $types = Type::withCount(['feedbacks','feedbacks as positive_count' => function ($query) { $query->where('polarity', 'Positive');
                }, 'feedbacks as neutral_count' => function ($query) {
                    $query->where('polarity', 'Neutral');
                }, 'feedbacks as negative_count' => function ($query) {
                    $query->where('polarity', 'Negative');
                }])->whereBetween('created_at', [$date_start, $date_end])
                    ->where('model_type', 'Feedback')
                    ->orderBy($sort_column, $sort_option)
                    ->get();

                $otherTotals = null;

                $otherTotals = DB::table('feedbacks')->selectRaw('count(*) as feedbacks_count')
                    ->selectRaw("count(case when polarity = 'Positive' then 1 end) as positive_count")
                    ->selectRaw("count(case when polarity = 'Neutral' then 1 end) as neutral_count")
                    ->selectRaw("count(case when polarity = 'Negative' then 1 end) as negative_count")
                    ->where('type_id', '=', NULL)
                    ->whereBetween('created_at', [$date_start, $date_end])
                    ->first();

                if ($otherTotals->feedbacks_count != 0) {
                    $types->add(new Type([ 'id' => 0, 'name' => 'Others', 'model_type' => 'Feedback',
                        'created_at' => now(), 'updated_at' => now(),
                        'feedbacks_count' => $otherTotals->feedbacks_count, 'positive_count' => $otherTotals->positive_count,
                        'neutral_count' => $otherTotals->neutral_count, 'negative_count' => $otherTotals->negative_count,
                    ]));
                }

        } catch(\Illuminate\Database\QueryException $ex){
            return view('errors.404Report', compact('title', 'description'));
        }

        if ($types->isEmpty()) {
            return view('errors.404Report', compact('title', 'description'));
        }

        $title = 'Feedback Type Reports';
        $modelName = 'Feedback Type';

        return view('admin.information.pdf.feedbackTypes', compact('title', 'modelName', 'types', 'otherTotals',
            'date_start', 'date_end', 'sort_column', 'sort_option'
        ));
    }

    public function reportShow($date_start,  $date_end, $sort_column, $sort_option, $polarity_option, $status_option, $type_id) {

        $title = 'Report - No data';
        $description = 'No data';
        try {
            $feedbacks = Feedback::with('type')
                ->whereBetween('created_at', [$date_start, $date_end])
                ->orderBy($sort_column, $sort_option)
                ->where(function($query) use ($polarity_option, $status_option) {
                    if($polarity_option == 'all' && $status_option == 'all') {
                        return null;
                    } elseif ($polarity_option == 'all' && $status_option != 'all') {
                        return $query->where('status', '=', ucwords($status_option));
                    } elseif ($polarity_option != 'all' && $status_option == 'all') {
                        return $query->where('polarity', '=', ucwords($polarity_option));
                    } else {
                        return $query->where('status', '=', ucwords($status_option))
                        ->where('polarity', '=', ucwords($polarity_option));
                    }
                })
                ->where(function($query) use ($type_id) {
                    if ($type_id == 0) {
                        return $query->where('type_id', NULL);
                    }else {
                        return $query->where('type_id', $type_id);
                    }
                })
                ->get();
        } catch(\Illuminate\Database\QueryException $ex){
            return view('errors.404Report', compact('title', 'description'));
        }

        if ($feedbacks->isEmpty()) {
            return view('errors.404Report', compact('title', 'description'));
        }

        $feedbacksData = null;

        $feedbacksData =  DB::table('feedbacks')
            ->selectRaw('count(*) as feedbacks_count')
            ->selectRaw("count(case when status = 'Pending' then 1 end) as pending_count")
            ->selectRaw("count(case when status = 'Noted' then 1 end) as noted_count")
            ->selectRaw("count(case when status = 'Ignored' then 1 end) as ignored_count")
            ->selectRaw("count(case when polarity = 'Positive' then 1 end) as positive_count")
            ->selectRaw("count(case when polarity = 'Neutral' then 1 end) as neutral_count")
            ->selectRaw("count(case when polarity = 'Negative' then 1 end) as negative_count")
            ->where('created_at', '>=', $date_start)
            ->where('created_at', '<=', $date_end)
            ->where(function($query) use ($polarity_option, $status_option) {
                if($polarity_option == 'all' && $status_option == 'all') {
                    return null;
                } elseif ($polarity_option == 'all' && $status_option != 'all') {
                    return $query->where('status', '=', ucwords($status_option));
                } elseif ($polarity_option != 'all' && $status_option == 'all') {
                    return $query->where('polarity', '=', ucwords($polarity_option));
                } else {
                    return $query->where('status', '=', ucwords($status_option))
                    ->where('polarity', '=', ucwords($polarity_option));
                }
            })
            ->where(function($query) use ($type_id) {
                if ($type_id == 0) {
                    return $query->where('type_id', NULL);
                }else {
                    return $query->where('type_id', $type_id);
                }
            })
            ->first();


        $type = Type::find($type_id);
        $title = 'Feedback Type Reports';
        $modelName =  $type_id == 0 ? 'Others/Deleted' : $type->name;

        return view('admin.information.pdf.feedbacks', compact('title', 'modelName', 'feedbacks', 'feedbacksData',
            'date_start', 'date_end', 'sort_column', 'sort_option', 'polarity_option', 'status_option'
        ));

        // $pdf = PDF::loadView('admin.information.reports.feedback', compact('feedbacks', 'request', 'feedbacksData'))->setOptions(['defaultFont' => 'sans-serif'])->setPaper('a4', 'landscape');
        // return $pdf->stream();


    }
}
