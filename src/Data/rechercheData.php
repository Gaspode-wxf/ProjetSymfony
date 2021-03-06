<?php

namespace App\Data;

use App\Entity\Campus;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

class rechercheData
{
    /**
     * @var string
     */
    public string $champRecherche = "";

    /**
     * @var bool
     */
    public $orga = true;

    /**
     * @var bool
     */
    public $inscrit = true;

    /**
     * @var bool
     */
    public $pasInscrit = true;

    /**
     * @var bool
     */
    public bool $perime = false;


    /**
     * @var Campus
     */
    public Campus $campus;

    /**
     * @var |null
     */
    public $dateMin;

    /**
     * @var |null
     */
    public $dateMax;

}