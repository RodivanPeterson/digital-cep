<?php

use PHPUnit\Framework\TestCase;
use RodivanBitencourt\DigitalCep\Search;

class SearchTest extends TestCase
{
    /**
     * @dataProvider dadosEnderecoTeste
     */
    public function testGetAddressFromZipCodeDefaultUsage(string $input, array $esperado) {
        $search = new Search();
        $resultado = $search->getAddressFromZipCode($input);

        $this->assertEquals($esperado, $resultado);
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
    public function testFormatZipCode(string $input, string $esperado) {
        $search = new Search();

        $reflection = new \ReflectionClass($search);
        $method = $reflection->getMethod('formatZipCode');
        $method->setAccessible(true);
        
        $resultado = $method->invoke($search, $input);

        $this->assertEquals($esperado, $resultado);
    }

    public function dadosFormatacaoCep() {
        return [
            "CEP com hífen" => ["12345-678", "12345678"],
            "CEP com espaços" => [" 123 45 678 ", "12345678"],
            "CEP com caracteres" => ["12a34b567c8", "12345678"],
        ];
    }
}
