<?
/**
  * E-mail class, email.class.php
  * E-mail management
  * @category classes
  *
  * @author Renato Leite
  * @copyright Agência WD7
  * @license http://www.agenciawd7.com.br/licencas/wd7framework
  * @version 1.0.0.0
  *
  */
class WD7Email{
    /** @var string Nome do remetente */
    public static $name;
    /** @var string E-mail do destinatario */
    public static $email;
    /** @var string Telefone do remetente */
    public static $phone;
    /** @var string Assunto do e-mail  */
    public static $subject;
    /** @var string Comentários do e-mail */
    public static $comments;
    /** @var string Remetente */
    public static $to;
    /** @var string Cabeçalho em html (caso tiver) */
    public static $header;

    /**
     * Valida se todos os dados do email foram preenchidos
     * @return boolean Se validou ou não
     */
    private static function ValidateEmailData(){
        /** Valida se os campos foram preenchidos */
        if(!isset(self::$name)){ return False; }
        if(!isset(self::$email)){ return False; }
        if(!isset(self::$subject)){ return False; }
        if(!isset(self::$comments)){ return False; }
        if(!isset(self::$to)){ return False; }
        /** Caso todos os campos tenham sido preenchidos */
        return True;
    }

    /**
     * Envia o email
     * @return boolean Retorno se enviou com sucesso ou não o e-mail
     */
    public static function Send(){
        /** Valida se todos os dador foram preenchidos */
        if(self::ValidateEmailData()){
            $lsEmail = 'From: ' . self::$email . "\r\n" .
                         'Reply-To:'. self::$email . "\r\n" ;
			 $lsMsg = (isset(self::$header)? self::$header . '\n\n' : '') . "Nome: " . self::$name. "\n" .
                                  (isset(self::$phone) ? "Telefone: " . self::$phone . "\n" : '') . "Mensagem: " . self::$comments . "\n";
            if(mail(self::$to, self::$subject, $lsMsg, $lsEmail)){
                    return True;
            }else{
                    return False;
            }
        }else{
            return False;
        }
    }
}
?>
