<x-layout>
    @if (isset($pet))
        <x-slot:title>Form to edit pet</x-slot:title>
    @else
        <x-slot:title>Form to add pet</x-slot:title>
    @endif
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

    @if (isset($pet))
        <form method="POST" action="{{ route('pet.editPet', $pet['id']) }}" enctype="multipart/form-data">
        @else
            <form method="POST" action="{{ route('pet.addPet') }}" enctype="multipart/form-data">
    @endif

    @csrf

    @if (isset($pet['id']))
        <div class="form-group pb-2">
            <label for="id">ID:</label>
            <p class="m-0">{{ $pet['id'] ?? 'N/A' }}</p>
        </div>
    @else
        <div class="form-group pb-2">
            <label for="id">ID:</label>
            <input type="number" min="0" class="form-control @error('id') border-danger @enderror"
                name="id" id="id" value="{{ old('id') }}">

            @error('id')
                <div class="form-text text-danger">
                    {{ $message }}
                </div>
            @enderror
        </div>
    @endif

    <div class="form-group pb-2">
        <label for="name">Name:</label>
        <input type="text" class="form-control @error('name') border-danger @enderror" name="name" id="name"
            value="{{ old('name', isset($pet['name']) ? $pet['name'] : '') }}">

        @error('name')
            <div class="form-text text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group pb-2">
        <label for="category">Category:</label>
        <div class="pb-2">
            <select name="category" id="category" class="form-select" aria-label="Select example">
                @foreach (config('categories') as $categoryId => $categoryName)
                    <option value="{{ $categoryId }}"
                        {{ isset($pet['category']) && collect($pet['category'])->pluck('id')->contains($categoryId) ? 'selected' : '' }}>
                        {{ $categoryName }}
                    </option>
                @endforeach
            </select>
        </div>

        @error('category')
            <div class="form-text text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>

    @if (isset($pet['photoUrls']))
        <p class="m-0">Photos:</p>
        @if (!empty($pet['photoUrls']) && is_array($pet['photoUrls']))
            <ul>
                @foreach ($pet['photoUrls'] as $url)
                    <li>{{ $url }}</li>
                    <img src="{{ $url }}" style="max-width: 300px">
                @endforeach
            </ul>
        @else
            <p>No photos</p>
        @endif
    @endif

    <div class="form-group pb-2">
        <label for="image">Image:</label>
        <input type="file" class="form-control @error('image') border-danger @enderror" name="image[]" id="image"
            accept="image/png, image/jpg, image/jpeg, image/gif, image/svg" multiple>

        @error('image')
            <div class="form-text text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group pb-2">
        <label for="tags">Tags:</label>
        <div class="pb-2">
            <select name="tags[]" class="form-select" multiple aria-label="Multiple select example">
                @foreach (config('tags') as $tagId => $tagName)
                    <option value="{{ $tagId }}"
                        {{ isset($pet['tags']) && collect($pet['tags'])->pluck('id')->contains($tagId) ? 'selected' : '' }}>
                        {{ $tagName }}
                    </option>
                @endforeach
            </select>
        </div>

        @error('tags')
            <div class="form-text text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="form-group pb-2">
        <label for="status">Status:</label>
        <div class="pb-2">
            <select name="status" id="status" class="form-select" aria-label="Select example">
                @if (isset($pet['status']))
                    <option value="{{ $pet['status'] }}" selected>{{ $pet['status'] }}</option>
                @else
                    <option value="{{ null }}" selected>Select status</option>
                @endif

                @foreach (App\Enums\PetStatus::cases() as $status)
                    <option value="{{ $status->value }}">{{ $status->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    </form>

</x-layout>
