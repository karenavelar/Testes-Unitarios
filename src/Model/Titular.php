<?php

require_once __DIR__ . '/../../vendor/autoload.php';

class Titular
{
    private $cpf;
    private $nome;

    public function __construct(CPF $cpf, string $nome)
    {
        $this->cpf = $cpf;
        $this->validaNomeTitular($nome);
        $this->nome = $nome;
    }

    public function recuperaCpf(): string
    {
        return $this->cpf->recuperaNumero();
    }

    public function recuperaNome(): string
    {
        return $this->nome;
    }

    private function validaNomeTitular(string $nomeTitular)
    {
        if (strlen($nomeTitular) < 5) {
            throw new InvalidArgumentException("Nome precisa ter pelo menos 5 caracteres");
        }
    }
}
