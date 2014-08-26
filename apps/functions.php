<?php

//Alternative connection
require_once("_alternative.php");

add_action( 'admin_menu', 'my_remove_menu_pages' );
function my_remove_menu_pages() {
    // remove_menu_page('options-general.php');  
    // remove_menu_page('tools.php');  
    //remove_menu_page('plugins.php');  
    //remove_menu_page('index.php');  
    //remove_menu_page('edit.php');  
    //remove_menu_page('edit-comments.php');  
    //remove_menu_page('themes.php');  
    //remove_menu_page('users.php');  
}


function dashboard_redirect($url) {
    global $current_user;
    // is there a user ?
             $url = 'wp-admin/edit.php?post_type=page';
        
        return $url;
    
}
add_filter('login_redirect', 'dashboard_redirect');  

//anti injection
function anti_injection($str){
    // verifica se o valor da string é somente numérico 
    if (!is_numeric($str)) { 
    //verifica se o magic_quotes está habilitado, se sim tira o escape da string, caso não mantem o valor digitado.
    $str = get_magic_quotes_gpc() ? stripslashes(strip_tags($str)) : $str;
    // verifica se a função mysql_real_escape_string está habilitada, se sim roda ela, se não usa o mysql_escape_string 
    $str = function_exists('mysql_real_escape_string') ? mysql_real_escape_string($str) : mysql_escape_string($str);
    }
    //retorna o valor
    return $str;
}

//Filtra todos os campos recebidos
function getCampo($campo) {
    return anti_injection($campo);
}

//Retorna endereço do servidor
function urlAtual() {
    $prt = "http://";
    $ser = $_SERVER['HTTP_HOST']; 
    $end = $_SERVER ['REQUEST_URI'];
    $url_f = $prt . $ser . $end;
    return($url_f);
}

//retorna data hora atual
function dataHoraAtual() {
    return date("Y-m-d H:i:s");
}

//retorna data hora atual
function horaAtual() {
    return date("H:i:s");
}

//Retorna o link com a api do site
function urlApi($url) {
		if (strpos($url, "https://")===false) {
			$typeUrl = "http://";
		} else {
			$typeUrl = "https://";
		}
    $array_url = parse_url($url);
    $array_url_host = $array_url['host']; 
    if (strpos($url, "/api")) {
        $url_api = $url;
    } else {
        $url_api = $typeUrl . $array_url_host . "/api";
    }
    return($url_api);
}
function urlHost($url) {
		if (strpos($url, "https://")===false) {
			$typeUrl = "http://";
		} else {
			$typeUrl = "https://";
		}
    $array_url = parse_url($url);
    $array_url_host = $array_url['host']; 
    return($typeUrl . $array_url_host);
}

function urlHostLimpa($url) {
    $array_url = parse_url($url);
    $array_url_host = $array_url['host']; 
    return($array_url_host);
}

add_filter('query_vars', 'parameter_queryvars' );
function parameter_queryvars( $qvars ) {
    $qvars[] = 'repository';
    $qvars[] = 's';
    $qvars[] = 'search';
    $qvars[] = 'option';
    $qvars[] = 'filter';
    return $qvars;
}


#private $api_version = '1';
#private $base_url = 'http://datacatalogs.org/api/%d/';

function getApiUrl($urlRecepApi) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL,$urlRecepApi);
    $result=curl_exec($ch);
    curl_close($ch);
    #$result = file_get_contents($urlRecepApi);
    $result = json_decode($result);
    return $result->{'version'};
}


function getCountApps(){
    /* Seleciona todos as mesas do banco de dados */
    $strSQL = "select count(*) as qtd from apps;";

    /* Envia a consulta e armazena os dados */
    $ObjRst = mysql_query($strSQL) or die("Erro ao selecionar qtd de Apps.");

    /* Percorre o objeto enquanto exitir dados */
    if ($row = mysql_fetch_assoc($ObjRst)) {
        $qtdApps = $row['qtd'];
    }
    return $qtdApps;
}


?>