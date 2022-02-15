<?php

namespace App\Controller;

use App\Form\CSVType;
use App\Service\CSVService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CSVController extends AbstractController
{
    /**
     * @Route("/admin/import", name="import")
     */
    public function index(Request $request, CSVService $CSVService): Response
    {
        $csv = [];
        $form = $this->createForm(CSVType::class, $csv);
        $form->handleRequest($request);
if ($form->isSubmitted())
{
    $csvFile = $form->get('csv')->getData();
    $CSVService->readCSV($csvFile);
    dd($csvFile);
}
        return $this->renderForm('csv/index.html.twig', [
            'form'=>$form,
        ]);
    }




}
