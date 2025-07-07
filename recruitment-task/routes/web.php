<?php

use App\Http\Controllers\PetController;
use Illuminate\Support\Facades\Route;


Route::get('/pet/addPet', [PetController::class, 'showFormToAddPet']);
Route::post('/pet/addPet', [PetController::class, 'addOrEditPet'])
    ->name('pet.addPet');
Route::get('/pet/findPetsByStatus', [PetController::class, 'showFormToFindPetsByStatus']);
Route::get('/pet/uploadImage', [PetController::class, 'showFormToUploadImage']);
Route::post('/pet/uploadImage', [PetController::class, 'uploadImage'])
    ->name('pet.uploadImage');
Route::get('/pet/update', [PetController::class, 'showFormToUpdatePet']);
Route::post('/pet/update', [PetController::class, 'updatePetNameAndStatus'])
    ->name('pet.updateNameAndStatus');
Route::get('/pet/findByStatus', [PetController::class, 'showStatuses']);
Route::get('/pet/{id}', [PetController::class, 'showPetById']);
Route::delete('/pet/{id}', [PetController::class, 'deletePet'])->name('deletePet');
Route::get('/pet/{id}/editPet', [PetController::class, 'showFormToEditPet'])->name('formToEditPet');
Route::post('/pet/{id}/editPet', [PetController::class, 'addOrEditPet'])->name('pet.editPet');
