<?php
/**
 * debug 
 *
 * Mostra o conteúdo de uma variável de forma legível
 * 
 * @param mixed $v 
 * @param string $n 
 * @param mixed $dump 
 * @param mixed $exit 
 * @access public
 * @return void
 */
function debug ($v, $n = '', $dump = false, $exit = false ) {
    echo "<hr><pre>{$n}:";

    if( $dump )
        var_dump($v);
    else
        print_r($v);

    echo '</pre><hr>';

    if( $exit ) exit;
}

function loadModules( $dir ) {
    if ( $dh = opendir( $dir ) ) {
        while ( ( $file = readdir( $dh ) ) !== false ) {
            if( preg_match('/^\w+\.class\.php$/', $file) ) {
                $eval = "require_once '{$dir}/{$file}';";
                //debug( $eval, '$eval' );
                eval($eval);
            }
        }
        closedir($dh);
    }
}

function unir_arrays( $a, $b ) {
    if( is_array( $a ) ) {
        if( is_array( $b ) ) {
            $novo_array = array_merge( $a, $b );
        } else {
            $novo_array = $a;
        }
    } elseif( is_array( $b ) ) {
        $novo_array = $b;
    } else {
        $novo_array = array();
    }

    return $novo_array;
}

/**
 * Remove itens do array baseado num array de chaves
 * 
 * @param array $elementos - Array a ter seus itens removidos
 * @param array $chaves - Array de chaves para remover
 * @param bool $inverte - True para removar os campos do array de chaves e false para remover os outro campos
 * @access public
 * @return array - Array com os elementos removidos
 */
function remove_elementos_array($elementos, $chaves, $inverte = false) {
    if (!$inverte) {
        foreach ($chaves as $chave) {
            unset($elementos[$chave]);   
        }
    } else {
        $arrAuxiliar = array();
        foreach ($chaves as $chave) {
            if (isset($elementos[$chave])) {
                $arrAuxiliar[$chave] = $elementos[$chave];
            }
        }
        unset($elementos);
        $elementos = $arrAuxiliar;
    }
    return $elementos;
}
