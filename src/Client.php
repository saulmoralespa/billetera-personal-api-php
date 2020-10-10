<?php


namespace BilleteraPersonal;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Utils;

class Client
{
    const API_BASE_URL = "https://www.personal.com.py/ApiComerciosMaven/webresources/";

    protected $user;
    protected $password;

    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function client()
    {
        return new GuzzleClient([
            "base_uri" => self::API_BASE_URL
        ]);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getToken()
    {
        try {
            $response = $this->client()->post("autenticacion",
                [
                    "form_params" => [
                        "usuario" => $this->user,
                        "clave" => $this->password
                    ]
                ]
            );

            $result = self::responseJson($response);
            self::checkErros($result);
            return $result;

        }catch (RequestException $exception){
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * @param $idTransaccionComercio
     * @param $lineaUsuario
     * @param $monto
     * @return mixed
     * @throws \Exception
     */
    public function payment($idTransaccionComercio, $lineaUsuario, $monto)
    {
        try{
            $response = $this->client()->post("pago",
                [
                    "form_params" => [
                        "tokenSession" => $this->getToken()->mensaje,
                        "idTransaccionComercio" => $idTransaccionComercio,
                        "lineaUsuario" => $lineaUsuario,
                        "monto" => $monto
                    ],
                    "timeout" => 60
                ]
            );

            $result = self::responseJson($response);
            self::checkErros($result);
            return $result;

        }catch (RequestException $exception){
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * @param $idTransaccionComercio
     * @return mixed
     * @throws \Exception
     */
    public function getPayment($idTransaccionComercio)
    {
        try{
            $response = $this->client()->post("consulta",
                [
                    "form_params" => [
                        "tokenSession" => $this->getToken()->mensaje,
                        "idTransaccionComercio" => $idTransaccionComercio
                    ]
                ]
            );

            $result = self::responseJson($response);
            self::checkErros($result);
            return $result;

        }catch (RequestException $exception){
            throw new \Exception($exception->getMessage());
        }
    }

    private static function checkErros($result)
    {
        $code = $result->codigo ?? $result->codigoTransaccion;
        if($code !== 0){
            $message = $result->mensaje ?? $result->mensajeTransaccion ?? $result->estado;
            throw new \Exception($message);
        }
    }

    public static function responseJson($response)
    {
        return Utils::jsonDecode(
            $response->getBody()->getContents()
        );
    }
}