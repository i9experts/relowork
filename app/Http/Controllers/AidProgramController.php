<?php

namespace App\Http\Controllers;

use App\Models\AidProgram;
use App\Models\Family;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AidProgramController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:aid_programs_list,aid_programs_view,aid_programs_create,aid_programs_edit,aid_programs_delete')->only(['index']);
        $this->middleware('permission:aid_programs_view')->only(['show']);
        $this->middleware('permission:aid_programs_create')->only(['create','store']);
        $this->middleware('permission:aid_programs_edit')->only(['edit','update']);
        $this->middleware('permission:aid_programs_delete')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organizationId = optional(request()->user())->organization_id ?? session('organization_id');

        $status = request('status');
        $query = AidProgram::where('organization_id', $organizationId)
            ->withCount(['families', 'volunteers', 'attachments'])
            ->withSum(['transactions as disbursed_amount' => function ($q) {
                $q->where('transaction_type', 'Disbursement');
            }], 'amount');

        if ($status) {
            $query->where('status', $status);
        }

        $data['programs'] = $query->orderBy('id', 'desc')->get();
        $data['active_programs'] = AidProgram::where('organization_id', $organizationId)
            ->where('status', 'Active')->count();
        $data['total_budget_active'] = AidProgram::where('organization_id', $organizationId)
            ->where('status', 'Active')->sum('total_budget');
        $data['beneficiaries_total'] = AidProgram::where('organization_id', $organizationId)
            ->sum('target_beneficiaries');
        $data['current_status'] = $status;

        return view('aid-program.aid-program-list')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $organizationId = optional(request()->user())->organization_id ?? session('organization_id');
        $data['volunteers'] = Volunteer::where('organization_id', $organizationId)->get();
        $data['volunteersCount'] = $data['volunteers']->count();
        $data['families'] = Family::select('id', 'family_code', 'head_of_family_name')
            ->where('organization_id', $organizationId)
            ->orderBy('id', 'desc')
            ->get();

        return view('aid-program.aid_program_form')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $organizationId = optional($request->user())->organization_id ?? session('organization_id');
        $validated = $request->validate([
            'program_title' => ['required', 'string', 'max:255'],
            'program_type' => ['nullable', 'in:Food,Education,Water,Medical,Housing,Emergency'],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'location' => ['nullable', 'string', 'max:255'],
            'target_beneficiaries' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'in:Planned,Active,Completed,On Hold'],
            'total_budget' => ['nullable', 'integer', 'min:0'],
            'fund_source' => ['nullable', 'in:Zakat,Sadaqah,General Donation,Grant'],
            'monitoring_frequency' => ['nullable', 'in:Weekly,Monthly,Quarterly'],
            'progress_notes' => ['nullable', 'string'],
            'volunteer_id' => ['required', Rule::exists('volunteers', 'id')->where(function ($q) use ($organizationId) {
                $q->where('organization_id', $organizationId);
            })],
            'families' => ['required', 'array', 'min:1'],
            'families.*' => [Rule::exists('families', 'id')->where(function ($q) use ($organizationId) {
                $q->where('organization_id', $organizationId);
            })],
            'attachments.*' => ['file', 'max:5120'],
        ]);

        $programCode = $this->nextProgramCode($organizationId);

        DB::transaction(function () use ($validated, $request, $programCode, $organizationId) {
            $program = AidProgram::create([
                'program_code' => $programCode,
                'organization_id' => $organizationId,
                'program_title' => $validated['program_title'],
                'program_type' => $validated['program_type'] ?? null,
                'description' => $validated['description'] ?? null,
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'location' => $validated['location'] ?? null,
                'target_beneficiaries' => $validated['target_beneficiaries'] ?? null,
                'status' => $validated['status'] ?? 'Planned',
                'total_budget' => $validated['total_budget'] ?? null,
                'fund_source' => $validated['fund_source'] ?? null,
                'monitoring_frequency' => $validated['monitoring_frequency'] ?? null,
                'progress_notes' => $validated['progress_notes'] ?? null,
            ]);

            if (! empty($validated['volunteer_id'])) {
                $program->volunteers()->syncWithoutDetaching([
                    $validated['volunteer_id'] => ['organization_id' => $organizationId],
                ]);
            }

            if (! empty($validated['families'])) {
                $familiesWithPivot = collect($validated['families'])
                    ->mapWithKeys(function ($id) use ($organizationId) {
                        return [$id => ['organization_id' => $organizationId]];
                    })
                    ->all();
                $program->families()->sync($familiesWithPivot);
            }

            // handle attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    if (! $file->isValid()) {
                        continue;
                    }
                    $path = $file->store('aid_attachments', 'public');
                    $program->attachments()->create([
                        'organization_id' => $organizationId,
                        'type' => $file->getClientMimeType(),
                        'path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                    ]);
                }
            }
        });

        return redirect()->route('cms.aid-program.index')->with('success', 'Aid program created successfully!');
    }

    protected function nextProgramCode(int $organizationId): string
    {
        $max = DB::table('aid_programs')
            ->where('organization_id', $organizationId)
            ->whereNotNull('program_code')
            ->selectRaw('MAX(CAST(SUBSTR(program_code, INSTR(program_code, "-") + 1) AS INTEGER)) as max_num')
            ->lockForUpdate()
            ->value('max_num');
        $next = ((int) ($max ?? 0)) + 1;
        return 'PRG-'.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Display the specified resource.
     */
    public function show(AidProgram $aidProgram)
    {
        $aidProgram->loadCount(['families', 'volunteers', 'attachments'])
            ->load(['families:id,family_code,head_of_family_name', 'volunteers:id,full_name', 'attachments']);

        $aidProgram->loadSum(['transactions as disbursed_amount' => function ($q) {
            $q->where('transaction_type', 'Disbursement');
        }], 'amount');

        return view('aid-program.aid-program-show', compact('aidProgram'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AidProgram $aidProgram)
    {
        $organizationId = optional(request()->user())->organization_id ?? session('organization_id');
        $volunteers = Volunteer::where('organization_id', $organizationId)->get();
        $families = Family::select('id', 'family_code', 'head_of_family_name')
            ->where('organization_id', $organizationId)
            ->orderBy('id', 'desc')
            ->get();
        // Preload relationships used by the form for defaults
        $aidProgram->load(['families:id', 'volunteers:id']);

        return view('aid-program.aid_program_edit-form', compact('aidProgram', 'volunteers', 'families'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AidProgram $aidProgram)
    {
        $organizationId = optional($request->user())->organization_id ?? session('organization_id');
        $validated = $request->validate([
            'program_title' => ['required', 'string', 'max:255'],
            'program_type' => ['nullable', 'in:Food,Education,Water,Medical,Housing,Emergency'],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'location' => ['nullable', 'string', 'max:255'],
            'target_beneficiaries' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'in:Planned,Active,Completed,On Hold'],
            'total_budget' => ['nullable', 'integer', 'min:0'],
            'fund_source' => ['nullable', 'in:Zakat,Sadaqah,General Donation,Grant'],
            'monitoring_frequency' => ['nullable', 'in:Weekly,Monthly,Quarterly'],
            'progress_notes' => ['nullable', 'string'],
            'volunteer_id' => ['required', Rule::exists('volunteers', 'id')->where(function ($q) use ($organizationId) {
                $q->where('organization_id', $organizationId);
            })],
            'families' => ['required', 'array', 'min:1'],
            'families.*' => [Rule::exists('families', 'id')->where(function ($q) use ($organizationId) {
                $q->where('organization_id', $organizationId);
            })],
            'attachments.*' => ['file', 'max:5120'],
        ]);

        DB::transaction(function () use ($validated, $request, $aidProgram, $organizationId) {
            $aidProgram->update([
                'program_title' => $validated['program_title'],
                'program_type' => $validated['program_type'] ?? null,
                'description' => $validated['description'] ?? null,
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'location' => $validated['location'] ?? null,
                'target_beneficiaries' => $validated['target_beneficiaries'] ?? null,
                'status' => $validated['status'] ?? $aidProgram->status,
                'total_budget' => $validated['total_budget'] ?? null,
                'fund_source' => $validated['fund_source'] ?? null,
                'monitoring_frequency' => $validated['monitoring_frequency'] ?? null,
                'progress_notes' => $validated['progress_notes'] ?? null,
                'volunteer_id' => $validated['volunteer_id'] ?? null,
            ]);

            if (! empty($validated['volunteer_id'])) {
                $aidProgram->volunteers()->syncWithoutDetaching([
                    $validated['volunteer_id'] => ['organization_id' => $organizationId],
                ]);
            }

            if (array_key_exists('families', $validated)) {
                $families = $validated['families'] ?? [];
                $familiesWithPivot = collect($families)
                    ->mapWithKeys(function ($id) use ($organizationId) {
                        return [$id => ['organization_id' => $organizationId]];
                    })
                    ->all();
                $aidProgram->families()->sync($familiesWithPivot);
            }

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    if (! $file->isValid()) {
                        continue;
                    }
                    $path = $file->store('aid_attachments', 'public');
                    $aidProgram->attachments()->create([
                        'organization_id' => $organizationId,
                        'type' => $file->getClientMimeType(),
                        'path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                    ]);
                }
            }
        });

        return redirect()->route('cms.aid-program.show', $aidProgram->id)->with('success', 'Aid program updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AidProgram $aidProgram)
    {
        DB::transaction(function () use ($aidProgram) {
            // Detach relationships
            if (method_exists($aidProgram, 'families')) {
                $aidProgram->families()->detach();
            }
            if (method_exists($aidProgram, 'volunteers')) {
                $aidProgram->volunteers()->detach();
            }
            // Delete attachments (including files if present)
            if (method_exists($aidProgram, 'attachments')) {
                foreach ($aidProgram->attachments as $att) {
                    if (! empty($att->path)) {
                        $p = $att->path;
                        if (function_exists('str_starts_with') && str_starts_with($p, 'public/')) {
                            $p = substr($p, 7);
                        }
                        Storage::disk('public')->delete($p);
                        // fallback in case older records used full 'public/...'
                        Storage::delete($att->path);
                    }
                    $att->delete();
                }
            }
            $aidProgram->delete();
        });

        return redirect()->route('cms.aid-program.index')->with('warning', 'Aid program deleted successfully.');
    }
}
