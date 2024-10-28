<?php

use PHPUnit\Framework\TestCase;
use RodivanBitencourt\DigitalCep\Search;

class SearchTest extends TestCase
{
    /**
     * @dataProvider dadosEnderecoTeste
     */
    public function testGetAddressFromZipCodeDefaultUsage(string $input, array $expected) {
        $search = new Search();
        $result = $search->getAddressFromZipCode($input);

        $this->assertEquals($expected, $result);
    }

    public function dadosEnderecoTeste() {
        return [
            "Endereço Praça da Sé" => [
                "01001000",
                [
                    "cep" => "01001-000",
                    "logradouro" => 'Praça da Sé',
                    "complemento" => "lado ímpar",
                    "unidade" => "",
                    "bairro" => "Sé",
                    "localidade" => "São Paulo",
                    "uf" => "SP",
                    "estado" => "São Paulo",
                    "regiao" => "Sudeste",
                    "ibge" => "3550308",
                    "gia" => "1004",
                    "ddd" => "11",
                    "siafi" => "7107"
                ]
            ],
            "Endereço Qualquer" => [
                "03624010",
                [
                    "cep" => "03624-010",
                    "logradouro" => "Rua Luís Asson",
                    "complemento" => "",
                    "unidade" => "",
                    "bairro" => "Vila Buenos Aires",
                    "localidade" => "São Paulo",
                    "uf" => "SP",
                    "estado" => "São Paulo",
                    "regiao" => "Sudeste",
                    "ibge" => "3550308",
                    "gia" => "1004",
                    "ddd" => "11",
                    "siafi" => "7107"
                ]
            ]
        ];
    }

    /**
     * @dataProvider dadosFormatacaoCep
     */
    public function testFormatZipCode(string $input, string $expected) {
        $search = new Search();
        $result = $search->formatZipCode($input);

        $this->assertEquals($expected, $result);
    }

    public function dadosFormatacaoCep() {
        return [
            "CEP com hífen" => ["12345-678", "12345678"],
            "CEP com espaços" => [" 123 45 678 ", "12345678"],
            "CEP com caracteres" => ["12a34b567c8", "12345678"],
        ];
    }

    public function testFetchAddressDataSuccess() {
        $search = new Search();
        $result = $search->fetchAddressData('01001000');

        $expected = [
            "cep" => "01001-000",
            "logradouro" => 'Praça da Sé',
            "complemento" => "lado ímpar",
            "unidade" => "",
            "bairro" => "Sé",
            "localidade" => "São Paulo",
            "uf" => "SP",
            "estado" => "São Paulo",
            "regiao" => "Sudeste",
            "ibge" => "3550308",
            "gia" => "1004",
            "ddd" => "11",
            "siafi" => "7107"
        ];

        $resultArray = json_decode($result, true);

        $this->assertEquals($expected, $resultArray);
    }

    public function testFetchAddressDataFailure() {
        $this->expectExceptionMessage('Não foi possível acessar a API de CEP.');

        $search = new Search();
        $search->getAddressFromZipCode('cep_inválido');
    }
    
    public function testDecodeJsonResponseSuccess() {
        $search = new Search();
        $validJson = '{
            "cep": "01001-000",
            "logradouro": "Praça da Sé",
            "complemento": "lado ímpar",
            "unidade": "",
            "bairro": "Sé",
            "localidade": "São Paulo",
            "uf": "SP",
            "estado": "São Paulo",
            "regiao": "Sudeste",
            "ibge": "3550308",
            "gia": "1004",
            "ddd": "11",
            "siafi": "7107"
        }';
        $expectedArray = [
            "cep"=> "01001-000",
            "logradouro"=> "Praça da Sé",
            "complemento"=> "lado ímpar",
            "unidade"=> "",
            "bairro"=> "Sé",
            "localidade"=> "São Paulo",
            "uf"=> "SP",
            "estado"=> "São Paulo",
            "regiao"=> "Sudeste",
            "ibge"=> "3550308",
            "gia"=> "1004",
            "ddd"=> "11",
            "siafi"=> "7107"
        ];

        $result = $search->decodeJsonResponse($validJson);

        $this->assertEquals($expectedArray, $result, "O JSON válido deve ser convertido corretamente em um array.");
    }

    public function testDecodeJsonResponseFailure() {
        $search = new Search();

        $invalidJson = '{"cep": "12345-678", "logradouro": "Rua Exemplo"'; // JSON inválido (falta fechamento)

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Erro ao decodificar resposta JSON");

        $search->decodeJsonResponse($invalidJson);
    }
}
