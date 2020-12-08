<?php

require_once __DIR__ . '/../../vendor/autoload.php';

class Conta
{
    private $titular;
    private $saldo;
    private static $numeroDeContas = 0;

    public function __construct(Titular $titular)
    {
        $this->titular = $titular;
        $this->saldo = 0;

        self::$numeroDeContas++;
    }

    public function saca(float $valorASacar): void
    {
        if ($valorASacar > $this->saldo) {
            throw new InvalidArgumentException("Saldo indisponível");
        }

        $this->saldo -= $valorASacar;
    }

    public function deposita(float $valorADepositar): void
    {
        if ($valorADepositar < 0) {
            throw new InvalidArgumentException("Valor precisa ser positivo");
        }

        $this->saldo += $valorADepositar;
    }

    public function transfere(float $valorATransferir, Conta $contaDestino): void
    {
        if ($valorATransferir > $this->saldo) {
            throw new InvalidArgumentException("Saldo indisponível");
        }

        $this->saca($valorATransferir);
        $contaDestino->deposita($valorATransferir);
    }


    public function recuperaSaldo(): float
    {
        return $this->saldo;
    }

    public function recuperaNomeTitular(): string
    {
        return $this->titular->recuperaNome();
    }

    public function recuperaCpfTitular(): string
    {
        return $this->titular->recuperaCpf();
    }
}
