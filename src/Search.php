<?php

namespace RodivanBitencourt\DigitalCep;

class Search
{
    private const API_URL = "https://viacep.com.br/ws/";

    public function getAddressFromZipCode(string $zipCode): array {
        $formattedZipCode = $this->formatZipCode($zipCode);
        $response = $this->fetchAddressData($formattedZipCode);
        $addressData = $this->decodeJsonResponse($response);

        return $addressData ?: [];
    }

    public function formatZipCode(string $zipCode): string {
        return preg_replace('/[^0-9]/', '', $zipCode);
    }

    public function fetchAddressData(string $zipCode) {
        $response = @file_get_contents(self::API_URL . $zipCode . "/json");

        if ($response === false) {
            throw new \Exception("Não foi possível acessar a API de CEP.");
        }
        
        return $response;
    }

    public function decodeJsonResponse(string $response): array {
        $addressData = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Erro ao decodificar resposta JSON.");
        }

        return $addressData;
    }
}
