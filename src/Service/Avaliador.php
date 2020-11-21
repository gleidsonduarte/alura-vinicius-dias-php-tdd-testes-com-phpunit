<?php

namespace Alura\Leilao\Service;

use Alura\Leilao\Model\Leilao;
use DomainException;

class Avaliador
{
    private $menorValor = INF;
    private $maiorValor = -INF;
    private $maioresLances;

    public function getMaiorValor(): float
    {
        return $this->maiorValor;
    }

    public function getMenorValor(): float
    {
        return $this->menorValor;
    }

    public function getMaioresLances(): array
    {
        return $this->maioresLances;
    }

    public function avalia(Leilao $leilao): void
    {
        if ($leilao->estaFinalizado()) {
            throw new DomainException('Leilão já finalizado');
        }

        if (empty($leilao->getLances())) {
            throw new DomainException('Não é possível avaliar leilão vazio.');
        }

        foreach ($leilao->getLances() as $lance) {
            if ($lance->getValor() > $this->maiorValor) {
                $this->maiorValor = $lance->getValor();
            }

            if ($lance->getValor() < $this->menorValor) {
                $this->menorValor = $lance->getValor();
            }
        }

        $lances = $leilao->getLances();
        usort($lances, function($lance1, $lance2) {
            return $lance2->getValor() - $lance1->getValor();
        });
        $this->maioresLances = array_slice($lances, 0, 3);
    }
}