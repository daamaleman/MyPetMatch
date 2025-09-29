<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $isOrganization = (bool) $request->boolean('is_organization');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['nullable', 'in:adoptante,organizacion'], // admin no se expone en registro público
            // Campos de organización (condicionales)
            'org_name' => [$isOrganization ? 'required' : 'nullable', 'string', 'max:255'],
            'org_email' => ['nullable', 'string', 'email', 'max:255'],
            'org_phone' => ['nullable', 'string', 'max:50'],
            'org_city' => ['nullable', 'string', 'max:120'],
            'org_state' => ['nullable', 'string', 'max:120'],
            'org_country' => ['nullable', 'string', 'max:120'],
            'org_description' => ['nullable', 'string'],
        ]);

        $role = $isOrganization
            ? 'organizacion'
            : (in_array($request->role, ['adoptante','organizacion']) ? $request->role : 'adoptante');

        $user = null;

        DB::transaction(function () use ($request, $role, $isOrganization, &$user) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $role,
            ]);

            if ($isOrganization) {
                $org = Organization::create([
                    'name' => $request->input('org_name'),
                    'email' => $request->input('org_email') ?: null,
                    'phone' => $request->input('org_phone') ?: null,
                    'city' => $request->input('org_city') ?: null,
                    'state' => $request->input('org_state') ?: null,
                    'country' => $request->input('org_country') ?: null,
                    'description' => $request->input('org_description') ?: null,
                ]);

                $user->organization()->associate($org);
                $user->save();
            }
        });

        event(new Registered($user));

        Auth::login($user);

        $target = match($user->role ?? null) {
            'organizacion' => route('orgs.dashboard'),
            'adoptante' => route('adoptions.dashboard'),
            'admin' => route('orgs.dashboard'),
            default => RouteServiceProvider::HOME,
        };

        return redirect($target);
    }
}
