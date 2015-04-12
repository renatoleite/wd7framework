<?
/**
  * Operações do baco de dados class, model.class.php
  * Model management
  * @category classes
  *
  * @author Renato Leite
  * @copyright Agência WD7
  * @license http://www.agenciawd7.com.br/licencas/wd7framework
  * @version 1.0.0.0
  *
  */
class WD7Model {
    /** @var Tabela do banco de dados */
    private static $table;
    /** @var Vai receber o where das consultas */
    private static $where;
    /** @var Recebe os fields das operações */
    private static $fields;
    /** @var Recebe os valores dos fields correspontes */
    private static $values;
    /** @var Recebe o order dos selects */
    private static $order;
    /** @var Recebe o group dos selects */
    private static $group;
    /** @var Recebe o limit dos selects */
    private static $limit;

    /**
     * Seta a tabela do banco de dados
     * @param string $lsTable Nome da tabela do banco de dados
     */
    public static function Table($lsTable){
        self::$table = $lsTable;
    }

    /**
     * Prepara o SQL antes de ser comitado
     * @param string $lsSQL O SQL a ser preparado
     * @return string O SQL depois do tramaento
     */
    private static function PrepareSQL($lsSQL){
        return str_replace("'null'", "null", $lsSQL);
    }

    /**
     * Operção generica de inclusão
     * @param connection $conn Objeto de conexão.
     * @return boolean Retorna se incluiu ou não
     */
    public static function Insert($conn = null) {
        /* Caso nenhuma conexão tenha sido passada, é usada uma avulsa, caso não
         * é usada a que foi passada. */
        if(!isset($conn)){
            $qry = mysql_query(self::MountInsert()) or die(WD7Error::SetError(mysql_error()));
        }else{
            $qry = mysql_query(self::MountInsert(), $conn) or die(WD7Error::SetError(mysql_error()));
        }
        self::ClearParameters();
        return ($qry) ? true : false;
    }

    /**
     * Retorna o Id inserido
     * @return string Id do registro inserido
     */
    public static function IdInsert(){
        return mysql_insert_id();
    }

    /**
     * Monta o select a ser executado
     * @return string O select em sí
     */
    private static function MountSelect() {
        return 'SELECT ' . self::GetFields() . ' FROM ' . self::$table . self::GetWhere() . self::GetOrder() . self::GetGroupBy() . self::GetLimit();
    }

    /**
     * Monta o update a ser executado
     * @return string O Sql do Update
     */
    private static function MountUpdate() {
        $lsFields = explode(',', self::$fields);
        $lsValues = explode(',', self::$values);
        for ($i = 0; $i < count($lsFields); $i++) {
            $lsVirgula = ($i >= 1) ? ', ' : '';
            $lsResult = $lsResult . $lsVirgula . $lsFields[$i] . ' = ' . $lsValues[$i];
        }
        return self::PrepareSQL('UPDATE ' . self::$table . ' SET ' . $lsResult . self::GetWhere());
    }

    /**
     * Monta o SQL do delete
     * @return string O SQL do delete
     */
    private static function MountDelete() {
        return self::PrepareSQL('DELETE FROM ' . self::$table . self::GetWhere());
    }

    /**
     * Monta o SQL do Insert
     * @return string O SQL do insert
     */
    private static function MountInsert() {
        return self::PrepareSQL('INSERT INTO ' . self::$table . ' (' . self::$fields . ') VALUES (' . self::$values . ')');
    }

    /**
     * Operção generica de alteração
     * @param connection $conn Objeto de conexão.
     * @return boolean Se alterou ou não
     */
    public static function Update($conn = null) {
        /* Caso nenhuma conexão tenha sido passada, é usada uma avulsa, caso não
         * é usada a que foi passada. */
        if(!isset($conn)){
            $qry = mysql_query(self::MountUpdate()) or die(WD7Error::SetError(mysql_error()));
        }else{
            $qry = mysql_query(self::MountUpdate(), $conn) or die(WD7Error::SetError(mysql_error()));
        }
        self::ClearParameters();
        return ($qry) ? true : false;
    }

