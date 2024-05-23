<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use App\State\TaxeEnlevementOrdureCalculatorProcessor;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: "/calculate/taxe_enlevement_ordures",
            openapi: new Model\Operation(
                summary: "Calculez la taxe d'enlèvement des ordures de votre propriété!",
                description: "Il vous faudra seulement entrer la valeur du loyer annuel potentiel de votre propriété"
            ),
            //controller: TaxeFonciereCalculatorAction::class
            normalizationContext: ["groups"=>["taxe_enlevement_ordure:read"]],
            denormalizationContext: ["groups"=>["taxe_enlevement_ordure:write"]],
            input: TaxeEnlevementOrdureCalculator::class,
            output: TaxeEnlevementOrdureCalculator::class,
            processor: TaxeEnlevementOrdureCalculatorProcessor::class

        )
    ]
)]

class TaxeEnlevementOrdureCalculator
{
    #[ApiProperty(
        openapiContext: [
            'type' => 'float',
            'description' => 'Loyer annuel potentiel de votre habitation si elle était mise en location',
        ]
    )]
    #[Groups(['taxe_enlevement_ordure:write'])]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'float')]
    #[Assert\NotNull]
    public float $loyerAnnuel;


    #[ApiProperty(
        openapiContext: [
            'type' => 'float',
            'description'=> 'Résultat de la taxe d/enlevement d/ordures'
        ]
    )]
    #[Groups(['taxe_enlevement_ordure:read'])]
    public float $result;

    public function process(): void
    {
        $locativeCadastrale = $this->loyerAnnuel/2;
        $this->result = $locativeCadastrale * 0.0937;
    }
}