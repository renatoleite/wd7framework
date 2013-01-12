<?
/**
  * Trasação class, mysqltransaction.class.php
  * MySQLTransaction management
  * @category classes
  *
  * @author Renato Leite
  * @copyright Agência WD7
  * @license http://www.agenciawd7.com.br/licencas/wd7framework
  * @version 1.0.0.0
  *
  */
class WD7MySQLTransaction extends WD7DB{
    /** Inicia a transação */
    public static function StartTransaction(){
        mysql_query('START TRANSACTION');
    }

    /** Comitta a transação */
    public static function Commit(){
        mysql_query('COMMIT');
    }

    /** Rollback na transação iniciada */
    public static function RollBack(){
        mysql_query('ROLLBACK');
    }
}
?>
