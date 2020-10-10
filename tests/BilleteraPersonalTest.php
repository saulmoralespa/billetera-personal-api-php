<?php

use BilleteraPersonal\Client;
use PHPUnit\Framework\TestCase;

class BilleteraPersonalTest extends TestCase
{
    public $billeteraPersonal;

    protected function setUp()
    {
        $dotenv = Dotenv\Dotenv::createMutable(__DIR__ . '/../');
        $dotenv->load();

        $user = $_ENV['USER'];
        $password = $_ENV['PASSWORD'];

        $this->billeteraPersonal = new Client($user, $password);
    }

    public function testAuth()
    {
        $response = $this->billeteraPersonal->getToken();
        var_dump($response);
        $this->assertAttributeEquals(0, 'codigo', $response);
        $this->assertAttributeNotEmpty('mensaje', $response);
    }

    public function testPayment()
    {
        $idTransaccionComercio = time();
        $lineaUsuario = "595900000000";
        $monto = "100";
        $response = $this->billeteraPersonal->payment($idTransaccionComercio, $lineaUsuario, $monto);
        var_dump($response);
        $this->assertAttributeNotEmpty('mensajeTransaccion', $response);
    }

    public function testGetPayment()
    {
        $idTransaccionComercio = "1579447632";
        $response = $this->billeteraPersonal->getPayment($idTransaccionComercio);
        $this->assertObjectHasAttribute('estado', $response);
    }
}