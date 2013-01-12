<?
/**
  * Encripta os dados class, encryption.class.php
  * Encryption management
  * @category classes
  *
  * @author Renato Leite
  * @copyright Agência WD7
  * @license http://www.agenciawd7.com.br/licencas/wd7framework
  * @version 1.0.0.0
  *
  */
class WD7Encryption{
    /**
     * Metodo para criptografia SHA1
     * @param string $string A string a ser criptografada
     * @param string $key A chave da criptografia
     * @return string A string criptografada
     */
    public static function EncodeSHA1($string, $key) {
        $key = sha1($key);
        $strLen = strlen($string);
        $keyLen = strlen($key);
        for ($i = 0; $i < $strLen; $i++) {
            $ordStr = ord(substr($string,$i,1));
            if ($j == $keyLen) { $j = 0; }
            $ordKey = ord(substr($key,$j,1));
            $j++;
            $hash .= strrev(base_convert(dechex($ordStr + $ordKey),16,36));
        }
        return $hash;
    }

    /**
     * Metodo para descriptografar SHA1
     * @param string $string A Hash a ser descriptografada
     * @param string $key A chave da criptografia
     * @return string Retorna a string descriptografada
     */
    public static function DecodeSHA1($string,$key) {
        $key = sha1($key);
        $strLen = strlen($string);
        $keyLen = strlen($key);
        for ($i = 0; $i < $strLen; $i+=2) {
            $ordStr = hexdec(base_convert(strrev(substr($string,$i,2)),36,16));
            if ($j == $keyLen) { $j = 0; }
            $ordKey = ord(substr($key,$j,1));
            $j++;
            $hash .= chr($ordStr - $ordKey);
        }
        return $hash;
    }
}
?>
