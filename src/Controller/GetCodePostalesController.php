<?php

namespace App\Controller;

use App\Repository\VillesFranceFreeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetCodePostalesController extends AbstractController
{
    #[Route('/get_code_postal', name: 'get_code_postal')]
    public function index(VillesFranceFreeRepository $franceFreeRepository,Request $request): Response
    {
        $axiosPost = json_decode($request->getContent())->codePostal;
        $villes = $franceFreeRepository->finVilleByCode($axiosPost);
        $arrayVille =[];
        foreach ($villes as $ville){
            $arrayVille[] = $ville->toArray();
        }
    return $this->json($arrayVille);
    }
}
