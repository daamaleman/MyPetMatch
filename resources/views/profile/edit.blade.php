<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mi Perfil — {{ config('app.name', 'MyPetMatch') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light dark">
    <style>html{scroll-behavior:smooth}</style>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body class="font-poppins bg-neutral-light text-neutral-dark dark:bg-neutral-dark dark:text-neutral-white">
    @include('partials.header')

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold">Mi Perfil</h1>
                <p class="mt-1 text-sm text-neutral-dark/70">Actualiza tu información de cuenta y preferencias.</p>
            </div>
            <div>
                @if (session('status'))
                    <span class="inline-block rounded-xl border border-neutral-mid/40 bg-white dark:bg-neutral-dark px-3 py-2 text-sm">{{ session('status') }}</span>
                @endif
            </div>
        </div>

        @php $role = auth()->user()->role ?? null; @endphp

        <div class="mt-6 grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Columna principal -->
            <div class="xl:col-span-2 space-y-6">
                <!-- Info de cuenta -->
                <div class="rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-6">
                    <h2 class="text-lg font-semibold">Información de cuenta</h2>
                    <p class="text-sm text-neutral-dark/70">Nombre y correo electrónico.</p>
                    <div class="mt-4 max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Seguridad -->
                <div class="rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-6">
                    <h2 class="text-lg font-semibold">Seguridad</h2>
                    <p class="text-sm text-neutral-dark/70">Actualiza tu contraseña periódicamente.</p>
                    <div class="mt-4 max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Adoptante: datos de contacto y dirección -->
                @if(in_array($role, ['adoptante','admin']))
                <div class="rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-6">
                    <h2 class="text-lg font-semibold">Datos de Adoptante</h2>
                    <p class="text-sm text-neutral-dark/70">Estos datos ayudarán a las organizaciones a contactarte.</p>
                    @php $ap = optional(auth()->user()->adopterProfile); @endphp
                    <form class="mt-4 max-w-xl" method="POST" action="{{ route('profile.adopter.update') }}">
                        @csrf
                        @method('PATCH')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm" for="phone">Teléfono</label>
                                <input id="phone" name="phone" type="text" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('phone', $ap->phone) }}">
                                @error('phone')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="text-sm" for="zip">Código Postal</label>
                                <input id="zip" name="zip" type="text" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('zip', $ap->zip) }}">
                                @error('zip')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-sm" for="address_line1">Dirección</label>
                                <input id="address_line1" name="address_line1" type="text" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('address_line1', $ap->address_line1) }}">
                                @error('address_line1')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-sm" for="address_line2">Referencia (opcional)</label>
                                <input id="address_line2" name="address_line2" type="text" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('address_line2', $ap->address_line2) }}">
                                @error('address_line2')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="text-sm" for="city">Ciudad</label>
                                <input id="city" name="city" type="text" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('city', $ap->city) }}">
                                @error('city')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="text-sm" for="state">Estado/Provincia</label>
                                <input id="state" name="state" type="text" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('state', $ap->state) }}">
                                @error('state')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-sm" for="country">País</label>
                                <input id="country" name="country" type="text" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('country', $ap->country) }}">
                                @error('country')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="mt-4">
                            <button class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
                @endif

                <!-- Organización: perfil extendido -->
                @if(in_array($role, ['organizacion','admin']))
                <div class="rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-6">
                    <h2 class="text-lg font-semibold">Perfil de Organización</h2>
                    <p class="text-sm text-neutral-dark/70">Datos públicos de tu organización que verán los adoptantes.</p>
                    @php $org = optional(auth()->user()->organization); @endphp
                    <form class="mt-4 max-w-2xl" method="POST" action="{{ route('profile.organization.update') }}">
                        @csrf
                        @method('PATCH')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="text-sm" for="organization_name">Nombre de la organización</label>
                                <input id="organization_name" name="organization_name" type="text" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('organization_name', $org->name) }}" required>
                                @error('organization_name')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-sm" for="organization_description">Descripción</label>
                                <textarea id="organization_description" name="organization_description" rows="4" class="mt-1 block w-full rounded-xl border-neutral-mid/40">{{ old('organization_description', $org->description) }}</textarea>
                                @error('organization_description')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="text-sm" for="organization_email">Email</label>
                                <input id="organization_email" name="organization_email" type="email" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('organization_email', $org->email) }}">
                                @error('organization_email')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="text-sm" for="organization_phone">Teléfono</label>
                                <input id="organization_phone" name="organization_phone" type="text" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('organization_phone', $org->phone) }}">
                                @error('organization_phone')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="text-sm" for="organization_city">Ciudad</label>
                                <input id="organization_city" name="organization_city" type="text" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('organization_city', $org->city) }}">
                                @error('organization_city')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="text-sm" for="organization_state">Estado/Provincia</label>
                                <input id="organization_state" name="organization_state" type="text" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('organization_state', $org->state) }}">
                                @error('organization_state')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-sm" for="organization_country">País</label>
                                <input id="organization_country" name="organization_country" type="text" class="mt-1 block w-full rounded-xl border-neutral-mid/40" value="{{ old('organization_country', $org->country) }}">
                                @error('organization_country')<p class="text-xs text-danger mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="mt-4">
                            <button class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
                @endif

                <!-- Eliminar cuenta -->
                <div class="rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-6">
                    <h2 class="text-lg font-semibold">Eliminar cuenta</h2>
                    <p class="text-sm text-neutral-dark/70">Esta acción no se puede deshacer.</p>
                    <div class="mt-4 max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>

            <!-- Columna lateral -->
            <aside class="space-y-6">
                <div class="rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-6">
                    <h3 class="font-semibold">Tu rol</h3>
                    <p class="text-sm mt-1">{{ ucfirst($role ?? 'usuario') }}</p>
                    @if($role==='adoptante')
                        <p class="text-sm text-neutral-dark/70 mt-2">Como adoptante puedes explorar mascotas y enviar solicitudes.</p>
                        <a href="{{ route('adoptions.browse') }}" class="btn btn-primary mt-3">Ver mascotas disponibles</a>
                    @elseif(in_array($role,['organizacion','admin']))
                        <p class="text-sm text-neutral-dark/70 mt-2">Gestiona mascotas y solicitudes de tu organización.</p>
                        <a href="{{ route('orgs.pets.index') }}" class="btn btn-primary mt-3">Gestionar mascotas</a>
                    @endif
                </div>
                <div class="rounded-2xl border border-neutral-mid/30 bg-white dark:bg-neutral-dark p-6">
                    <h3 class="font-semibold">Ayuda rápida</h3>
                    <ul class="mt-2 text-sm list-disc ps-5 space-y-1">
                        <li>Actualiza tu nombre y correo y verifica tu email.</li>
                        <li>¿Olvidaste tu contraseña? Cámbiala desde "Seguridad".</li>
                        @if($role==='adoptante')
                        <li>Explora y guarda mascotas para revisar más tarde.</li>
                        @else
                        <li>Publica mascotas en estado “Publicado” para que aparezcan en Adoptar.</li>
                        @endif
                    </ul>
                </div>
            </aside>
        </div>
    </main>
</body>
</html>
