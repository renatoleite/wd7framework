<?

interface DB {

    public function doConecta();

    public function doDesconecta();

    public function setDB();

    public function getStatusConexao();

    public function set($prop, $value);

    public function setErro($erro);
}

abstract class DBWD7 implements DB {

    private $host;
    private $user;
    private $pass;
    private $data;

    public function doConecta() {        
        $con = mysql_connect($this->host, $this->user, $this->pass) or die($this->setErro(mysql_error()));
        return $con;
    }

    public function getStatusConexao() {
        return ($this->conectar() == true) ? 'Conectado' : 'Erro de Conexão';
    }

    public function setDB() {
        $sel = mysql_select_db($this->data) or die($this->erro(mysql_error()));
        return ($sel) ? true : false;
    }

    public function set($prop, $value) {
        $this->$prop = $value;
    }

    public function setErro($erro) {
        $arqerro = fopen("../log-" . $this->host . ".log", "a");
        $escreve = fwrite($arqerro, "[" . date("d/m/y H:i") . "] - " . $erro . "\r\n");
        fclose($arqerro);
        echo $erro;
    }

    public function doDesconecta() {
        return @mysql_close($this->conectar());
    }

}

?>
