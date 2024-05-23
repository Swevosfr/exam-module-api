<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use App\State\TaxeFonciereCalculatorProcessor;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
       new Post(
           uriTemplate: "/calculate/taxe_fonciere",
           openapi: new Model\Operation(
               summary: "Calculez la taxe foncière de votre habitation !",
               description: "Il vous faudra pour cela entrer la surface habitable de votre habitation ainsi que son prix au mètre carré"
           ),
           //controller: TaxeFonciereCalculatorAction::class
            normalizationContext: ["groups"=>["taxe_fonciere:read"]],
           denormalizationContext: ["groups"=>["taxe_fonciere:write"]],
           input: TaxeFonciereCalculator::class,
           output: TaxeFonciereCalculator::class,
           processor: TaxeFonciereCalculatorProcessor::class

       )
    ]
)]

class TaxeFonciereCalculator
{
    #[ApiProperty(
        openapiContext: [
            'type' => 'float',
            'description' => 'Surface habitable en mètres carrés',
        ]
    )]
    #[Groups(['taxe_fonciere:write'])]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'float')]
    #[Assert\NotNull]
    public float $surface;


    #[ApiProperty(
        openapiContext: [
            'type' => 'float',
            'description' => 'Prix au mètre carré',
        ]
    )]
    #[Groups(['taxe_fonciere:write'])]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'float')]
    #[Assert\NotNull]
    public float $prix;


    #[ApiProperty(
        openapiContext: [
            'type' => 'float',
            'description'=> 'Résultat de la taxe foncière'
        ]
    )]
    #[Groups(['taxe_fonciere:read'])]
    public float $result;

    public function process(): void
    {
        $cadastrale = $this->surface * $this->prix;
        $this->result = $cadastrale * 0.005;
    }
}