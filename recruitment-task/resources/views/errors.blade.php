<x-layout>
    <x-slot:title>Ups... error here</x-slot:title>

    <div class="mb-3 row">
        <div class="col-lg-8 pb-lg-3 pt-3">
            @if ($error)
                <div class="alert alert-danger">
                    <h4 class="alert-heading">Sorry, something went wrong...</h4>
                    <p>{{ $error }}</p>
                </div>
            @endif

        </div>
    </div>
</x-layout>
