<?

class Utils{

	public function DataAtual(){
		$data=date("d/m/Y");
		return $data;
	}

	public function DiminuirDatas($d1, $d2){
		$d1=strtotime($d1);
		$d2=strtotime($d2);
		$lsdata = ($d2-$d1)/86400;
		return round($lsdata);
	}

	public function firewall($sql){
		$sql = preg_replace(sql_regcase("/(from|select|insert|delete|where|drop table|show tables|#|\*|\'|'|\|--|\\\\)/"),"",$sql);
		$sql = trim($sql);
		$sql = addslashes($sql);
		return $sql;
	}

	public function mestoext($data){		
		$mexextenso = ($data == 1) 	? 'Janeiro' 	: $mexextenso;
		$mexextenso = ($data == 2) 	? 'Fevereiro' 	: $mexextenso;
		$mexextenso = ($data == 3) 	? 'Março' 		: $mexextenso;
		$mexextenso = ($data == 4) 	? 'Abril' 		: $mexextenso;
		$mexextenso = ($data == 5) 	? 'Maio' 		: $mexextenso;
		$mexextenso = ($data == 6) 	? 'Junho' 		: $mexextenso;
		$mexextenso = ($data == 7) 	? 'Julho' 		: $mexextenso;
		$mexextenso = ($data == 8) 	? 'Agosto' 		: $mexextenso;
		$mexextenso = ($data == 9) 	? 'Setembro' 	: $mexextenso;
		$mexextenso = ($data == 10) ? 'Outubro' 	: $mexextenso;
		$mexextenso = ($data == 11) ? 'Novembro' 	: $mexextenso;
		$mexextenso = ($data == 12) ? 'Dezembro' 	: $mexextenso;
		return $mexextenso;
	}

	public function getDia($data){			
		setlocale(LC_ALL, "pt_BR", "ptb");
		return strftime("%A", strtotime($data));
	}
	
	public function FormatarData($data){
        $ano=substr($data,0,4);
        $mes=substr($data,5,2);
        $dia=substr($data,8,9);   
        return  $dia.'/'.$mes.'/'.$ano;
	}	

	private function doUploadImg($file,$path,$caminho,$maxdim,$maxdimImgGrande,$maxsize=5000000){
	if(is_uploaded_file($file[tmp_name])){
		if(eregi("^image\/(jpeg)$", $file["type"])){
			if($file[size] < $maxsize){
				list($larg_orig, $alt_orig) = @getimagesize($file[tmp_name]);
		
				$razao_orig = $larg_orig/$alt_orig;
				if($razao_orig > 1){
					//NOVA RESOLUÇÃO PARA AS IMAGENS GRANDES
					$altG 	= $maxdimImgGrande/$razao_orig;
					$largG 	= $maxdimImgGrande;
					//NOVA RESOLUÇÃO PARA AS IMAGENS PEQUENAS
					$alt 	= $maxdim/$razao_orig;
					$larg 	= $maxdim;
				}else{
					//NOVA RESOLUÇÃO PARA AS IMAGENS GRANDES
					$largG 	= $maxdimImgGrande*$razao_orig;
					$altG 	= $maxdimImgGrande;					
					//NOVA RESOLUÇÃO PARA AS IMAGENS PEQUENAS
					$larg 	= $maxdim*$razao_orig;
					$alt 	= $maxdim;				
				}
						
				$image_p = imagecreatetruecolor($largG, $altG);			
				$image = imagecreatefromjpeg($file[tmp_name]);			
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $largG, $altG, $larg_orig, $alt_orig);							
				$imagem_nova = imagecreatetruecolor($larg, $alt);	
				@imagecopyresampled($imagem_nova, $image, 0, 0, 0, 0, $larg, $alt, $larg_orig, $alt_orig);							
				@imagejpeg($image_p,$caminho."/g/".$path,100) ? 1 : 5;						
				return (@imagejpeg($imagem_nova,$caminho."/p/".$path,100)) ? 1 : 5;								
			}
			return 4;
		}
		return 3;
	}
	return 2;
	}	
	
	public function UploadImg($foto,$caminho){	
	
		$path 	= md5(uniqid(time())) . "." . "jpg";
		$up 	= $this->doUploadImg($foto,$path,$caminho,142,600);
		switch($up){
			case 1:
				return "[Ok]".$path;				
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
}
