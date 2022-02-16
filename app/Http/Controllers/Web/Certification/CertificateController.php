<?php

namespace App\Http\Controllers\Web\Certification;

use App\Http\Controllers\Controller;
use App\Http\Requests\CertificateRequest;
use App\Http\Requests\StoreRequirementRequest;
use App\Http\Resources\CertificateResource;
use App\Http\Resources\RequirementResource;
use App\Models\Certificate;
use App\Models\CertificateForm;
use App\Models\CertificateRequirement;
use App\Models\Requirement;
use Helper;
use Illuminate\Http\Request;
use Log;
use \NumberFormatter;

class CertificateController extends Controller
{

    public function convertNumber($num = false)
    {
        $num = str_replace(array(',', ''), '' , trim($num));
        if(! $num) {
            return false;
        }
        $num = (int) $num;
        $words = array();
        $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
            'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
        );
        $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
        $list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
        );
        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ( $hundreds == 1 ? '' : '' ) . ' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ( $tens < 20 ) {
                $tens = ($tens ? ' and ' . $list1[$tens] . ' ' : '' );
            } elseif ($tens >= 20) {
                $tens = (int)($tens / 10);
                $tens = ' and ' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }
        $words = implode(' ',  $words);
        $words = preg_replace('/^\s\b(and)/', '', $words );
        $words = trim($words);
        $words = strtoupper($words);
        $words = $words . " PESOS";
        return $words;
    }

    public function index()
    {
        $certificates = Certificate::withCount('certificateForms')->get();
        return view('admin.certification.certificates.index', compact('certificates'));
    }

    public function show(Certificate $certificate)
    {
        $certificate = $certificate->load('requirements');
        $certificateForms = CertificateForm::with('orders')->where('certificate_id', '=', $certificate->id)
            ->where('status', '=', 'Approved')->orderBy('created_at','DESC')->get();

        return view('admin.certification.certificates.show', compact('certificate', 'certificateForms'));
    }

    public function edit(Certificate $certificate)
    {
        if(request()->ajax()) {
            $certificate = $certificate->load('requirements');

            $status = [ (object)[ "id" => 1, "type" => "Available"],(object) ["id" => 2, "type" => "Unavailable"] ];
            $receivedOption = [ (object)[ "id" => 1, "type" => "Available for delivery and pickuo"],(object) ["id" => 0, "type" => "Pickup Only"] ];

            return response()->json([
                'data' => $certificate,
                'status' => $status,
                'receivedOption' => $receivedOption
            ], 200);
        }
    }

    public function update(CertificateRequest $request, Certificate $certificate)
    {
        $certificate->fill($request->validated())->save();

        return response()->json([
            'data' => $certificate,
            'message' => 'Certficate successfully updated.',
        ], 200);
    }

    public function addRequirement(Certificate $certificate) {

        if(request()->ajax()) {
            $id = $certificate->id;

            $requirements = Requirement::whereDoesntHave('certificates', function ($query) use ($id) {
                $query->where('certificates.id', $id);
            })->get();

            return RequirementResource::collection($requirements)->additional(['success' => true]);
        }
    }

    public function storeRequirement(StoreRequirementRequest $request) {
        CertificateRequirement::insert($request->validated());

        $certificate = Certificate::with('requirements')->withCount('requirements')->find($request->certificate_id);
        return (new CertificateResource($certificate))->additional(Helper::instance()->storeSuccess('certificate requirement'));
    }

    public function destroyRequirement(Certificate $certificate, Requirement $requirement) {
        $certificate->requirements()->detach($requirement->id);
        return response()->json(Helper::instance()->destroySuccess('certificate requirement'));
    }

    public function printCertificate(CertificateForm $certificateForm) {

        $certificateForm = $certificateForm->load('orders');

        if ($certificateForm->certificate_id == 1) {
            return view('admin.certificates.indigency', compact('certificateForm'));
        } elseif ($certificateForm->certificate_id == 2) {

            $totalTax = $certificateForm->basic_tax + $certificateForm->additional_tax + $certificateForm->gross_receipt_preceding + $certificateForm->gross_receipt_profession + $certificateForm->real_property;
            if ($certificateForm->interest != 0) {
                $interest = $totalTax * $certificateForm->interest;
                $totalAmountTax = $totalTax + $interest;
            } else {
                $totalAmountTax = $totalTax;
            }

            $totalAmountTaxWord = $this->convertNumber($totalAmountTax);

            return view('admin.certificates.cedula', compact('certificateForm', 'totalTax', 'totalAmountTax', 'totalAmountTaxWord'));

        } elseif ($certificateForm->certificate_id == 3) {
            return view('admin.certificates.clearance', compact('certificateForm'));
        } elseif ($certificateForm->certificate_id == 4) {
            return view('admin.certificates.identification', compact('certificateForm'));
        } elseif ($certificateForm->certificate_id == 5) {
            return view('admin.certificates.businessClearance', compact('certificateForm'));
        }


    }

    public function report($date_start, $date_end, $sort_column, $sort_option, $certificate_id)
    {
        $title = 'Report - No data';
        $description = 'No data';
        try {
            $certificate = Certificate::find($certificate_id);
            $certificateForms = CertificateForm::with('orders')->where('certificate_id', $certificate_id)
                ->whereBetween('created_at', [$date_start, $date_end])
                ->orderBy($sort_column, $sort_option)
                ->get();

        } catch(\Illuminate\Database\QueryException $ex){
            return view('errors.404Report', compact('title', 'description'));
        }

        if ($certificateForms->isEmpty()) {

            return view('errors.404Report', compact('title', 'description'));
        }

        $title = $certificate->name.' Report';
        $modelName = 'Certificates';

        return view('admin.certification.pdf.certificates', compact('title', 'modelName', 'certificateForms', 'certificate',
            'date_start', 'date_end', 'sort_column', 'sort_option'
        ));
    }

}
