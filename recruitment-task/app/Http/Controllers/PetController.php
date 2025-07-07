<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Services\PetService;
use Exception;
use Illuminate\Http\Request;
use App\Exceptions\InvalidRequestException;
use App\Exceptions\NotFoundException;
use App\Http\Requests\AddOrEditPetRequest;
use App\Http\Requests\StatusRequest;
use App\Http\Requests\UpdatePetNameAndStatusRequest;
use App\Http\Requests\UploadImageRequest;

class PetController extends Controller
{
    public function showPetById(int $id)
    {
        try {
            $pets = [PetService::findPetById($id)];
        } catch (Exception $e) {
            return $this->handleHttpExceptions($e);
        }

        return view('pet.showPets', compact('pets'));
    }

    public function showStatuses(StatusRequest $request)
    {
        if ($request->input('status')) {
            try {
                $pets = PetService::findByStatus($request->status);
            } catch (Exception $e) {
                return $this->handleHttpExceptions($e);
            }
        } else {
            return view('pet.findPetsByStatus', ['pets' => []]);
        }

        return view('pet.findPetsByStatus', compact('pets'));
    }

    public function showFormToUpdatePet()
    {
        return view('pet.formToUpdatePet');
    }

    public function updatePetNameAndStatus(UpdatePetNameAndStatusRequest $request)
    {
        $petIdToUpdate = $request->input('id');
        $dataToUpdate = [];
        if ($request->input('name') === null && $request->input('status') !== null) {
            $dataToUpdate = [
                'status' => $request->input('status'),
            ];
        } elseif ($request->input('name') !== null && $request->input('status') === null) {
            $dataToUpdate = [
                'name' => $request->input('name'),
            ];
        } elseif ($request->input('name') !== null && $request->input('status') !== null) {
            $dataToUpdate = [
                'name' => $request->input('name'),
                'status' => $request->input('status'),
            ];
        } else {
            return back()->with('error', 'You are sending no data to update.');
        }

        try {
            PetService::updatePetNameAndStatus($petIdToUpdate, $dataToUpdate);
            return back()->with('success', 'Data has been updated.');
        } catch (Exception $e) {
            return $this->handleHttpExceptions($e);
        }
    }

    public function showFormToUploadImage()
    {
        return view('pet.formToUploadImage');
    }

    public function uploadImage(UploadImageRequest $request)
    {
        $petId = $request->input('id');
        $dataToUpdate = [];

        if ($request->file('image') === null) {
            return back()->with('error', 'Sorry, you can\'t send only additional data, you must also upload an image.');
        }

        $dataToUpdate = [
            'file' => $request->file('image')
        ];

        if ($request->input('additionalData') !== null) {
            $dataToUpdate['additionalData'] = $request->input('additionalData');
        }

        try {
            $this->uploadImageToPet($petId, $dataToUpdate);
            return back()->with('success', 'Image has been uploaded successfully.');
        } catch (Exception $e) {
            return $this->handleHttpExceptions($e);
        }
    }

    public function deletePet(int $id)
    {
        try {
            if (PetService::findPetById($id)) {
                PetService::deletePetById($id);
                return back()->with('success', 'Pet has been deleted successfully.');
            }
        } catch (Exception $e) {
            return $this->handleHttpExceptions($e);
        }
    }

    public function showFormToAddPet()
    {
        return view('pet.formToAddOrEditPet');
    }

    public function addOrEditPet(AddOrEditPetRequest $request, ?int $id = null)
    {
        $jsonData = ['id' => $request->input('id'),];
        if ($id === null && $request->input('id') === null) {
            $jsonData['id'] = rand(1, 1000000);
        }

        if ($request->input('category')) {
            $categoryId = $request->input('category');
            $categoryName = config('categories')[$categoryId];
            $jsonData['category'] = ['id' => $categoryId, 'name' => $categoryName];
        }

        $photoUrls = [];
        if ($request->file('image') !== null) {
            foreach ($request->file('image') as $image) {
                try {
                    $this->uploadImageToPet($request->input('id'), ['file' => $image]);
                    $photoUrls[] = $image->getClientOriginalName();
                } catch (InvalidRequestException) {
                    return back()->with('error', 'Image upload failed. Please try again.');
                }
            }
        }

        $jsonData['name'] = $request->input('name');
        $jsonData['photoUrls'] = $photoUrls;

        if ($request->input('tags')) {
            $tags = [];
            foreach ($request->input('tags') as $key => $tag) {
                $tags[$key] = ['id' => $tag, 'name' => config('tags')[$tag]];
            }
            $jsonData['tags'] = $tags;
        }

        if ($request->input('status')) {
            $jsonData['status'] = $request->input('status');
        }

        try {
            if ($id === null) {
                PetService::addPet(json_encode($jsonData));
                return back()->with('success', 'Pet has been added successfully.');
            }
            PetService::editPet(json_encode($jsonData));
            return back()->with('success', 'Pet has been updated successfully.');
        } catch (Exception $e) {
            return $this->handleHttpExceptions($e);
        }
    }

    public function showFormToEditPet(int $id)
    {
        try {
            $pet = PetService::findPetById($id);
            if (!array_key_exists($pet['category']['id'], config('categories'))) {
                $pet['category']['id'] = 0;
            }
            foreach ($pet['tags'] as $key => $tag) {
                if (!array_key_exists($key, config('tags'))) {
                    $pet['tags'][$key] = 0;
                }
            }
            return view('pet.formToAddOrEditPet', compact('pet'));
        } catch (Exception $e) {
            return $this->handleHttpExceptions($e);
        }
    }

    protected function uploadImageToPet(int $id, array $data): array
    {
        $response = PetService::uploadImage($id, $data);

        if ($response) {
            return $response;
        } else {
            throw new InvalidRequestException();
        }
    }

    protected function handleHttpExceptions($exception)
    {
        switch (get_class($exception)) {
            case NotFoundException::class:
                $error = '404 Not Found';
                return response()->view('errors', compact('error'), 404);
            case InvalidRequestException::class:
                $error = '400 Bad Request';
                return response()->view('errors', compact('error'), 400);
            default:
                $error = '500 Unexpected Error';
                return response()->view('errors', compact('error'), 500);
        }
    }
}
