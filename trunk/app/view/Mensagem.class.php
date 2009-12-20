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

    public static function setSeparador($separador) {
        self::$separador = $separador;
    }

    public static function getSeparador() {
        if (self::$separador == '') {
            return '<br/>';
        } else {
            return self::$separador;
        }
    }

    public static function addOk($mensagem) {
        self::$mensagensOk[] = 'OK: ' .  htmlentities($mensagem);
    }

    public static function addAtencao($mensagem) {
        self::$mensagensAtencao[] = 'ATEN&Ccedil;&Atilde;O: ' . htmlentities($mensagem);
    }

    public static function addErro($mensagem) {
        self::$mensagensErro[] = 'ERRO: ' . htmlentities($mensagem);
    }

    public static function getOk() {
        if (is_array(self::$mensagensOk)) {
            return implode(self::getSeparador(), self::$mensagensOk);
        } else {
            return '';
        }
    }

    public static function getAtencao() {
        if (is_array(self::$mensagensAtencao)) {
            return implode(self::getSeparador(), self::$mensagensAtencao);
        } else {
            return '';
        }
    }

    public static function getErro() {
        if (is_array(self::$mensagensErro)) {
            return implode(self::getSeparador(), self::$mensagensErro);
        } else {
            return '';
        }
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
            return implode(self::getSeparador(), $saida);
        }
    }
}
