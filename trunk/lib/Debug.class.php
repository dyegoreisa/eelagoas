<?php
/**
 * Singleton para debug
 * 
 * @package   Debug
 * @version   $id$
 * @copyright 2009 - Link Simbólico
 * @author    Dyego Reis de Azevedo <dyegoreisa@yahoo.com.br> 
 * @license   PHP Version 5.2 {@link http://www.php.net/license/}
 */
class Debug
{
    /**
     * Definição do arquivo
     */
    const LOG_FILE = '/var/log/php/debug.log';

    /**
     * Salva dados do debug no arquivo de log
     * 
     * @param string $contents 
     * @static
     * @access private
     * @return void
     */
    private static function salvar($contents) 
    {
        if (file_put_contents(self::LOG_FILE, $contents, FILE_APPEND) === false) {
            throw new Exception('Não foi possível salvar o arquivo.');
        }
    }

    /**
     * Apaga o arquivo de debug
     * 
     * @access public
     * @return void
     */
    public static function clear()
    {
        if (file_put_contents(self::LOG_FILE, "BEGIN:\n") === false) {
            throw new Exception('Não foi possível limpar o arquivo.');
        }
    }
    
    /**
     * Mostra os dados de uma variável na tela
     * 
     * @param mixed $variable 
     * @param string $name 
     * @static
     * @access public
     * @return void
     */
    public static function show($variable, $name = '') 
    {
        self::dump($variable, $name, false, true);
    }

    /**
     * Efetua o debug de uma variável
     * 
     * @param mixed $variable 
     * @param string $name 
     * @param bool $dump 
     * @param bool $print 
     * @static
     * @access public
     * @return void
     */
    public static function dump($variable, $name = '', $dump = false, $print = false) 
    {
        $lineSize = 50;
        if ($print) {
            $begin = "<hr><pre>$name: ";
            $end   = '</pre><hr>';
        } else {
            $begin = '+' . str_repeat('-', $lineSize) . "+\n$name: ";
            $end   = "\n+" . str_repeat('-',$lineSize) . "+\n\n";
        }

        if (!$print) {
            ob_start();
        }

        print $begin;

        if ($dump) {
            var_dump($variable);
        } else {
            print_r($variable);
        }

        print $end;

        if (!$print) {
            $contents = ob_get_contents();
            ob_end_clean();
            self::salvar($contents);
        }
    }

    /**
     * Exibe informações de um objeto
     *
     *  - Como informações básicas são mostrados o nome da classe e o nome
     *    completo do arquivo.
     *  - O parâmetro $options recebe as seguintes letras:
     *    p - exibe as propriedades
     *    s - exibe as propriedades estáticas
     *    m - exibe os métodos
     *    i - exibe os nomes das interfaces
     *    l - exibe o número das linhas inicial e final da classe
     *    c - exibe o construtor
     *    d - exibe a documentação da classe
     * 
     * @param mixed $variable 
     * @param string $name 
     * @param bool $print 
     * @param string $options 
     * @static
     * @access public
     * @return void
     */
    public static function reflect($variable, $name = '', $print = false, $options = '') 
    {
        $lineSize = 50;
        if ($print) {
            if (empty($name)) {
                $begin = "<hr><pre>";
            } else {
                $begin = "<hr><pre>$name ";
            }
            $end   = '</pre><hr>';
            $nl    = '<br/>';
        } else {
            if (empty($name)) {
                $begin = '+' . str_repeat('-', $lineSize) . '+';
            } else {
                $begin = '+' . str_repeat('-', $lineSize) . "+\n$name ";
            }
            $end   = "\n+" . str_repeat('-',$lineSize) . "+\n\n";
            $nl    = "\n";
        }

        if (!$print) {
            ob_start();
        }

        print $begin;

        $reflector = new ReflectionClass($variable);
        print $nl . 'Class name: ' . $reflector->getName(); // Gets class name
        print $nl . 'a filename: ' . $reflector->getFileName(); // Gets a filename

        if (preg_match('/p/', $options) != false) {
            print $nl . 'properties: '; print_r($reflector->getProperties()); // Gets properties
        }

        if (preg_match('/s/', $options) != false) {
            print $nl . 'static properties: '; print_r($reflector->getStaticProperties()); // Gets static properties
        }

        if (preg_match('/m/', $options) != false) {
            print $nl . 'a list of methods: '; print_r($reflector->getMethods()); // Gets a list of methods
        }

        if (preg_match('/i/', $options) != false) {
            print $nl . 'the interface names: '; print_r($reflector->getInterfaceNames()); // Gets the interface names
        }

        if (preg_match('/l/', $options) != false) {
            print $nl . 'starting line number:' . $reflector->getStartLine(); // Gets starting line number
            print $nl . 'end line: ' . $reflector->getEndLine(); // Gets end line
        }

        if (preg_match('/c/', $options) != false) {
            print $nl . 'constructor: ' . $reflector->getConstructor(); // Gets constructor
        }

        if (preg_match('/d/', $options) != false) {
            print $nl . 'doc comments: ' . $reflector->getDocComment(); // Gets doc comments
        }

        print $end;

        if (!$print) {
            $contents = ob_get_contents();
            ob_end_clean();
            self::salvar($contents);
        }
    }
}
?>
