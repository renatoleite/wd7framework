<?
/**
  * Utilidades class, utils.class.php
  * Utils management
  * @category classes
  *
  * @author Renato Leite
  * @copyright Agência WD7
  * @license http://www.agenciawd7.com.br/licencas/wd7framework
  * @version 1.0.0.0
  *
  */
class WD7Utils {
    /**
     * Retorna a data atual no padrão brasileiro
     * @return date Retorna a data atual (dd/mm/YY)
     */
    public static function CurrentDate() {
        $data = date("d/m/Y");
        return $data;
    }

    /**
     * Diminui uma data da outra
     * @param string $d1 Primeira data
     * @param string $d2 Segunda data
     * @return integer Retorna o valor em dias
     */
    public static function DecreaseDates($d1, $d2) {
        $d1 = strtotime($d1);
        $d2 = strtotime($d2);
        $lsdata = ($d2 - $d1) / 86400;
        return round($lsdata);
    }

    /**
     * Previne de SQL Injection
     * @param string $sql String a ser verificada
     * @return string String verificada e limpa
     */
    public static function Firewall($sql) {
        /** Isso previne problemas com o isset*/
        if(Trim($sql) == ''){
            return null;
        }
        $sql1 = preg_replace(sql_regcase("/(from|select|insert|delete|where|drop table|show tables|#|\*|\'|'|\|--|\\\\)/"), "", $sql);
        $sql2 = trim($sql1);
        $sql3 = addslashes($sql2);
        return $sql3;
    }

    /**
     * Zera uma string
     * @param string $valor String a ser zerada
     * @param integer $tam Numero de caracteres que a string tem q ter com o zeros
     * @return string Retorna a string ja com os zeros
     */
    public static function FillWithZero($valor, $tam) {
        $tamanho = strlen($valor);
        $qntzero = ($tam - $tamanho);
        $resul = '';
        $i = 1;
        while ($i <= $qntzero) {
            $resul = $resul . '0';
            $i++;
        }
        return $resul . $valor;
    }

    /**
     * Transforma o mês númerico em extenso, por exemplo: 2 -> fevereiro
     * @param integer $data O numero do mês
     * @return string O mês
     */
    public static function MonthToExt($data) {
        if($data == 1){ return 'Janeiro'; }
        if($data == 2){ return 'Fevereiro'; }
        if($data == 3){ return 'Março'; }
        if($data == 4){ return 'Abril'; }
        if($data == 5){ return 'Maior'; }
        if($data == 6){ return 'Junho'; }
        if($data == 7){ return 'Julho'; }
        if($data == 8){ return 'Agosto'; }
        if($data == 9){ return 'Setembro'; }
        if($data == 10){ return 'Outubro'; }
        if($data == 11){ return 'Novembro'; }
        if($data == 12){ return 'Dezembro'; }
    }

    /**
     * Pega so o dia da data, por exemplo: 10/02/1992 -> 10)
     * @param string $data A data
     * @return integer O dia
     */
    public static function GetDay($data) {
        setlocale(LC_ALL, "pt_BR", "ptb");
        return strftime("%A", strtotime($data));
    }

    /**
     * Formata data no padrão brasileiro
     * @param string $data A data em sí
     * @return string A data formatada
     */
    public static function FormatDate($data) {
        $ano = substr($data, 0, 4);
        $mes = substr($data, 5, 2);
        $dia = substr($data, 8, 2);
        return $dia . '/' . $mes . '/' . $ano;
    }

    /**
     * Formata a data é a hora no padrão brasileiro
     * @param datetime $datetime A data e hora
     * @return string Data convertida
     */
    public static function FormatDateTime($datetime){
        $ano = substr($datetime, 0, 4);
        $mes = substr($datetime, 5, 2);
        $dia = substr($datetime, 8, 2);
        $hora = substr($datetime, 10, 9);
        return $dia . '/' . $mes . '/' . $ano.$hora;
    }

