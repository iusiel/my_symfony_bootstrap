<?php

namespace App\Controller;

use App\Services\Nonce;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SampleController extends AbstractController
{
    #[Route("/", name: "app_sample")]
    public function index(Nonce $nonce): Response {
        return $this->render("sample/index.html.twig", [
            "controller_name" => "SampleController",
        ]);
    }
}
