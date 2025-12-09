<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\SurveyAsset;
use App\Models\SurveyMember;
use App\Models\Surveys;
use App\Models\SurveyUpload;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SurveysController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:surveys_list,surveys_view,surveys_create,surveys_edit,surveys_delete')->only(['index']);
        $this->middleware('permission:surveys_view')->only(['show']);
        $this->middleware('permission:surveys_create')->only(['create','store']);
        $this->middleware('permission:surveys_edit')->only(['edit','update']);
        $this->middleware('permission:surveys_delete')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organizationId = optional(request()->user())->organization_id ?? session('organization_id');

        $total = Surveys::where('organization_id', $organizationId)->count();
        $pending = Surveys::where('organization_id', $organizationId)
            ->where('revisit_flag', true)->count();
        $completedRate = $total > 0 ? round((($total - $pending) / $total) * 100, 1) : 0;

        $data['stats'] = [
            'active_surveys' => $pending,
            'total_responses' => $total,
            'completion_rate' => $completedRate,
            'pending_review' => $pending,
        ];

        $data['surveys'] = Surveys::with(['enumerator', 'family'])
            ->where('organization_id', $organizationId)
            ->withCount(['members', 'uploads'])
            ->latest()
            ->paginate(10);

        return view('survey.survey-list')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $organizationId = optional(request()->user())->organization_id ?? session('organization_id');
        $data['volunteers'] = Volunteer::where('organization_id', $organizationId)->get();
        $data['families'] = Family::where('organization_id', $organizationId)->get();

        return view('survey.survey-assessment-form')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'enumerator_id' => ['nullable', 'integer'],
            'survey_mode' => ['nullable', 'in:In-person,Phone,WhatsApp,Web'],
            'datetime' => ['nullable', 'date_format:Y-m-d\\TH:i'],
            'gps_lat' => ['nullable', 'numeric'],
            'gps_long' => ['nullable', 'numeric'],

            'consent_captured' => ['nullable'],
            'consent_type' => ['required_if:consent_captured,1,on,true', 'in:Text,Voice,Signature Photo'],
            'consent_attachment' => ['required_if:consent_type,Voice,Signature Photo', 'file', 'max:5120'],

            'revisit_needed' => ['nullable', 'in:yes,no'],
            'revisit_reason' => ['required_if:revisit_needed,yes', 'string', 'max:255'],
            'revisit_from' => ['required_if:revisit_needed,yes', 'date'],
            'revisit_to' => ['required_if:revisit_needed,yes', 'date', 'after_or_equal:revisit_from'],

            'photos' => ['required', 'array'],
            'photos.*' => ['file', 'mimes:jpg,jpeg,png', 'max:5120'],
            'audio_note' => ['nullable', 'file', 'mimetypes:audio/mpeg,audio/mp4,audio/wav,audio/x-wav,audio/ogg', 'max:20480'],

            // Preferences
            'pref_assistance' => ['nullable', 'array'],
            'program_pref' => ['nullable', 'array'],

            'family_id' => ['nullable'],
            'zakat_elig' => ['nullable', 'in:yes,no,unsure'],
            'head_of_family' => ['nullable', 'string', 'max:255'],
            'survey_cnic' => ['nullable', 'regex:/^\d{5}-\d{7}-\d$/'],
            'household_size' => ['nullable', 'integer', 'min:1'],
            'gender' => ['nullable', 'in:Male,Female'],
            'age' => ['nullable', 'integer', 'min:0', 'max:120'],
            'marital_status' => ['nullable', 'in:Married,Widowed,Divorced,Single'],
            'contact_number' => ['nullable', 'string', 'max:20'],

            'members' => ['nullable', 'array'],
            'members.*.person_id' => ['nullable', 'integer', 'exists:family_members,id'],
            'members.*.name' => ['required_with:members.*.age,members.*.gender,members.*.relation,members.*.cnic,members.*.disability,members.*.school_work', 'string', 'max:255'],
            'members.*.age' => ['nullable', 'integer', 'min:0', 'max:120'],
            'members.*.gender' => ['nullable', 'in:Male,Female,Other'],
            'members.*.relation' => ['nullable', 'string', 'max:100'],
            'members.*.education' => ['nullable', 'string', 'max:100'],
            'members.*.occupation' => ['nullable', 'string', 'max:100'],
            'members.*.cnic' => ['nullable', 'regex:/^\d{5}-\d{7}-\d$/'],
            'members.*.disability' => ['nullable', 'string', 'max:100'],
            'members.*.school_work' => ['nullable', 'string', 'max:100'],

            'address_full' => ['nullable', 'string'],
            'province' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'tehsil' => ['nullable', 'string', 'max:100'],
            'uc' => ['nullable', 'string', 'max:100'],
            'settlement_type' => ['nullable', 'string', 'max:50'],
            'tenure_type' => ['nullable', 'string', 'max:50'],
            'displacement_status' => ['nullable', 'string', 'max:50'],
            'primary_languages' => ['nullable', 'array'],
            'primary_income' => ['nullable', 'string', 'max:100'],
            'monthly_income' => ['nullable', 'integer', 'min:0'],
            'monthly_expenses' => ['nullable', 'integer', 'min:0'],
            'debt_outstanding' => ['nullable', 'integer', 'min:0'],
            'debt_source' => ['nullable', 'string', 'max:50'],
            'assets' => ['nullable', 'array'],
            'banking_access' => ['nullable', 'string', 'max:50'],
            'digital_literacy' => ['nullable', 'string', 'max:50'],
            'female_led' => ['nullable', 'in:yes,no'],
            'pwd_count' => ['nullable', 'integer', 'min:0'],

            'meals_per_day' => ['nullable', 'integer', 'min:0'],
            'food_stocks' => ['nullable', 'integer', 'min:0'],
            'child_maln' => ['nullable', 'in:unknown,yes,no'],
            'food_needs' => ['nullable', 'array'],
            'water_source' => ['nullable', 'string', 'max:50'],
            'water_distance' => ['nullable', 'integer', 'min:0'],
            'water_quality' => ['nullable', 'in:yes,no'],

            'received_aid_6m' => ['nullable', 'in:yes,no'],
            'aid_details' => ['nullable', 'string', 'max:255'],

            'purpose_explained' => ['nullable'],
            'consent_statement' => ['nullable'],
        ]);

        return DB::transaction(function () use ($request, $validated) {
            $organizationId = optional($request->user())->organization_id ?? session('organization_id');
            // Create Survey
            $survey = new Surveys;
            // Generate Survey ID: SUR-YYYY-00001 (yearly reset)
            $year = now()->year;
            $yearCount = Surveys::whereYear('created_at', $year)->lockForUpdate()->count() + 1;
            $survey->survey_code = sprintf('SUR-%d-%05d', $year, $yearCount);
            $familyId = $request->input('family_id');
            if (!$organizationId && $familyId && $familyId !== 'new') {
                $organizationId = optional(Family::find((int) $familyId))->organization_id;
            }
            if (!$organizationId && ($validated['enumerator_id'] ?? null)) {
                $organizationId = optional(Volunteer::find((int) $validated['enumerator_id']))->organization_id;
            }
            $survey->organization_id = $organizationId;
            // Set family_id if provided (and not the "new" placeholder)
            if ($familyId && $familyId !== 'new') {
                $survey->family_id = (int) $familyId;
            } else {
                $survey->family_id = null;
            }
            $survey->enumerator_id = $validated['enumerator_id'] ?? null;
            $survey->survey_mode = $validated['survey_mode'] ?? null;
            $survey->date_time = null;
            if ($request->filled('datetime')) {
                try {
                    $survey->date_time = \Carbon\Carbon::createFromFormat('Y-m-d\\TH:i', $request->input('datetime'));
                } catch (\Exception $e) {
                    $survey->date_time = null;
                }
            }
            $survey->lat = $validated['gps_lat'] ?? null;
            $survey->lon = $validated['gps_long'] ?? null;
            $survey->consent_type = ($request->boolean('consent_captured') ? ($validated['consent_type'] ?? 'Text') : null);
            $survey->revisit_flag = ($validated['revisit_needed'] ?? 'yes') === 'yes';
            $survey->revisit_from = $validated['revisit_from'] ?? null;
            $survey->revisit_to = $validated['revisit_to'] ?? null;

            // Pack extra info into flags_json (location, socio-economic, preferences, etc.)
            $flags = [
                'revisit_reason' => $validated['revisit_reason'] ?? null,
                'family_id' => $familyId ?? null,
                'head_of_family' => $validated['head_of_family'] ?? null,
                'survey_cnic' => $validated['survey_cnic'] ?? null,
                'household_size' => $validated['household_size'] ?? null,
                'address_full' => $validated['address_full'] ?? null,
                'province' => $validated['province'] ?? null,
                'district' => $validated['district'] ?? null,
                'tehsil' => $validated['tehsil'] ?? null,
                'uc' => $validated['uc'] ?? null,
                'settlement_type' => $validated['settlement_type'] ?? null,
                'tenure_type' => $validated['tenure_type'] ?? null,
                'displacement_status' => $validated['displacement_status'] ?? null,
                'primary_languages' => $validated['primary_languages'] ?? [],
                'primary_income' => $validated['primary_income'] ?? null,
                'monthly_income' => $validated['monthly_income'] ?? null,
                'monthly_expenses' => $validated['monthly_expenses'] ?? null,
                'debt_outstanding' => $validated['debt_outstanding'] ?? null,
                'debt_source' => $validated['debt_source'] ?? null,
                'banking_access' => $validated['banking_access'] ?? null,
                'digital_literacy' => $validated['digital_literacy'] ?? null,
                'female_led' => $validated['female_led'] ?? 'no',
                'pwd_count' => $validated['pwd_count'] ?? 0,
                'meals_per_day' => $validated['meals_per_day'] ?? null,
                'food_stocks' => $validated['food_stocks'] ?? null,
                'child_maln' => $validated['child_maln'] ?? 'unknown',
                'food_needs' => $validated['food_needs'] ?? [],
                'water_source' => $validated['water_source'] ?? null,
                'water_distance' => $validated['water_distance'] ?? null,
                'water_quality' => $validated['water_quality'] ?? 'no',
                'received_aid_6m' => $validated['received_aid_6m'] ?? 'no',
                'aid_details' => $validated['aid_details'] ?? null,

                // Preferences
                'pref_assistance' => $validated['pref_assistance'] ?? [],
                'program_pref' => $validated['program_pref'] ?? [],
                'purpose_explained' => $request->boolean('purpose_explained'),
                'consent_statement' => $request->boolean('consent_statement'),
            ];
            $survey->flags_json = $flags;
            $survey->save();

            // Consent attachment upload (optional)
            if ($request->hasFile('consent_attachment')) {
                $file = $request->file('consent_attachment');
                if ($file->isValid()) {
                    $path = $file->store('survey/consent', 'public');
                    SurveyUpload::create([
                        'organization_id' => $survey->organization_id,
                        'survey_id' => $survey->id,
                        'type' => 'consent',
                        'url' => $path,
                    ]);
                }
            }

            // Photos (required at least one)
            foreach ($request->file('photos', []) as $p) {
                if ($p && $p->isValid()) {
                    $path = $p->store('survey/photos', 'public');
                    SurveyUpload::create([
                        'organization_id' => $survey->organization_id,
                        'survey_id' => $survey->id,
                        'type' => 'photo',
                        'url' => $path,
                    ]);
                }
            }

            // Audio note (optional)
            if ($request->hasFile('audio_note')) {
                $a = $request->file('audio_note');
                if ($a->isValid()) {
                    $path = $a->store('survey/audio', 'public');
                    SurveyUpload::create([
                        'organization_id' => $survey->organization_id,
                        'survey_id' => $survey->id,
                        'type' => 'audio',
                        'url' => $path,
                    ]);
                }
            }

            // Members
            $members = $request->input('members', []);
            if (is_array($members)) {
                foreach ($members as $m) {
                    if (! is_array($m)) {
                        continue;
                    }
                    // Skip if the row is fully empty to avoid creating many blank members
                    $allEmpty = (
                        empty($m['name']) &&
                        empty($m['age']) &&
                        empty($m['gender']) &&
                        empty($m['relation']) &&
                        empty($m['cnic']) &&
                        empty($m['school_work']) &&
                        empty($m['disability'])
                    );
                    if ($allEmpty) {
                        continue;
                    }
                    // Require at least a name to consider this a valid member row
                    $nameVal = isset($m['name']) ? trim($m['name']) : '';
                    if ($nameVal === '') {
                        continue;
                    }

                    // Normalize age to avoid DB out-of-range errors (tinyint). If not in 0-120, set null
                    $ageVal = isset($m['age']) ? (int) $m['age'] : null;
                    if ($ageVal !== null && ($ageVal < 0 || $ageVal > 120)) {
                        $ageVal = null;
                    }

                    Log::info('Survey member raw input', ['member' => $m]);
                    $createPayload = [
                        'organization_id' => $survey->organization_id,
                        'survey_id' => $survey->id,
                        'person_id' => null,
                        'name' => $nameVal,
                        'age' => $ageVal,
                        'gender' => $m['gender'] ?? null,
                        'relation' => $m['relation'] ?? null,
                        'cnic' => $m['cnic'] ?? null,
                        'school_work' => $m['school_work'] ?? null,
                        'disability' => $m['disability'] ?? null,
                    ];
                    Log::info('Survey member create payload', $createPayload);
                    SurveyMember::create($createPayload);
                }
            }

            // Assets
            $assets = $request->input('assets', []);
            if (is_array($assets)) {
                foreach ($assets as $asset) {
                    if (! $asset) {
                        continue;
                    }
                    SurveyAsset::create([
                        'organization_id' => $survey->organization_id,
                        'survey_id' => $survey->id,
                        'asset' => $asset,
                        'present' => true,
                    ]);
                }
            }

            // If linked to an existing family, sync selected fields and members
            if ($survey->family_id) {
                $family = Family::find($survey->family_id);
                if ($family) {
                    if ($request->filled('head_of_family')) {
                        $family->head_of_family_name = $request->input('head_of_family');
                    }
                    if ($request->filled('survey_cnic')) {
                        $family->cnic = $request->input('survey_cnic');
                    }
                    if ($request->filled('gender')) {
                        $family->gender = $request->input('gender');
                    }
                    if ($request->filled('age')) {
                        $family->age = (int) $request->input('age');
                    }
                    if ($request->filled('marital_status')) {
                        $family->marital_status = $request->input('marital_status');
                    }
                    if ($request->filled('contact_number')) {
                        $family->contact_number = $request->input('contact_number');
                    }
                    $family->zakat_eligibility = ($request->input('zakat_elig') === 'yes');
                    if ($request->filled('monthly_income')) {
                        $family->monthly_income = (int) $request->input('monthly_income');
                    }
                    if ($request->filled('monthly_expenses')) {
                        $family->monthly_expense = (int) $request->input('monthly_expenses');
                    }
                    if ($request->filled('address_full')) {
                        $family->address = $request->input('address_full');
                    }
                    if ($request->filled('province')) {
                        $family->province = $request->input('province');
                    }
                    if ($request->filled('district')) {
                        $family->city = $request->input('district');
                    }
                    if ($request->filled('tehsil')) {
                        $family->area = $request->input('tehsil');
                    }
                    $family->save();

                    // Sync family members from survey members
                    $family->members()->delete();
                    $fmToCreate = [];
                    foreach ($request->input('members', []) as $m) {
                        if (! is_array($m)) {
                            continue;
                        }
                        $nameVal = isset($m['name']) ? trim($m['name']) : '';
                        if ($nameVal === '') {
                            continue;
                        }
                    $fmToCreate[] = [
                        'organization_id' => $family->organization_id,
                        'name' => $nameVal,
                        'age' => isset($m['age']) && $m['age'] !== '' ? (int) $m['age'] : null,
                        'relation' => $m['relation'] ?? null,
                        'education' => $m['education'] ?? null,
                        'occupation' => $m['occupation'] ?? null,
                        'cnic' => $m['cnic'] ?? null,
                    ];
                    }
                    if (! empty($fmToCreate)) {
                        $family->members()->createMany($fmToCreate);
                    }
                    $family->update(['family_members_count' => count($fmToCreate)]);
                }
            }
            // If no family linked, create a new family with members and attach to survey
            else {
                $nextCode = 'FAM-'.str_pad((string) ((Family::max('id') ?? 0) + 1), 4, '0', STR_PAD_LEFT);

                // Determine organization for survey-created family
                $organizationId = optional($request->user())->organization_id ?? session('organization_id');

                $family = Family::create([
                    'family_code' => $nextCode,
                    'organization_id' => $organizationId,
                    'head_of_family_name' => $request->input('head_of_family', 'New Family'),
                    'cnic' => $request->input('survey_cnic'),
                    'family_members_count' => (int) ($request->input('household_size') ?? 0),
                    'gender' => $request->input('gender'),
                    'age' => $request->input('age') ? (int) $request->input('age') : null,
                    'marital_status' => $request->input('marital_status'),
                    'contact_number' => $request->input('contact_number'),
                    'zakat_eligibility' => ($request->input('zakat_elig') === 'yes'),
                    'monthly_income' => $request->input('monthly_income'),
                    'monthly_expense' => $request->input('monthly_expenses'),
                    'address' => $request->input('address_full'),
                    'province' => $request->input('province'),
                    'city' => $request->input('district'),
                    'area' => $request->input('tehsil'),
                    'volunteer_id' => $validated['enumerator_id'] ?? null,
                ]);

                $fmToCreate = [];
                foreach ($request->input('members', []) as $m) {
                    if (! is_array($m)) {
                        continue;
                    }
                    $nameVal = isset($m['name']) ? trim($m['name']) : '';
                    if ($nameVal === '') {
                        continue;
                    }
                    $fmToCreate[] = [
                        'organization_id' => $family->organization_id,
                        'name' => $nameVal,
                        'age' => isset($m['age']) && $m['age'] !== '' ? (int) $m['age'] : null,
                        'relation' => $m['relation'] ?? null,
                        'education' => $m['education'] ?? null,
                        'occupation' => $m['occupation'] ?? null,
                        'cnic' => $m['cnic'] ?? null,
                    ];
                }
                if (! empty($fmToCreate)) {
                    $family->members()->createMany($fmToCreate);
                }
                $family->update(['family_members_count' => count($fmToCreate)]);
                $survey->family_id = $family->id;
                $survey->save();
            }

            return redirect()->route('cms.survey.index')->with('success', 'Survey saved successfully.');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Surveys $survey)
    {
        $survey->load([
            'family',
            'enumerator',
            'members',
            'assets',
            'uploads',
            'needs',
            'outcomes',
        ])->loadCount(['members as members_count', 'uploads as uploads_count']);

        return view('survey.view-detail', compact('survey'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Surveys $survey)
    {
        $survey->load(['family', 'enumerator', 'members', 'assets', 'uploads']);
        $data['survey'] = $survey;
        $organizationId = optional(request()->user())->organization_id ?? session('organization_id');
        $data['volunteers'] = Volunteer::where('organization_id', $organizationId)->get();
        $data['families'] = Family::where('organization_id', $organizationId)->get();

        return view('survey.survey-assessment-edit-form')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Surveys $survey)
    {
        $validated = $request->validate([
            'enumerator_id' => ['nullable', 'integer'],
            'survey_mode' => ['nullable', 'in:In-person,Phone,WhatsApp,Web'],
            'datetime' => ['nullable', 'date'],
            'gps_lat' => ['nullable', 'numeric'],
            'gps_long' => ['nullable', 'numeric'],

            'consent_captured' => ['nullable'],
            'consent_type' => ['required_if:consent_captured,1,on,true', 'in:Text,Voice,Signature Photo'],
            'consent_attachment' => ['nullable', 'file', 'max:5120'],

            'revisit_needed' => ['nullable', 'in:yes,no'],
            'revisit_reason' => ['nullable', 'string', 'max:255'],
            'revisit_from' => ['nullable', 'date'],
            'revisit_to' => ['nullable', 'date', 'after_or_equal:revisit_from'],

            'photos' => ['nullable', 'array'],
            'photos.*' => ['file', 'mimes:jpg,jpeg,png', 'max:5120'],
            'audio_note' => ['nullable', 'file', 'mimetypes:audio/mpeg,audio/mp4,audio/wav,audio/x-wav,audio/ogg', 'max:20480'],

            // Preferences
            'pref_assistance' => ['nullable', 'array'],
            'program_pref' => ['nullable', 'array'],

            'family_id' => ['nullable'],
            'head_of_family' => ['nullable', 'string', 'max:255'],
            'survey_cnic' => ['nullable', 'regex:/^\d{5}-\d{7}-\d$/'],
            'household_size' => ['nullable', 'integer', 'min:1'],

            'members' => ['nullable', 'array'],
            'members.*.name' => ['nullable', 'string', 'max:255'],
            'members.*.age' => ['nullable', 'integer', 'min:0', 'max:120'],
            'members.*.gender' => ['nullable', 'in:Male,Female,Other'],
            'members.*.relation' => ['nullable', 'string', 'max:100'],
            'members.*.cnic' => ['nullable', 'regex:/^\d{5}-\d{7}-\d$/'],
            'members.*.disability' => ['nullable', 'string', 'max:100'],
            'members.*.school_work' => ['nullable', 'string', 'max:100'],

            'address_full' => ['nullable', 'string'],
            'province' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'tehsil' => ['nullable', 'string', 'max:100'],
            'uc' => ['nullable', 'string', 'max:100'],
            'settlement_type' => ['nullable', 'string', 'max:50'],
            'tenure_type' => ['nullable', 'string', 'max:50'],
            'displacement_status' => ['nullable', 'string', 'max:50'],
            'primary_languages' => ['nullable', 'array'],
            'primary_income' => ['nullable', 'string', 'max:100'],
            'monthly_income' => ['nullable', 'integer', 'min:0'],
            'monthly_expenses' => ['nullable', 'integer', 'min:0'],
            'debt_outstanding' => ['nullable', 'integer', 'min:0'],
            'debt_source' => ['nullable', 'string', 'max:50'],
            'assets' => ['nullable', 'array'],
            'banking_access' => ['nullable', 'string', 'max:50'],
            'digital_literacy' => ['nullable', 'string', 'max:50'],
            'female_led' => ['nullable', 'in:yes,no'],
            'pwd_count' => ['nullable', 'integer', 'min:0'],

            'meals_per_day' => ['nullable', 'integer', 'min:0'],
            'food_stocks' => ['nullable', 'integer', 'min:0'],
            'child_maln' => ['nullable', 'in:unknown,yes,no'],
            'food_needs' => ['nullable', 'array'],
            'water_source' => ['nullable', 'string', 'max:50'],
            'water_distance' => ['nullable', 'integer', 'min:0'],
            'water_quality' => ['nullable', 'in:yes,no'],

            'received_aid_6m' => ['nullable', 'in:yes,no'],
            'aid_details' => ['nullable', 'string', 'max:255'],

            'purpose_explained' => ['nullable'],
            'consent_statement' => ['nullable'],
        ]);

        return DB::transaction(function () use ($request, $validated, $survey) {
            // Update base survey fields
            $familyId = $request->input('family_id');
            $survey->family_id = ($familyId && $familyId !== 'new') ? (int) $familyId : null;
            $survey->enumerator_id = $validated['enumerator_id'] ?? null;
            $survey->survey_mode = $validated['survey_mode'] ?? null;
            if ($request->filled('datetime')) {
                try {
                    $survey->date_time = \Carbon\Carbon::createFromFormat('Y-m-d\\TH:i', $request->input('datetime'));
                } catch (\Exception $e) {
                }
            }
            $survey->lat = $validated['gps_lat'] ?? $survey->lat;
            $survey->lon = $validated['gps_long'] ?? $survey->lon;
            $survey->consent_type = ($request->boolean('consent_captured') ? ($validated['consent_type'] ?? 'Text') : null);
            $survey->revisit_flag = ($validated['revisit_needed'] ?? ($survey->revisit_flag ? 'yes' : 'no')) === 'yes';
            $survey->revisit_from = $validated['revisit_from'] ?? $survey->revisit_from;
            $survey->revisit_to = $validated['revisit_to'] ?? $survey->revisit_to;

            // Update flags
            $f = $survey->flags_json ?? [];
            $f['revisit_reason'] = $validated['revisit_reason'] ?? data_get($f, 'revisit_reason');
            $f['family_id'] = $survey->family_id;
            $f['head_of_family'] = $validated['head_of_family'] ?? data_get($f, 'head_of_family');
            $f['survey_cnic'] = $validated['survey_cnic'] ?? data_get($f, 'survey_cnic');
            $f['household_size'] = $validated['household_size'] ?? data_get($f, 'household_size');
            $f['address_full'] = $validated['address_full'] ?? data_get($f, 'address_full');
            $f['province'] = $validated['province'] ?? data_get($f, 'province');
            $f['district'] = $validated['district'] ?? data_get($f, 'district');
            $f['tehsil'] = $validated['tehsil'] ?? data_get($f, 'tehsil');
            $f['uc'] = $validated['uc'] ?? data_get($f, 'uc');
            $f['settlement_type'] = $validated['settlement_type'] ?? data_get($f, 'settlement_type');
            $f['tenure_type'] = $validated['tenure_type'] ?? data_get($f, 'tenure_type');
            $f['displacement_status'] = $validated['displacement_status'] ?? data_get($f, 'displacement_status');
            $f['primary_languages'] = $validated['primary_languages'] ?? data_get($f, 'primary_languages', []);
            $f['primary_income'] = $validated['primary_income'] ?? data_get($f, 'primary_income');
            $f['monthly_income'] = $validated['monthly_income'] ?? data_get($f, 'monthly_income');
            $f['monthly_expenses'] = $validated['monthly_expenses'] ?? data_get($f, 'monthly_expenses');
            $f['debt_outstanding'] = $validated['debt_outstanding'] ?? data_get($f, 'debt_outstanding');
            $f['debt_source'] = $validated['debt_source'] ?? data_get($f, 'debt_source');
            $f['banking_access'] = $validated['banking_access'] ?? data_get($f, 'banking_access');
            $f['digital_literacy'] = $validated['digital_literacy'] ?? data_get($f, 'digital_literacy');
            $f['female_led'] = $validated['female_led'] ?? data_get($f, 'female_led', 'no');
            $f['pwd_count'] = $validated['pwd_count'] ?? data_get($f, 'pwd_count', 0);
            $f['meals_per_day'] = $validated['meals_per_day'] ?? data_get($f, 'meals_per_day');
            $f['food_stocks'] = $validated['food_stocks'] ?? data_get($f, 'food_stocks');
            $f['child_maln'] = $validated['child_maln'] ?? data_get($f, 'child_maln', 'unknown');
            $f['food_needs'] = $validated['food_needs'] ?? data_get($f, 'food_needs', []);
            $f['water_source'] = $validated['water_source'] ?? data_get($f, 'water_source');
            $f['water_distance'] = $validated['water_distance'] ?? data_get($f, 'water_distance');
            $f['water_quality'] = $validated['water_quality'] ?? data_get($f, 'water_quality', 'no');
            $f['received_aid_6m'] = $validated['received_aid_6m'] ?? data_get($f, 'received_aid_6m', 'no');
            $f['aid_details'] = $validated['aid_details'] ?? data_get($f, 'aid_details');

            // Preferences (only overwrite if field present, so multi-select updates work)
            if ($request->has('pref_assistance')) {
                $f['pref_assistance'] = (array) $request->input('pref_assistance', []);
            }
            if ($request->has('program_pref')) {
                $f['program_pref'] = (array) $request->input('program_pref', []);
            }
            $f['purpose_explained'] = $request->boolean('purpose_explained');
            $f['consent_statement'] = $request->boolean('consent_statement');
            $survey->flags_json = $f;
            $survey->save();

            // Optional uploads
            if ($request->hasFile('consent_attachment')) {
                $file = $request->file('consent_attachment');
                if ($file->isValid()) {
                    $path = $file->store('survey/consent', 'public');
                    SurveyUpload::create(['organization_id' => $survey->organization_id, 'survey_id' => $survey->id, 'type' => 'consent', 'url' => $path]);
                }
            }
            foreach ($request->file('photos', []) as $p) {
                if ($p && $p->isValid()) {
                    $path = $p->store('survey/photos', 'public');
                    SurveyUpload::create(['organization_id' => $survey->organization_id, 'survey_id' => $survey->id, 'type' => 'photo', 'url' => $path]);
                }
            }
            if ($request->hasFile('audio_note')) {
                $a = $request->file('audio_note');
                if ($a->isValid()) {
                    $path = $a->store('survey/audio', 'public');
                    SurveyUpload::create(['organization_id' => $survey->organization_id, 'survey_id' => $survey->id, 'type' => 'audio', 'url' => $path]);
                }
            }

            // Resync members: simple strategy - delete and recreate from form
            $survey->members()->delete();
            $members = $request->input('members', []);
            if (is_array($members)) {
                foreach ($members as $m) {
                    if (! is_array($m)) {
                        continue;
                    }
                    $nameVal = isset($m['name']) ? trim($m['name']) : '';
                    if ($nameVal === '') {
                        continue;
                    }
                    $ageVal = isset($m['age']) ? (int) $m['age'] : null;
                    if ($ageVal !== null && ($ageVal < 0 || $ageVal > 120)) {
                        $ageVal = null;
                    }
                    SurveyMember::create([
                        'organization_id' => $survey->organization_id,
                        'survey_id' => $survey->id,
                        'person_id' => null,
                        'name' => $nameVal,
                        'age' => $ageVal,
                        'gender' => $m['gender'] ?? null,
                        'relation' => $m['relation'] ?? null,
                        'cnic' => $m['cnic'] ?? null,
                        'school_work' => $m['school_work'] ?? null,
                        'disability' => $m['disability'] ?? null,
                    ]);
                }
            }

            // Resync assets
            $survey->assets()->delete();
            foreach ($request->input('assets', []) as $asset) {
                if (! $asset) {
                    continue;
                }
                SurveyAsset::create(['organization_id' => $survey->organization_id, 'survey_id' => $survey->id, 'asset' => $asset, 'present' => true]);
            }

            // If linked to a family, sync family core fields and members
            if ($survey->family_id) {
                $family = Family::find($survey->family_id);
                if ($family) {
                    $family->zakat_eligibility = ($request->input('zakat_elig') === 'yes');
                    if ($request->filled('monthly_income')) {
                        $family->monthly_income = (int) $request->input('monthly_income');
                    }
                    if ($request->filled('monthly_expenses')) {
                        $family->monthly_expense = (int) $request->input('monthly_expenses');
                    }
                    if ($request->filled('address_full')) {
                        $family->address = $request->input('address_full');
                    }
                    if ($request->filled('province')) {
                        $family->province = $request->input('province');
                    }
                    if ($request->filled('district')) {
                        $family->city = $request->input('district');
                    }
                    if ($request->filled('tehsil')) {
                        $family->area = $request->input('tehsil');
                    }
                    $family->save();

                    // Sync family members from survey members
                    $family->members()->delete();
                    $fmToCreate = [];
                    foreach ($request->input('members', []) as $m) {
                        if (! is_array($m)) {
                            continue;
                        }
                        $nameVal = isset($m['name']) ? trim($m['name']) : '';
                        if ($nameVal === '') {
                            continue;
                        }
                    $fmToCreate[] = [
                        'organization_id' => $family->organization_id,
                        'name' => $nameVal,
                        'age' => isset($m['age']) && $m['age'] !== '' ? (int) $m['age'] : null,
                        'relation' => $m['relation'] ?? null,
                        'education' => $m['education'] ?? null,
                        'occupation' => $m['occupation'] ?? null,
                        'cnic' => $m['cnic'] ?? null,
                    ];
                }
                if (! empty($fmToCreate)) {
                    $family->members()->createMany($fmToCreate);
                }
                    $family->update(['family_members_count' => count($fmToCreate)]);
                }
            }

            return redirect()->route('cms.survey.index', $survey->id)->with('success','Survey updated successfully.');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Surveys $surveys)
    {
        //
    }
}
