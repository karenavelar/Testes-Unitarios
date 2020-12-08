<?php

use PHPUnit\Framework\TestCase;

class ContaTest extends TestCase
{
    /**
     * @dataProvider geraTitular
     * @covers Conta::__construct
     * @covers Conta::recuperaCpfTitular
     * @covers Conta::recuperaNomeTitular
     * @covers Conta::recuperaSaldo
     * @covers CPF::recuperaNumero
     * @covers Titular::recuperaCpf
     * @covers Titular::recuperaNome
     */
    public function testCriaConta(Titular $titular)
    {
        $conta = new Conta($titular);

        static::assertEquals("Laura Cardoso", $conta->recuperaNomeTitular());
        static::assertEquals("123.456.789-10", $conta->recuperaCpfTitular());
        static::assertEquals(0, $conta->recuperaSaldo());
    }


    /**
     * @dataProvider geraContaVazia
     * @covers Conta::deposita
     * @covers Conta::recuperaSaldo
     */
    public function testDepositoValido($conta)
    {
        $conta->deposita(150);

        static::assertEquals(150, $conta->recuperaSaldo());
    }

    /**
     * @dataProvider geraContaVazia
     * @covers Conta::deposita
     */
    public function testDepositoInvalido($conta)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Valor precisa ser positivo");

        $conta->deposita(-100);
    }

    /**
     * @dataProvider geraContaComSaldo
     * @covers Conta::saca
     * @covers Conta::recuperaSaldo
     */

    public function testSaqueValido($conta)
    {
        $conta->saca(200);

        static::assertEquals(800, $conta->recuperaSaldo());
    }

    /**
     * @dataProvider geraContaVazia
     * @covers Conta::saca
     */
    public function testSacaComSaldoIndisponivel($conta)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Saldo indisponível");

        $conta->saca(200);
    }

    /**
     * @dataProvider geraContasTransferenciaComSaldo
     * @covers Conta::transfere
     * @covers Conta::recuperaSaldo
     * @covers Conta::saca
     * @covers Conta::deposita
     */
    public function testTransfereComSaldo(Conta $conta1, Conta $conta2)
    {
        $conta1->transfere(200, $conta2);

        static::assertEquals(800, $conta1->recuperaSaldo());
        static::assertEquals(200, $conta2->recuperaSaldo());
    }

    /**
     * @dataProvider geraContasTransferenciaSemSaldo
     * @covers Conta::transfere
     */
    public function testTransfereSemSaldo($conta1, $conta2)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Saldo indisponível");
        $conta1->transfere(200, $conta2);
    }

    /**
     * @codeCoverageIgnore
     */
    public function geraContaVazia()
    {
        $cpf = new CPF("123.456.789-10");
        $titular = new Titular($cpf, "Laura Cardoso");
        $conta = new Conta($titular);

        return [
            [$conta]
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function geraContaComSaldo()
    {
        $cpf = new CPF("123.456.789-10");
        $titular = new Titular($cpf, "Laura Cardoso");
        $conta = new Conta($titular);
        $conta->deposita(1000);

        return [
            [$conta]
        ];
    }

    /**
     * @covers Conta::transfere
     * @covers Conta::recuperaSaldo
     * @codeCoverageIgnore
     */
    public function geraContasTransferenciaComSaldo(): array
    {
        $cpf = new CPF("123.456.789-10");
        $titular = new Titular($cpf, "Laura Cardoso");
        $conta1 = new Conta($titular);
        $conta1->deposita(1000);

        $cpf = new CPF("987.654.321-10");
        $titular = new Titular($cpf, "Maria");
        $conta2 = new Conta($titular);

        return [
            [$conta1, $conta2]
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function geraContasTransferenciaSemSaldo(): array
    {
        $cpf = new CPF("123.456.789-10");
        $titular = new Titular($cpf, "Laura Cardoso");
        $conta1 = new Conta($titular);

        $cpf = new CPF("987.654.321-10");
        $titular = new Titular($cpf, "Maria");
        $conta2 = new Conta($titular);

        return [
            [$conta1, $conta2]
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public function geraTitular()
    {
        $cpf = new CPF("123.456.789-10");
        $titular = new Titular($cpf, "Laura Cardoso");

        return [
            [$titular]
        ];
    }

    /**
     * @dataProvider geraContaVazia
     * @covers Conta::recuperaSaldo
     * @covers Conta::saca
     * @covers Conta::deposita
     */
    public function testValorDoSaqueNaoPodeSerMaiorQueSaldo(Conta $conta)
    {
        $conta->deposita(200);
        $conta->saca(100);
        static::assertEquals(100, $conta->recuperaSaldo());
    }

    /**
     * @covers CPF::__construct()
     * @covers CPF::recuperaNumero()
     */
    public function testCriaCpfValido()
    {
        $cpf = new CPF("606.678.213-23");

        static::assertEquals("606.678.213-23", $cpf->recuperaNumero());
    }

    /**
     * @covers CPF::__construct()
     */
    public function testRecusaCpfInvalido()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Cpf inválido");

        $cpf = new CPF("606.678.21-23");
    }

    /**
     * @dataProvider geraCpf
     * @covers Titular::__construct()
     * @covers Titular::validaNomeTitular
     * @uses CPF::__construct()
     * @uses Titular::validaNomeTitular()
     */
    public function testRecusaTitularComNomeInvalido($cpf)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Nome precisa ter pelo menos 5 caracteres");

        $titular = new Titular($cpf, "J");
    }

    /**
     * @dataProvider geraCpf
     * @covers Titular::__construct()
     * @covers Titular::recuperaNome()
     * @covers Titular::recuperaCpf()
     * @covers CPF::recuperaNumero()
     * @covers Titular::validaNomeTitular()
     */
    public function testCriaTitularComNomeValido($cpf)
    {
        $titular = new Titular($cpf, "João da Silva Sauro");

        static::assertEquals("João da Silva Sauro", $titular->recuperaNome());
        static::assertEquals("123.456.789-10", $titular->recuperaCpf());
    }

    public function geraCpf()
    {
        $cpf = new CPF("123.456.789-10");

        return [
            [$cpf]
        ];
    }


}
