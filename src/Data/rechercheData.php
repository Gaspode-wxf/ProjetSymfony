<?php

namespace App\Data;

use App\Entity\Campus;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Validator\Constraints\Date;

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
    public $pasInscrit = false;

    /**
     * @var bool
     */
    public bool $perime = false;

    /**
     * @var Campus|null
     */
    public Campus $campus;

    /**
     * @var Date|null
     */
    public Date $dateMin;

    /**
     * @var Date|null
     */
    public Date $dateMax;


}