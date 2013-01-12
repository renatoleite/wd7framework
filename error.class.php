<?
/**
  * Error class, error.class.php
  * Error management
  * @category classes
  *
  * @author Renato Leite
  * @copyright Agência WD7
  * @license http://www.agenciawd7.com.br/licencas/wd7framework
  * @version 1.0.0.0
  *
  */
class WD7Error{
    /**
     * Seta os erros para o arquivo host.log por exemplo: 127.0.0.1.log
     * @param string $erro A string que vai ser salva no log
     */
    public static function SetError($erro) {
        $arqerro = Fopen("../logerrosql.log", "a");
        $escreve = Fwrite($arqerro, "[" . date("d/m/y H:i") . "] - " . $erro . "\r\n");
        fclose($arqerro);
        echo "Desculpe foi encontrado um erro!";
    }
}

?>
