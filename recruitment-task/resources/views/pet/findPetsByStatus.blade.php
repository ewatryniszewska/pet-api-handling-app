<x-layout>
    <x-slot:title>Choose status to find pets</x-slot:title>

    <form method="GET" action="{{ url('/pet/findByStatus') }}">

        <div class="mb-3 row">
            <div class="col-lg-8 pb-lg-3 pt-3">
                <select name="status[]" class="form-select" multiple aria-label="Multiple select example">
                    @foreach (App\Enums\PetStatus::cases() as $status)
                        <option value="{{ $status->value }}">{{ $status->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <div class="mb-3 row">
        <div class="col-lg-8 pb-lg-3 pt-3">
            @include('pet.showPets', ['pets' => $pets])</div>
    </div>

</x-layout>