    /**
     * Operação generica de exclusão
     * @param connection $conn Objeto de conexão.
     * @return boolean Se excluiu ou  não
     */
    public static function Delete($conn = null) {
        /* Caso nenhuma conexão tenha sido passada, é usada uma avulsa, caso não
         * é usada a que foi passada. */
        if(!isset($conn)){
            $qry = mysql_query(self::MountDelete())  or die(WD7Error::SetError(mysql_error()));
        }else{
            $qry = mysql_query(self::MountDelete(), $conn)  or die(WD7Error::SetError(mysql_error()));
        }
        self::ClearParameters();
        return ($qry) ? true : false;
    }

    /**
     * Operação generica para selecionar os dados
     * @param connection $conn Objeto de conexão.
     * @return boolean Retorna se o select deu certo
     */
    public static function Select($conn = null) {
        /* Caso nenhuma conexão tenha sido passada, é usada uma avulsa, caso não
         * é usada a que foi passada. */
        if(!isset($conn)){
            $qry = mysql_query(self::MountSelect()) or die(WD7Error::SetError(mysql_error()));
        }else{
            $qry = mysql_query(self::MountSelect(), $conn) or die(WD7Error::SetError(mysql_error()));
        }
        self::ClearParameters();
        return $qry;
    }

    /**
     * Retorna a query criada, tem que ser usado antes do Select(), Insert(), Delete() ou Update()
     * @param string $type Se é "select", "insert", "update" ou "delete"
     * @return string Retorna o select
     */
    public static function GetQuery($type = null) {
        if($type == 'insert') {
            $lsRetorno = self::MountInsert();
        }else if($type == 'update') {
            $lsRetorno = self::MountUpdate();
        }else if($type == 'delete') {
            $lsRetorno = self::MountDelete();
        }else{
            $lsRetorno = self::MountSelect();
        }
        return $lsRetorno;
    }

    /**
     * Retorna o número de rows de um select, tem que ser usado antes do Select()
     * @return integer Numero de rows
     */
    public static function NumRows() {
        $qry = mysql_query('SELECT ' . self::GetFields() . ' FROM ' . self::$table . self::GetWhere() . self::GetOrder() . self::GetGroupBy()) or die(parent::setErro(mysql_error()));
        $rows = mysql_num_rows($qry);
        return $rows;
    }

    /**
     * Retorna o ultimo registro de uma tabela pelo field selecionado
     * @param string $lsField Nome do field
     * @return string ultimo registro
     */
    public static function LastRecord($lsField) {
        $qry = mysql_query('SELECT ' . $lsField . ' FROM ' . self::$table . ' ORDER BY ' . $lsField . ' DESC LIMIT 1') or die(parent::setErro(mysql_error()));
        $result = mysql_fetch_array($qry);
        return $result[$lsField];
    }

    /**
     * Retorna os primary keys de uma tabela
     * @param string $lsTabela Nome da tabela
     * @return string As colunas
     */
    public static function GetPK($lsTabela) {
        $qry = mysql_query('SHOW KEYS FROM ' . (isset($lsTabela) ? $lsTabela : self::$table ) . ' WHERE Key_name = \'PRIMARY\'') or die(parent::setErro(mysql_error()));
        $result = mysql_fetch_array($qry);
        return $result['Column_name'];
    }

    /**
     * Seta a propiedade
     * @param string $prop Nome da propiedade
     * @param string $value Valor da propiedade
     */
    public static function Set($prop, $value) {
        self::$$prop = $value;
    }

    /**
     * Metodo que retorna os fields carregados pelo metodo LoadParameters
     * @return string Os fiels
     */
    private static function GetFields() {
        return (self::$fields <> '') ? self::$fields : '*';
    }

    /**
     * Retorna os valores setados pro where
     * @return string Retorna o where
     */
    private static function GetWhere() {
        $lswhere = (self::$where <> '') ? self::$where : '1=1';
        return ' WHERE ' . $lswhere;
    }

