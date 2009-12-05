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

    /**
     * Array de objetos result com a lista de especie para cada parametro
     * 
     * @var mixed
     * @access private
     */
    private $extras;

    public function __construct(array $column = array()) 
    {
        $this->columns = $column;

        if (isset($this->columns['id_parametro']) && $this->columns['id_parametro'] != '' &&
            isset($this->columns['tabela'])       && $this->columns['tabela'] != ''
        ) {
            $process = new Process();
            $this->extras = $process->getExtrasByParametro($this->columns['id_parametro'], $this->columns['id_coleta'], $this->columns['tabela']);
        } else {
            $this->extras = array();
        }

    }

    public function __get($column)
    {
        if (array_key_exists($column, $this->columns)) {
            return $this->columns[$column];
        } else {
            throw new Exception("Coluna $columns não definida.");
            return null;
        }
    }

    public function __set($name, $value)
    {
        $this->columns[$name] = $value;
    }

    public function getExtras()
    {
        return $this->extras;
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

    public function getListExtraSeparads($tab)
    {
        $tmp = array();
        foreach ($this->extras as $val) {
            $tmp[] = str_repeat(' ', 6) . $val->descricao . ' - ' . $val->nome;
        }

        return implode($tab, $tmp);
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

