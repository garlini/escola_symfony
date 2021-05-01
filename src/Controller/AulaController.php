<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Entity\Aula;
use App\Service\AulaService;

/**
 * Description of AulaController
 *
 * @author andre
 */
class AulaController extends AbstractController 
{
    /**
     * @Route("/aulas", name="aulas_list")
     */
    public function index(AulaService $aulaService)
    {        
        $dias = $aulaService->getDescricaoDias();
        
        $diasMaterias = [];
        
        $aulas = $this->getDoctrine()->getRepository(Aula::class)->findAll();
        foreach ($aulas as $aula) { /** @var Aula $aula */
            $diasMaterias[$aula->getDiaSemana()][] = $aula->getMateria()->getNome();
        }
                
        return $this->render('aula/index.html.twig', ['dias' => $dias, 'diasMaterias' => $diasMaterias]);
    }
    
    /**
     * @Route("/aulas/edit/{diaSemana}", name="aulas_edit")
     */
    public function edit(Request $request, AulaService $aulaService, string $diaSemana)
    { 
        $erros = [];
        $data = [];
        
        $horarios = $aulaService->getMapHorarios();
        
        if ($request->isMethod('GET')) {
            //Preenche o formulario com as materias cadastradas anteriormente
            $aulas = $this->getDoctrine()->getRepository(Aula::class)->findBy(['diaSemana' => $diaSemana]);
            foreach ($aulas as $aula) { /** @var Aula $aula */
                $data[$horarios[$aula->getHorario()]] = $aula->getMateria();
            }
        }
        
        $form = $this->createForm(\App\Form\Type\GradeType::class, $data);
                
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            
            $erros = $aulaService->gravarAulasDiaSemana($diaSemana, $formData);
                        
            if (empty($erros)) {
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('aulas_list');
            }
        } 
        
        return $this->render('aula/edit.html.twig', [
            'form' => $form->createView(),
            'erros' => $erros
        ]);
    }
}