    /**
     * Retorna os valores setados pro order
     * @return string Os order setados
     */
    private static function GetOrder() {
        $lsorder = (self::$order <> '') ? ' ORDER BY ' . self::$order : '';
        return $lsorder;
    }

    /**
     * Retorna os valores setados pro groupby
     * @return string Os grupos setados
     */
    private static function GetGroupBy() {
        $lsgroup = (self::$group <> '') ? ' GROUP BY ' . self::$group : '';
        return $lsgroup;
    }

    /**
     * Retorna os valores setados pro limit
     * @return string Retorna os limits setados
     */
    private static function GetLimit() {
        $lslimit = (self::$limit <> '') ? ' LIMIT ' . self::$limit : '';
        return $lslimit;
    }

    /**
     * Metodo para criar um array de uma string
     * @param string $separador O separador em si
     * @param string $str A string a ser separada
     * @return array O Array pronto
     */
    public static function CreateArray($separador, $str) {
        return explode($separador, $str);
    }

    /**
     * Criar um arquivo de log (logint.log) com a informação passada e data de criação
     * @param string $log Os valores para o log
     */
    public static function SetLog($log) {
        $arqerro = fopen("./logint.log", "a");
        fwrite($arqerro, "[" . date("d/m/y H:i") . "] - " . $log . "\r\n");
        fclose($arqerro);
    }

    /**
     * Seta os parametros a serem usados nas operações basicas, por exemplo:
     * Para Insert e Update: LoadParameters('descricao', 'Descrição a ser inserida ou alterada', string);
     * Para Select: LoadParameters('nome');
     * @param string $lsField O Field
     * @param string $lsValor O valor a ser passado
     * @param type $lsTipo O tipo do field, por exemplo: string, integer...
     */
    public static function LoadParameter($lsField, $lsValor = null, $lsTipo = null) {
        if (isset($lsField)) {
            self::$fields = (isset(self::$fields) ? self::$fields . ',' : '') . $lsField;
        }
        if (($lsValor != 'lockNull') || ($lsValor == 0) || (is_bool($lsValor))) {
            self::$values = (isset(self::$values) ? self::$values . ',' : '') . (isset($lsTipo) ? self::ValidateValues($lsValor, $lsTipo) : $lsValor);
        }
    }

    /** Limpa os parametros */
    private static function ClearParameters() {
        self::$fields = null;
        self::$values = null;
        self::$table = null;
        self::$where = null;
        self::$order = null;
        self::$group = null;
        self::$limit = null;
    }

    /**
     * Prepara o valor pelo tipo e adiciona quoted
     * @param string $lsValue O valor a ser validadp
     * @param type $lsTipo O tipo que vai sofrer typecast
     * @return string Retorna no tipo certo, no caso de string com já com o quoted
     */
    private static function ValidateValues($lsValue, $lsTipo) {
        /* Valido para caso tiver passando um valor null */
        if (!isset($lsValue)){
            if(!is_bool($lsValue)){
                return (string) 'null';
            }
        }
        if ($lsTipo == 'integer') {
            return (integer) $lsValue;
        }
        if ($lsTipo == 'string') {
            return "'" . (string) $lsValue . "'";
        }
        if ($lsTipo == 'double') {
            return (double) $lsValue;
        }
        if ($lsTipo == 'float') {
            return (double) $lsValue;
        }
        if ($lsTipo == 'boolean') {
            return ($lsValue == true ? 1 : 0);
        }
        if ($lsTipo == 'time') {
            return "'" . date('H:m:s', strtotime($lsValue)) . "'";
        }
        if ($lsTipo == 'date') {
            return "'" . date('Y-m-d', strtotime($lsValue)) . "'";
        }
        if ($lsTipo == 'datetime') {
            return "'" . date('Y-m-d H:m:s', strtotime($lsValue)) . "'";
        }
    }

    /**
     * Select Livre
     * @param string $lsSelect O SQL
     * @return MySQL_Query Retorno do MySQL
     */
    public static function SelectSQL($lsSelect) {
        $qry = mysql_query($lsSelect) or die(WD7Error::SetError(mysql_error()));
        return $qry;
    }
}
?>
