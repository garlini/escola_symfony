<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Professor;
use App\Form\Type\ProfessorType;

/**
 * Description of ProfessorController
 *
 * @author andre
 */
class ProfessorController extends AbstractController 
{
    /**
     * @Route("/professores", name="professores_list")
     */
    public function index()
    {
        $professores = $this->getDoctrine()
            ->getRepository(Professor::class)
            ->findAll();
        
        return $this->render('professor/index.html.twig', ['professores' => $professores]);
    }
    
    /**
     * @Route("/professores/create", name="professores_create")
     */
    public function create(Request $request)
    {
        $professor = new Professor();
        
        $form = $this->createForm(ProfessorType::class, $professor);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->getDoctrine()->getManager()->persist($professor);
            $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute('professores_list');
        }
                
        return $this->render('professor/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/professores/update/{id}", name="professores_update")
     */
    public function update(Request $request, $id)
    {
        $professor = $this->getDoctrine()
            ->getRepository(Professor::class)
            ->find($id);
        
        if (!$professor) {
            throw new NotFoundHttpException();
        }
        
        $form = $this->createForm(ProfessorType::class, $professor);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute('professores_list');
        }
        
        return $this->render('professor/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
