<?php
class Mensagem
{
    private static $mensagensOk;
    private static $totalOk;
    private static $mensagensAtencao;
    private static $totalAtencao;
    private static $mensagensErro;
    private static $totalErro;
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
        self::$totalOk++;
    }

    public static function addAtencao($mensagem) {
        self::$mensagensAtencao[] = latinToUTF('ATENÇÃO: ') . htmlentities($mensagem);
        self::$totalAtencao++;
    }

    public static function addErro($mensagem) {
        self::$mensagensErro[] = 'ERRO: ' . htmlentities($mensagem);
        self::$totalErro++;
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
        if (self::$totalErro) {
            $saida[] = self::getErro();
            $saida[] = self::$separador . 'Total de erros: ' . self::$totalErro;
            if (self::$totalAtencao) {
                $saida[] = self::getAtencao();
                $saida[] = self::$separador . latinToUTF('Total de atenções: ') . self::$totalAtencao;
            }
            return implode(self::getSeparador(), $saida);
        } else {
            $saida = array();
            if (self::$totalAtencao) {
                $saida[] = self::getAtencao();
                $saida[] = self::$separador . latinToUTF('Total de atenções: ') . self::$totalAtencao;
            }
            if (self::$totalOk) {
                $saida[] = self::getOk();
                $saida[] = self::$separador . 'Total de Ok: ' . self::$totalOk;
            }
            return implode(self::getSeparador(), $saida);
        }
    }
}
