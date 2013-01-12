<?
/**
  * Sessões class, sessions.class.php
  * Session management
  * @category classes
  *
  * @author Renato Leite
  * @copyright Agência WD7
  * @license http://www.agenciawd7.com.br/licencas/wd7framework
  * @version 1.0.0.0
  *
  */
class WD7Sessions{
    /**
     * Metodo para registrar o valor na sessao
     * @param string $session Nome da sessão
     * @param undeclared $value Valor que a sessão vai receber
     */
    public static function RegisterSession($session, $value){
        $_SESSION[$session] = $value;
    }

    /**
     * Metodo para limpar uma sessao
     * @param string $session Nome da sessão
     */
    public static function DestroySession($session){
        unset($_SESSION[$session]);
    }

    /**
     * Metodo para retorna o valor de uma sessão
     * @param type $session
     * @return type
     */
    public static function GetSession($session){
        return $_SESSION[$session];
    }
}
?>
