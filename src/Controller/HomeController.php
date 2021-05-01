<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Materia;
use App\Service\AulaService;
use App\Entity\Aula;

/**
 * Description of HomeController
 *
 * @author andre
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(AulaService $aulaService)
    {
        $materias = $this->getDoctrine()
            ->getRepository(Materia::class)
            ->findAll();
        
        $dias = $aulaService->getDescricaoDias();
        
        $diasMaterias = [];
        
        $aulas = $this->getDoctrine()->getRepository(Aula::class)->findAll();
        foreach ($aulas as $aula) { /** @var Aula $aula */
            $diasMaterias[$aula->getDiaSemana()][] = $aula->getMateria()->getNome();
        }
        
        return $this->render('home/index.html.twig', [
            'materias' => $materias, 
            'dias' => $dias, 
            'diasMaterias' => $diasMaterias
       ]);
    }
}
