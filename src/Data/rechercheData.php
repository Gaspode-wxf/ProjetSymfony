<?php

namespace App\Data;

use phpDocumentor\Reflection\Types\Boolean;

class rechercheData
{
    /**
     * @var string
     */
    public string $champRecherche = "";

    /**
     * @var boolean
     */
    public $orga = true;

    /**
     * @var boolean
     */
    public $inscrit = true;

    /**
     * @var boolean
     */
    public $pasInscrit = false;

    /**
     * @var boolean
     */
    public bool $perime = false;

}