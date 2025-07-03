<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Exceptions\InvalidRequestException;
use App\Exceptions\NotFoundException;

class PetService
{
    protected static $apiUrl = 'https://petstore.swagger.io/v2/pet';

    public static function findPetById($id)
    {
        $response = Http::get(self::$apiUrl . '/' . $id);
        return self::handleExceptions($response);
    }

    public static function findByStatus($status)
    {
        $statusToUrl = '?';
        foreach ($status as $s) {
            $statusToUrl .= 'status=' . $s . '&';
        }

        $response = Http::get(self::$apiUrl . '/findByStatus' . $statusToUrl);
        return self::handleExceptions($response);
    }

    public static function updatePetNameAndStatus($id, $data)
    {
        $response = Http::asForm()->post(self::$apiUrl . '/' . $id, $data);
        return self::handleExceptions($response);
    }

    public static function uploadImage($id, $data)
    {
        $response = Http::asMultipart()->attach('file', fopen($data['file']->getRealPath(), 'r'), $data['file']->getClientOriginalName())->post(self::$apiUrl . '/' . $id . '/uploadImage', ['additionalMetadata' => $data['additionalData'] ?? null,]);
        return self::handleExceptions($response);
    }

    public static function deletePetById($id)
    {
        $response = Http::delete(self::$apiUrl . '/' . $id);
        return self::handleExceptions($response);
    }

    public static function addPet($data)
    {
        $response = Http::withBody($data, 'application/json')->post(self::$apiUrl);
        return self::handleExceptions($response);
    }

    public static function editPet($data)
    {
        $response = Http::withBody($data, 'application/json')->put(self::$apiUrl);
        return self::handleExceptions($response);
    }

    protected static function handleExceptions($response)
    {
        if ($response->ok()) {
            return $response->json();
        } elseif ($response->badRequest()) {
            throw new InvalidRequestException();
        } elseif ($response->notFound()) {
            throw new NotFoundException();
        }
    }
}
