<x-layout>
    <x-slot:title>Information about pet/pets</x-slot:title>
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

    <div class="mb-3 row">
        <div class="col-lg-8 pb-lg-3 pt-3">
            @if (empty($pets))
                <div class="alert alert-warning">
                    <h4 class="alert-heading">No pets found</h4>
                    <p>Please select status and press search to find a pet by status.</p>
                </div>
            @endif
            @foreach ($pets as $pet)
                <form action="{{ route('deletePet', $pet['id']) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn border border-danger w-auto m-1 px-3 btn-danger">Delete</button>
                </form>
                <a class="btn border border-info m-1 px-3" href="{{ route('formToEditPet', $pet['id']) }}">Edytuj</a>
                <p class="m-0">Id: {{ $pet['id'] ?? 'N/A' }}</p>

                <p class="m-0">Category: {{ $pet['category']['name'] ?? 'No category' }}</p>

                <p class="m-0">Name: {{ $pet['name'] ?? 'No name' }}</p>

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

                <p class="m-0">Tags:</p>
                @if (!empty($pet['tags']) && is_array($pet['tags']))
                    <ul>
                        @foreach ($pet['tags'] as $tag)
                            <li>{{ $tag['name'] ?? 'No tag name' }}</li>
                        @endforeach
                    </ul>
                @else
                    <p>No tags</p>
                @endif

                <p class="m-0">Status: {{ $pet['status'] ?? 'No status' }}</p>
                <hr>
            @endforeach
        </div>
    </div>
</x-layout>
