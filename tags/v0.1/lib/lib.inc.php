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
