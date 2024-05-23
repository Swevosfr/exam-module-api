<?php

namespace App\Controller;

use App\ApiResource\TaxeFonciereCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaxeFonciereCalculatorAction extends AbstractController
{
    public function __invoke(TaxeFonciereCalculator $data): TaxeFonciereCalculator
    {
        $data->process();

        return $data;
    }
}