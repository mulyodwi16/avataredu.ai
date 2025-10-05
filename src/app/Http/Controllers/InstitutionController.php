<?php

namespace App\Http\Controllers;

use App\Services\InstitutionService;
use App\Services\UserService;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstitutionController extends Controller
{
    protected InstitutionService $institutionService;
    protected UserService $userService;

    public function __construct(InstitutionService $institutionService, UserService $userService)
    {
        $this->institutionService = $institutionService;
        $this->userService = $userService;
    }

    /**
     * Display institution learning page
     */
    public function index(Request $request): View
    {
        $user = auth()->user();

        // Get user's institution data
        $institutionData = $this->userService->getInstitutionData($user);

        // Get all available institutions for potential enrollment
        $availableInstitutions = $this->institutionService->getActiveInstitutions();

        return view('pages.institution', compact(
            'user',
            'institutionData',
            'availableInstitutions'
        ));
    }

    /**
     * Display specific institution details
     */
    public function show(Institution $institution): View
    {
        $institutionDetails = $this->institutionService->getInstitutionDetails($institution);
        $statistics = $this->institutionService->getInstitutionStatistics($institution);

        return view('pages.institutions.show', compact(
            'institution',
            'institutionDetails',
            'statistics'
        ));
    }

    /**
     * Search institutions
     */
    public function search(Request $request): View
    {
        $query = $request->get('query', '');
        $filters = $request->only(['type', 'country', 'per_page']);

        $institutions = $this->institutionService->searchInstitutions($query, $filters);

        return view('pages.institutions.search', compact('institutions', 'query', 'filters'));
    }
}