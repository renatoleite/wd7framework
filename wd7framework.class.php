<?

/**Registra a classe de conexão ao banco de dados*/
require(dirname(__FILE__) . '/inc/class/db.class.php');

/*Registra as classes que podem ser instanciadas pelo framework*/
require(dirname(__FILE__) . '/inc/class/base.class.php');
require(dirname(__FILE__) . '/inc/class/util.class.php');
require(dirname(__FILE__) . '/inc/class/transaction.class.php');
require(dirname(__FILE__) . '/inc/class/regsession.class.php');
require(dirname(__FILE__) . '/inc/class/encrypt.class.php');

/**Classe que estancia todas as outras, para não precisar extender (Object Model)*/
class WD7Framework {
    public function Object($class) {
        return $Object = Object::getInstance($class);
    }
}

/*Classe que gerencia as instancias*/
class Object{
    /**Propiedade que recebe a instancia atual*/
    private static $instance;

    /**Grupo onde fica guardado as instancias*/
    private static $InstanceCollection = array();

    /**Metodo para adicionar uma instancia ao grupo*/
    private function AddItem($nameclass, $obj){
        self::$InstanceCollection[$nameclass] = $obj;
    }

    /**Metodo para deletar uma instancia do grupo*/
    public function DelItem($nameclass){
        unset(self::$InstanceCollection[$nameclass]);
    }

    /**Metodo que busca a instancia no grupo e retorna ela*/
    private function GetObj($nameclass, $tipo=null){
        return ($tipo == 'consulta' ? (isset(self::$InstanceCollection[$nameclass]) ? true : false ) : self::$InstanceCollection[$nameclass] ) ;
    }

    /**Verifica se o objeto ja foi instanciado, se sim, retorna o objeto, se não, o estancia*/
    public static function getInstance($class) {
        if (!isset(self::$instance)){
            /**Se não existe nenhum objeto instanciado, cria ele*/
            self::$instance = new $class;                        
        }elseif((isset(self::$instance)) && ((self::$instance instanceof $class)==false)){
            /**Se existe um objeto instanciado e ele for diferente da classe passada, guarda o atual e cria um novo*/
            if(self::GetObj($class, 'consulta') == false){
                self::AddItem(get_class(self::$instance), self::$instance);
                self::$instance = new $class;
            }else{                
                /**Verifica se a instancia atual ja tinha sido guardado, caso não, guarda ela*/
                if(self::GetObj(get_class(self::$instance), 'consulta') == false){                
                    self::AddItem(get_class(self::$instance), self::$instance);                    
                }                
                self::$instance = self::GetObj($class);
            }
        }else{
            /**Se a classe for a mesma que esta instanciada devolve ela*/
            self::$instance = self::$instance;
        }
        return self::$instance;
    }
}

?>