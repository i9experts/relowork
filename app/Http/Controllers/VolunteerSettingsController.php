<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\EducationLevel;
use App\Models\Language;
use App\Models\ProjectType;
use App\Models\VolunteerRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class VolunteerSettingsController extends Controller
{
    public function index(Request $request)
    {
        $organizationId = $this->currentOrganizationId($request);
        $educationLevels = EducationLevel::visibleTo($organizationId)->orderBy('name')->get();
        $availabilities = Availability::visibleTo($organizationId)->orderBy('name')->get();
        $projectTypes = ProjectType::visibleTo($organizationId)->orderBy('name')->get();
        $volunteerRoles = VolunteerRole::visibleTo($organizationId)->orderBy('name')->get();
        $languages = Language::orderBy('name')->get();

        return view('volunteer.volunteer-settings', compact(
            'educationLevels',
            'availabilities',
            'projectTypes',
            'volunteerRoles',
            'languages'
        ));
    }

    /**
     * Shared rows (organization_id = null) are used by every organization, so
     * only a super admin may rename or delete them. Rows owned by another
     * organization are already rejected by EnsureModelBelongsToTenant.
     */
    protected function ensureCanModify(Model $option): void
    {
        $user = request()->user();
        if ($option->organization_id === null && ! ($user && $user->isSystemAdmin())) {
            abort(403, 'Shared options can only be changed by a super admin.');
        }
    }

    protected function currentOrganizationId(Request $request): ?int
    {
        return optional($request->user())->organization_id ?? session('organization_id');
    }

    public function storeEducationLevel(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        $data['organization_id'] = $this->currentOrganizationId($request);
        EducationLevel::create($data);

        return back()->with('success', 'Education level added.');
    }

    public function storeAvailability(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        $data['organization_id'] = $this->currentOrganizationId($request);
        Availability::create($data);

        return back()->with('success', 'Availability option added.');
    }

    public function storeProjectType(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
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
            'name' => ['required', 'string', 'max:255'],
        ]);
        $data['organization_id'] = $this->currentOrganizationId($request);
        VolunteerRole::create($data);

        return back()->with('success', 'Volunteer role added.');
    }

    public function updateEducationLevel(Request $request, EducationLevel $educationLevel)
    {
        $this->ensureCanModify($educationLevel);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        $educationLevel->update(['name' => $data['name']]);
        return back()->with('success', 'Education level updated.');
    }

    public function destroyEducationLevel(EducationLevel $educationLevel)
    {
        $this->ensureCanModify($educationLevel);
        $educationLevel->delete();
        return back()->with('success', 'Education level deleted.');
    }

    public function updateAvailability(Request $request, Availability $availability)
    {
        $this->ensureCanModify($availability);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        $availability->update(['name' => $data['name']]);
        return back()->with('success', 'Availability updated.');
    }

    public function destroyAvailability(Availability $availability)
    {
        $this->ensureCanModify($availability);
        $availability->delete();
        return back()->with('success', 'Availability deleted.');
    }

    public function updateProjectType(Request $request, ProjectType $projectType)
    {
        $this->ensureCanModify($projectType);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        $projectType->update(['name' => $data['name']]);
        return back()->with('success', 'Project type updated.');
    }

    public function destroyProjectType(ProjectType $projectType)
    {
        $this->ensureCanModify($projectType);
        $projectType->delete();
        return back()->with('success', 'Project type deleted.');
    }

    public function updateVolunteerRole(Request $request, VolunteerRole $volunteerRole)
    {
        $this->ensureCanModify($volunteerRole);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        $volunteerRole->update(['name' => $data['name']]);
        return back()->with('success', 'Volunteer role updated.');
    }

    public function destroyVolunteerRole(VolunteerRole $volunteerRole)
    {
        $this->ensureCanModify($volunteerRole);
        $volunteerRole->delete();
        return back()->with('success', 'Volunteer role deleted.');
    }
}
