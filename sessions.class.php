<?
/**
  * Sess�es class, sessions.class.php
  * Session management
  * @category classes
  *
  * @author Renato Leite
  * @copyright Ag�ncia WD7
  * @license http://www.agenciawd7.com.br/licencas/wd7framework
  * @version 1.0.0.0
  *
  */
class WD7Sessions{
    /**
     * Metodo para registrar o valor na sessao
     * @param string $session Nome da sess�o
     * @param undeclared $value Valor que a sess�o vai receber
     */
    public static function RegisterSession($session, $value){
        $_SESSION[$session] = $value;
    }

    /**
     * Metodo para limpar uma sessao
     * @param string $session Nome da sess�o
     */
    public static function DestroySession($session){
        unset($_SESSION[$session]);
    }

    /**
     * Metodo para retorna o valor de uma sess�o
     * @param type $session
     * @return type
     */
    public static function GetSession($session){
        return $_SESSION[$session];
    }
}
?>
