<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Surveys;
use App\Models\Volunteer;
use App\Models\SurveyAsset;
use Illuminate\Support\Str;
use App\Models\SurveyMember;
use App\Models\SurveyUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SurveysController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('survey.survey-list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['volunteers'] = Volunteer::get();
        $data['families'] = Family::get();

        return view('survey.survey-assessment-form')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'enumerator_id' => ['nullable','integer'],
            'survey_mode' => ['nullable','in:In-person,Phone,WhatsApp,Web'],
            'datetime' => ['nullable','date'],
            'gps_lat' => ['nullable','numeric'],
            'gps_long' => ['nullable','numeric'],
            'consent_captured' => ['nullable'],
            'consent_type' => ['nullable','in:Text,Voice,Signature Photo'],
            'consent_attachment' => ['nullable','file','max:5120'],
            'revisit_needed' => ['nullable','in:yes,no'],
            'revisit_reason' => ['nullable','string','max:255'],
            'revisit_from' => ['nullable','date'],
            'revisit_to' => ['nullable','date','after_or_equal:revisit_from'],
            'photos' => ['required'],
            'photos.*' => ['file','mimes:jpg,jpeg,png','max:5120'],
            'audio_note' => ['nullable','file','mimetypes:audio/mpeg,audio/mp4,audio/wav,audio/x-wav,audio/ogg','max:20480'],
            'family_id' => ['nullable'],
            'head_of_family' => ['nullable','string','max:255'],
            'survey_cnic' => ['nullable','string','max:20'],
            'household_size' => ['nullable','integer','min:1'],
            'members' => ['nullable','array'],
            'address_full' => ['nullable','string'],
            'province' => ['nullable','string','max:100'],
            'district' => ['nullable','string','max:100'],
            'tehsil' => ['nullable','string','max:100'],
            'uc' => ['nullable','string','max:100'],
            'settlement_type' => ['nullable','string','max:50'],
            'tenure_type' => ['nullable','string','max:50'],
            'displacement_status' => ['nullable','string','max:50'],
            'primary_languages' => ['nullable','array'],
            'primary_income' => ['nullable','string','max:100'],
            'monthly_income' => ['nullable','integer','min:0'],
            'monthly_expenses' => ['nullable','integer','min:0'],
            'debt_outstanding' => ['nullable','integer','min:0'],
            'debt_source' => ['nullable','string','max:50'],
            'assets' => ['nullable','array'],
            'banking_access' => ['nullable','string','max:50'],
            'digital_literacy' => ['nullable','string','max:50'],
            'female_led' => ['nullable','in:yes,no'],
            'pwd_count' => ['nullable','integer','min:0'],
            'meals_per_day' => ['nullable','integer','min:0'],
            'food_stocks' => ['nullable','integer','min:0'],
            'child_maln' => ['nullable','in:unknown,yes,no'],
            'food_needs' => ['nullable','array'],
            'water_source' => ['nullable','string','max:50'],
            'water_distance' => ['nullable','integer','min:0'],
            'water_quality' => ['nullable','in:yes,no'],
            'received_aid_6m' => ['nullable','in:yes,no'],
            'aid_details' => ['nullable','string','max:255'],
            'purpose_explained' => ['nullable'],
            'consent_statement' => ['nullable'],
        ]);

        return DB::transaction(function () use ($request, $validated) {
            // Create Survey
            $survey = new Surveys();
            $survey->survey_code = 'SVY-' . strtoupper(Str::random(6));
            // Set family_id if provided (and not the "new" placeholder)
            $familyId = $request->input('family_id');
            if ($familyId && $familyId !== 'new') {
                $survey->family_id = (int) $familyId;
            } else {
                $survey->family_id = null;
            }
            $survey->enumerator_id = $validated['enumerator_id'] ?? null;
            $survey->survey_mode = $validated['survey_mode'] ?? null;
            $survey->ts = $validated['datetime'] ?? null;
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
                    if (!is_array($m)) continue;
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
                    if ($allEmpty) continue;
                    // Require at least a name to consider this a valid member row
                    $nameVal = isset($m['name']) ? trim($m['name']) : '';
                    if ($nameVal === '') continue;

                    // Normalize age to avoid DB out-of-range errors (tinyint). If not in 0-120, set null
                    $ageVal = isset($m['age']) ? (int) $m['age'] : null;
                    if ($ageVal !== null && ($ageVal < 0 || $ageVal > 120)) {
                        $ageVal = null;
                    }

                    SurveyMember::create([
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

            // Assets
            $assets = $request->input('assets', []);
            if (is_array($assets)) {
                foreach ($assets as $asset) {
                    if (!$asset) continue;
                    SurveyAsset::create([
                        'survey_id' => $survey->id,
                        'asset' => $asset,
                        'present' => true,
                    ]);
                }
            }

            return redirect()->route('cms.survey.index')->with('success', 'Survey saved successfully.');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Surveys $surveys)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Surveys $surveys)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Surveys $surveys)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Surveys $surveys)
    {
        //
    }
}
