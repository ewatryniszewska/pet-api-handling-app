<x-layout>
    <x-slot:title>Form to upload image</x-slot:title>
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

    <form method="POST" action="{{ route('pet.uploadImage') }}" enctype="multipart/form-data">
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
            <label for="additionalData">Additional Data:</label>
            <input type="text" class="form-control @error('additionalData') border-danger @enderror"
                name="additionalData" id="additionalData" value="{{ old('additionalData') }}">

            @error('additionalData')
                <div class="form-text text-danger">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-group pb-2">
            <label for="image">Image:</label>
            <input type="file" class="form-control @error('image') border-danger @enderror" name="image"
                id="image">

            @error('image')
                <div class="form-text text-danger">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

</x-layout>
