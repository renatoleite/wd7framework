<?
/**
  * Banco de dados class, db.class.php
  * Database management
  * @category classes
  *
  * @author Renato Leite
  * @copyright Agência WD7
  * @license http://www.agenciawd7.com.br/licencas/wd7framework
  * @version 1.0.0.0
  *
  */
class WD7DB{
    /** @var string Recebe o endereço do servidor */
    private static $host;
    /** @var string Recebe o usuário do banco de dados */
    private static $user;
    /** @var string Senha do banco de dados */
    private static $pass;
    /** @var string Nome do banco de dados*/
    private static $data;

    /** Destroi a conexão com BD, evitar deixar ela aberta depois que o framework foi destruido da memoria */
    private function __Destruct() {
        $this->Disconnect();
    }

    /**
     * Conecta o servidor
     * @return MySQL Objeto de conexão do MySQL
     */
    private static function Connect() {
        return mysql_connect(self::$host, self::$user, self::$pass) or die(self::SetErro(mysql_error()));
    }

    /**
     * Retorna o status da conexão com o MySQL
     * @return string Retorna dizendo se está conectado ou não
     */
    public static function GetStatusConnection() {
        return (self::Connect() == true) ? 'Conectado' : 'Erro de Conexão';
    }

    /**
     * Seta o banco de dados que vai se conectar
     * @return dbConnection Se a conexão foi bem sucedida.
     */
    private static function SetDB() {
        return mysql_select_db(self::$data) or die(self::SetErro(mysql_error()));
    }

    /**
     * Seta os erros para o arquivo host.log por exemplo: 127.0.0.1.log
     * @param string $erro A string que vai ser salva no log
     */
    public static function SetErro($erro) {
        $arqerro = Fopen("../log-" . self::$host . ".log", "a");
        $escreve = Fwrite($arqerro, "[" . date("d/m/y H:i") . "] - " . $erro . "\r\n");
        fclose($arqerro);
        echo $erro;
    }

    /**
     * Desconecta do MySQL
     * @return boolean Se desconectou com sucesso
     */
    public static function Disconnect() {
        return @mysql_close();
    }

    /**
     * Registra a configuração do banco de dados e o conecta
     * @return dbConn Retorna a conexão.
     */
    public static function InitializeMySQL(){
        self::$host = _WD7DB_Host_;
        self::$user = _WD7DB_User_;
        self::$pass = _WD7DB_Pass_;
        self::$data = _WD7DB_Data_;
        self::Connect();
        return self::SetDB();
    }
}
?>
