<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Entity\Materia;
use App\Form\Type\MateriaType;

/**
 * Description of MateriaController
 *
 * @author andre
 */
class MateriaController extends AbstractController 
{
    /**
     * @Route("/materias", name="materias_list")
     */
    public function index()
    {
        $materias = $this->getDoctrine()
            ->getRepository(Materia::class)
            ->findAll();
        
        return $this->render('materia/index.html.twig', ['materias' => $materias]);
    }
    
    /**
     * @Route("/materias/create", name="materias_create")
     */
    public function create(Request $request)
    {
        $materia = new Materia();
        
        $form = $this->createForm(MateriaType::class, $materia);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->getDoctrine()->getManager()->persist($materia);
            $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute('materias_list');
        }
        
        return $this->render('materia/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/materias/update/{id}", name="materias_update")
     */
    public function update(Request $request, $id)
    {
        $materia = $this->getDoctrine()
            ->getRepository(Materia::class)
            ->find($id);
        
        if (!$materia) {
            throw new NotFoundHttpException();
        }
        
        $form = $this->createForm(MateriaType::class, $materia);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute('materias_list');
        }
        
        return $this->render('materia/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
