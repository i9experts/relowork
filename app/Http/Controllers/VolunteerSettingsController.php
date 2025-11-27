<?php

namespace App\Http\Controllers;

use App\Models\EducationLevel;
use App\Models\Availability;
use App\Models\ProjectType;
use App\Models\VolunteerRole;
use App\Models\Language;
use Illuminate\Http\Request;

class VolunteerSettingsController extends Controller
{
    public function index()
    {
        $educationLevels = EducationLevel::orderBy('name')->get();
        $availabilities  = Availability::orderBy('name')->get();
        $projectTypes    = ProjectType::orderBy('name')->get();
        $volunteerRoles  = VolunteerRole::orderBy('name')->get();
        $languages       = Language::orderBy('name')->get();

        return view('volunteer.volunteer-settings', compact(
            'educationLevels',
            'availabilities',
            'projectTypes',
            'volunteerRoles',
            'languages'
        ));
    }

    protected function currentOrganizationId(Request $request): ?int
    {
        return optional($request->user())->organization_id ?? session('organization_id');
    }

    public function storeEducationLevel(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
        ]);
        $data['organization_id'] = $this->currentOrganizationId($request);
        EducationLevel::create($data);
        return back()->with('success', 'Education level added.');
    }

    public function storeAvailability(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
        ]);
        $data['organization_id'] = $this->currentOrganizationId($request);
        Availability::create($data);
        return back()->with('success', 'Availability option added.');
    }

    public function storeProjectType(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
        ]);
        $payload = [
            'name' => $data['name'],
            'organization_id' => $this->currentOrganizationId($request),
        ];
        ProjectType::create($payload);
        return back()->with('success', 'Project type added.');
    }

    public function storeVolunteerRole(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
        ]);
        $data['organization_id'] = $this->currentOrganizationId($request);
        VolunteerRole::create($data);
        return back()->with('success', 'Volunteer role added.');
    }

    
}
