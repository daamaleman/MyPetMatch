<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class OrganizationDirectoryController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $city = $request->string('city')->toString();
        $state = $request->string('state')->toString();
        $country = $request->string('country')->toString();

        $orgsQuery = Organization::query()->withCount('pets');
        if ($q !== '') {
            $orgsQuery->where('name', 'like', "%{$q}%");
        }
        if ($city !== '' && Schema::hasColumn('organizations', 'city')) {
            $orgsQuery->where('city', 'like', "%{$city}%");
        }
        if ($state !== '' && Schema::hasColumn('organizations', 'state')) {
            $orgsQuery->where('state', 'like', "%{$state}%");
        }
        if ($country !== '' && Schema::hasColumn('organizations', 'country')) {
            $orgsQuery->where('country', 'like', "%{$country}%");
        }
        $orgs = $orgsQuery->latest('id')->paginate(12);
        $orgs->appends($request->query());

        return view('orgs.index', compact('orgs', 'q', 'city', 'state', 'country'));
    }

    public function show(Organization $organization)
    {
    $organization->loadCount('pets')->load(['pets' => function($q){ $q->latest()->limit(6); }]);
        return view('orgs.details', ['org' => $organization]);
    }
}
