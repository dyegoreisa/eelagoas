<?php
class Mensagem
{
    private static $mensagensOk;
    private static $mensagensAtencao;
    private static $mensagensErro;
    private static $separador;

    private function __construct() {
        
    }

    public static function begin() {
        self::$mensagensOk      = array();
        self::$mensagensAtencao = array();
        self::$mensagensErro    = array();
    }

    public static function addOk($mensagem) {
        self::$mensagensOk[] = 'OK: ' .  $mensagem;
    }

    public static function addAtencao($mensagem) {
        self::$mensagensAtencao[] = 'ATENÇÃO: ' . $mensagem;
    }

    public static function addErro($mensagem) {
        self::$mensagensErro[] = 'ERRO: ' . $mensagem;
    }

    public static function getOk() {
        return implode(self::$separador, self::$mensagensOk);
    }

    public static function getAtencao() {
        return implode(self::$separador, self::$mensagensAtencao);
    }

    public static function getErro() {
        return implode(self::$separador, self::$mensagensErro);
    }

    public static function setSeparador($separador) {
        self::$separador = $separador;
    }

    public static function fetch() {
        if (count(self::$mensagensErro)) {
            return self::getErro();
        } else {
            $saida = array();
            if (count(self::$mensagensAtencao)) {
                $saida[] = self::getAtencao();
            }
            if (count(self::$mensagensOk)) {
                $saida[] = self::getOk();
            }
            return implode(self::$separador, $saida);
        }
    }
}
