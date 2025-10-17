<section class="space-y-4">
    <header>
        <h2 class="text-lg font-semibold">Eliminar cuenta</h2>
        <p class="mt-1 text-sm text-neutral-dark/70">Esta acción no se puede deshacer. Descarga cualquier dato que quieras conservar.</p>
    </header>

    <form id="delete-account-form" method="post" action="{{ route('profile.destroy') }}" class="d-none">
        @csrf
        @method('delete')
        <input type="hidden" name="password" id="delete-account-password" />
    </form>

    <button type="button" class="btn btn-danger" data-confirm-form="delete-account-form">Eliminar cuenta</button>
</section>