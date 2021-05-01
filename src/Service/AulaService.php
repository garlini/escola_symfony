<?php
namespace App\Service;

use App\Entity\Materia;
use App\Entity\Aula;

use Doctrine\Persistence\ManagerRegistry;

/**
 * Description of AulaService
 *
 * @author andre
 */
class AulaService 
{
    const MAX_AULAS_SEMANA = 3;
    
    protected $doctrine;


    public function __construct(ManagerRegistry $doctrine) 
    {
        $this->doctrine = $doctrine;
    }
    
    /**
     * Mapeia um horário (int) para um nome de campo.
     * @return array
     */
    public function getMapHorarios(): array
    {
        return [
            1 => 'horario_1',
            2 => 'horario_2',
            3 => 'horario_3',
            4 => 'horario_4'
        ];
    }
    
    /**
     * Mapeia uma constante de dia para um label que pode ser exibido ao usuário.
     * @return array
     */
    public function getDescricaoDias(): array
    {
        return [
            Aula::SEGUNDA_FEIRA => 'Segunda-feira',
            Aula::TERCA_FEIRA => 'Terça-feira',
            Aula::QUARTA_FEIRA => 'Quarta-feira',
            Aula::QUINTA_FEIRA => 'Quinta-feira',
            Aula::SEXTA_FEIRA => 'Sexta-feira',
            Aula::SABADO_3 => 'Penúltimo Sábado',
            Aula::SABADO_4 => 'Último Sábado',
        ];
    }
        
    /**
     * Processa os dados de submit para um dia de semana.
     * 
     * @param string $diaSemana Dia da semana sendo processado.
     * @param array $formData os dados de submit.
     * @return array Array com os erros de validação. Array vazio em caso de sucesso.
     */
    public function gravarAulasDiaSemana(string $diaSemana, array $formData): array
    {
        $erros = [];
               
        $horarios = $this->getMapHorarios();
        
        //verifica se alguma matéria foi selecionada mais de uma vez ou se algum horário foi ignorado.
        $selecionadas = [];
        
        foreach ($horarios as $horario) {
            if (!isset($formData[$horario]) || (!$formData[$horario] instanceof Materia)) {
                $erros[] = 'Todos horários devem ser prenchidos.';
                return $erros;
            }
            if (in_array($formData[$horario]->getId(), $selecionadas)) {
                $erros[] = "Matéria {$formData[$horario]->getNome()} foi selecionada mais de uma vez.";
                return $erros;
            }
            $selecionadas[] = $formData[$horario]->getId();
        }
        
        //aulas aos sábados são consideradas de reforço
        $isReforco = in_array($diaSemana, [
            Aula::SABADO_1,
            Aula::SABADO_2,
            Aula::SABADO_3,
            Aula::SABADO_4
        ]);
        
        //verifica se alguma materia passou do limite semanal
        if (!$isReforco) {
            foreach ($horarios as $horario) {
                $materia = $formData[$horario];

                $count = $this->doctrine->getRepository(\App\Entity\Aula::class)
                        ->createQueryBuilder('a')
                        ->select('count(a.id)')
                        ->where('a.materia = :materia')
                        ->andWhere('a.diaSemana <> :diaSemana')
                        ->andWhere('a.reforco = :reforco') /* ignora aulas de reforço na contagem */
                        ->setParameters(['materia' => $materia, 'diaSemana' => $diaSemana, 'reforco' => false])
                        ->getQuery()
                        ->getSingleScalarResult();

                if ($count >= self::MAX_AULAS_SEMANA) {
                    $erros[] = "Matéria {$materia->getNome()} excedeu o limite semanal de aulas.";
                    return $erros;
                }
            }
        } // else: não precisa verificar dia de reforço
        
        //tudo ok, faz a gravação.        
        foreach ($horarios as $kHorario => $horario)
        {
            //procura uma aula para o dia de semana e horario corrente, se não existir cria uma nova.
            $aula = $this->doctrine->getRepository(\App\Entity\Aula::class)
                    ->findOneBy([
                        'diaSemana' => $diaSemana,
                        'horario' => $kHorario
                    ]);
            if (!$aula instanceof \App\Entity\Aula) {
                $aula = new Aula();
                $aula->setDiaSemana($diaSemana);
                $aula->setHorario($kHorario);
                $this->doctrine->getManager()->persist($aula);
            }
            $aula->setMateria($formData[$horario]);
            $aula->setReforco($isReforco);
        }
        
        return $erros;
    }
}
