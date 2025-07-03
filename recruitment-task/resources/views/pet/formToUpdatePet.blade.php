<x-layout>
    <x-slot:title>Form to update name or/and status</x-slot:title>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('pet.updateNameAndStatus') }}">
        @csrf
        <div class="form-group pb-2">
            <label for="id">ID:</label>
            <input type="number" min="0" class="form-control @error('id') border-danger @enderror" name="id"
                id="id" value="{{ old('id') }}">

            @error('id')
                <div class="form-text text-danger">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group pb-2">
            <label for="name">Name:</label>
            <input type="text" class="form-control @error('name') border-danger @enderror" name="name"
                id="name" value="{{ old('name') }}">

            @error('name')
                <div class="form-text text-danger">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group pb-2">
            <label for="status">Status:</label>
            <div class="mb-3 row">
                <div class="col-lg-8 pb-lg-3 pt-3">
                    <select name="status" id="status" class="form-select" aria-label="Select example">
                        <option value="{{ null }}" selected>Select status</option>
                        @foreach (App\Enums\PetStatus::cases() as $status)
                            <option value="{{ $status->value }}">{{ $status->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @error('status')
                <div class="form-text text-danger">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

</x-layout>