    /**
     * Envia os dados do upload
     * @param string $file O Arquivo
     * @param string $path O nome da imagem
     * @param string $caminho Caminho da imagem
     * @param integer $maxdim Dimensão da imagem de miniatura
     * @param integer $maxdimImgGrande Dimensão da imagem grande
     * @param integer $maxsize Tamanho maximo do arquivo
     * @return int Retorna o status do upload
     */
    private static function LoadImage($file, $path, $caminho, $maxdim, $maxdimImgGrande, $maxsize = 5000000) {
        if (is_uploaded_file($file[tmp_name])) {
            if (eregi("^image\/(jpeg)$", $file["type"])) {
                if ($file[size] < $maxsize) {
                    list($larg_orig, $alt_orig) = @getimagesize($file[tmp_name]);
                    $razao_orig = $larg_orig / $alt_orig;
                    if ($razao_orig > 1) {
                        //NOVA RESOLUÇÃO PARA AS IMAGENS GRANDES
                        $altG = $maxdimImgGrande / $razao_orig;
                        $largG = $maxdimImgGrande;
                        //NOVA RESOLUÇÃO PARA AS IMAGENS PEQUENAS
                        $alt = $maxdim / $razao_orig;
                        $larg = $maxdim;
                    } else {
                        //NOVA RESOLUÇÃO PARA AS IMAGENS GRANDES
                        $largG = $maxdimImgGrande * $razao_orig;
                        $altG = $maxdimImgGrande;
                        //NOVA RESOLUÇÃO PARA AS IMAGENS PEQUENAS
                        $larg = $maxdim * $razao_orig;
                        $alt = $maxdim;
                    }
                    $image_p = imagecreatetruecolor($largG, $altG);
                    $image = imagecreatefromjpeg($file[tmp_name]);
                    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $largG, $altG, $larg_orig, $alt_orig);
                    $imagem_nova = imagecreatetruecolor($larg, $alt);
                    @imagecopyresampled($imagem_nova, $image, 0, 0, 0, 0, $larg, $alt, $larg_orig, $alt_orig);
                    @imagejpeg($image_p, $caminho . "/g/" . $path, 100) ? 1 : 5;
                    return (@imagejpeg($imagem_nova, $caminho . "/p/" . $path, 100)) ? 1 : 5;
                }
                return 4;
            }
            return 3;
        }
        return 2;
    }

    /**
     * Faz upload da imagem
     * @param string $foto A foto $_FILES[''];
     * @param string $caminho O caminho para onde a imagem vai
     * @return string
     */
    public static function UploadImage($foto, $caminho) {
        $path = md5(uniqid(time())) . "." . "jpg";
        $up = self::LoadImage($foto, $path, $caminho, 142, 600);
        switch ($up) {
            case 1:
                return "[Ok]" . $path;
                break;
            case 2:
                return "Arquivo não enviado!";
                break;
            case 3:
                return "O arquivo não é do tipo Jpg!";
                break;
            case 4:
                return "O arquivo é maior do que o permitido!";
                break;
            case 5:
                return "Ocorreu algum erro durante o redimensionamento!";
        }
    }

    /**
     * Formata CPF ou CNPJ
     * @param string $campo Valor do CPF ou CNPJ
     * @return string Retorna formatado
     */
    static function FormatCPFCNPJ($campo) {
        /**retira formato*/
        $codigoLimpo = ereg_replace("[' '-./ t]", '', $campo);
        /**pega o tamanho da string menos os digitos verificadores*/
        $tamanho = (strlen($codigoLimpo) - 2);
        /**verifica se o tamanho do código informado é válido*/
        if ($tamanho != 9 && $tamanho != 12) {
            return false;
        }
        /**seleciona a máscara para cpf ou cnpj*/
        $mascara = ($tamanho == 9) ? '###.###.###-##' : '##.###.###/####-##';

        $indice = -1;
        for ($i = 0; $i < strlen($mascara); $i++) {
            if ($mascara[$i] == '#')
                $mascara[$i] = $codigoLimpo[++$indice];
        }
        /**retorna o campo formatado*/
        $retorno = $mascara;
        return $retorno;
    }
}
?>