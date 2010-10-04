<?php
class Result
{
    /**
     * Array com o nome e valor dos campos usado com as funções __set e __get
     * 
     * @var mixed
     * @access private
     */
    private $columns;

    public function __construct(array $column = array())
    {
        $this->columns = $column;
    }

    public function __get($column)
    {
        if (array_key_exists($column, $this->columns)) {
            return $this->columns[$column];
        } else {
            throw new Exception("Coluna $column não definida.");
            return null;
        }
    }

    public function __set($name, $value)
    {
        $this->columns[$name] = $value;
    }

    public function getArrayColumns(array $columns = array())
    {
        if (empty($columns)) {
            return $this->columns;
        } else {
            $tmp = array();
            foreach ($columns as $val) {
                $tmp[$val] = $this->columns[$val];
            }
            return $tmp;
        }
    }

    public function getFormatedColumns(array $columns)
    {
        $tmp = array();
        if (!empty($columns)) {
            foreach ($columns as $key => $column) {
                $tmp[$key] = $this->columns[$key];
                if ($key == 'nome_parametro' && $this->columns['tabela'] != '') {
                    $tmp[$key] .= "\n" . $this->getListExtraSeparads("\n");
                } 

                if ($key == 'nome_categoria' && $this->columns[$key] == 'Perfil') {
                    $tmp[$key] .= "\n" . str_repeat(' ', 6) . $this->columns['nome_categoria_extra'] 
                               . ' - ' . $this->columns['valor_categoria_extra'];
                }
            }
            return $tmp;
        } else {
            return array();
        }
    }
}

