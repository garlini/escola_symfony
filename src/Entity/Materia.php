<?php

namespace App\Entity;

use App\Repository\MateriaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MateriaRepository::class)
 */
class Materia
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $nome;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Professor")
     * @@ORM\JoinColumn(name="professor_principal_id", referencedColumnName="id")
     */
    private $professorPrincipal;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Professor")
     * @@ORM\JoinColumn(name="professor_substituto_id", referencedColumnName="id")
     */
    private $professorSubstituto;
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }
    
    function getProfessorPrincipal() {
        return $this->professorPrincipal;
    }

    function getProfessorSubstituto() {
        return $this->professorSubstituto;
    }

    function setProfessorPrincipal($professorPrincipal): void {
        $this->professorPrincipal = $professorPrincipal;
    }

    function setProfessorSubstituto($professorSubstituto): void {
        $this->professorSubstituto = $professorSubstituto;
    }


}
