<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Organization;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update or create the user's organization profile (name only for now).
     */
    public function updateOrganization(Request $request): RedirectResponse
    {
        $user = $request->user();
        if (!in_array($user->role, ['organizacion','admin'], true)) {
            abort(403);
        }

        $data = $request->validate([
            'organization_name' => ['required','string','max:255'],
            'organization_description' => ['nullable','string'],
            'organization_email' => ['nullable','email','max:255'],
            'organization_phone' => ['nullable','string','max:50'],
            'organization_city' => ['nullable','string','max:120'],
            'organization_state' => ['nullable','string','max:120'],
            'organization_country' => ['nullable','string','max:120'],
        ]);

        $orgData = [
            'name' => $data['organization_name'],
            'description' => $data['organization_description'] ?? null,
            'email' => $data['organization_email'] ?? null,
            'phone' => $data['organization_phone'] ?? null,
            'city' => $data['organization_city'] ?? null,
            'state' => $data['organization_state'] ?? null,
            'country' => $data['organization_country'] ?? null,
        ];

        if ($user->organization) {
            $user->organization->update($orgData);
        } else {
            $org = Organization::create($orgData);
            $user->organization_id = $org->id;
            $user->save();
        }

        return Redirect::route('profile.edit')->with('status', 'organization-updated');
    }

    /**
     * Update adopter profile fields for adoptante users
     */
    public function updateAdopter(Request $request): RedirectResponse
    {
        $user = $request->user();
        if (!in_array($user->role, ['adoptante','admin'], true)) {
            abort(403);
        }

        $data = $request->validate([
            'phone' => ['nullable','string','max:50'],
            'address_line1' => ['nullable','string','max:255'],
            'address_line2' => ['nullable','string','max:255'],
            'city' => ['nullable','string','max:120'],
            'state' => ['nullable','string','max:120'],
            'country' => ['nullable','string','max:120'],
            'zip' => ['nullable','string','max:20'],
        ]);

        $profile = $user->adopterProfile;
        if ($profile) {
            $profile->update($data);
        } else {
            $user->adopterProfile()->create($data);
        }

        return Redirect::route('profile.edit')->with('status', 'adopter-updated');
    }
}
