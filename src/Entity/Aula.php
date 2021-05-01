<?php

namespace App\Entity;

use App\Repository\AulaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AulaRepository::class)
 */
class Aula
{
    const SEGUNDA_FEIRA = 'SEGUNDA_FEIRA';
    const TERCA_FEIRA   = 'TERCA_FEIRA';
    const QUARTA_FEIRA  = 'QUARTA_FEIRA';
    const QUINTA_FEIRA  = 'QUINTA_FEIRA';
    const SEXTA_FEIRA   = 'SEXTA_FEIRA';
    const SABADO_1      = 'SABADO_1';
    const SABADO_2      = 'SABADO_2';
    const SABADO_3      = 'SABADO_3';
    const SABADO_4      = 'SABADO_4';
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $diaSemana;

    /**
     * @ORM\Column(type="integer")
     */
    private $horario;

    /**
     * @ORM\Column(type="boolean")
     */
    private $reforco;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Materia")
     * @@ORM\JoinColumn(name="materia_id", referencedColumnName="id")
     */
    private $materia;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiaSemana(): ?string
    {
        return $this->diaSemana;
    }

    public function setDiaSemana(string $diaSemana): self
    {
        $this->diaSemana = $diaSemana;

        return $this;
    }

    public function getHorario(): ?int
    {
        return $this->horario;
    }

    public function setHorario(int $horario): self
    {
        $this->horario = $horario;

        return $this;
    }

    public function getReforco(): ?bool
    {
        return $this->reforco;
    }

    public function setReforco(bool $reforco): self
    {
        $this->reforco = $reforco;

        return $this;
    }
    
    function getMateria() {
        return $this->materia;
    }

    function setMateria(Materia $materia): void {
        $this->materia = $materia;
    }


}
