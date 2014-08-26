<?php 
#require_once("_alternative.php");

// Display errors for demo
error_reporting(E_ALL ^ E_NOTICE);
@ini_set('display_errors', 'stdout');
ini_set('MAX_EXECUTION_TIME', -1);

//Connection WPDB
//$second_db = new wpdb(DB_USER, DB_PASSWORD, $database_name, DB_HOST);

if(!empty($_POST['submit'])) { 

	//Recebe as variaveis
	$title = mysql_real_escape_string($_POST['title']);
	$description = mysql_real_escape_string($_POST['description']);
	$urlSource = mysql_real_escape_string($_POST['urlSource']);
	$urlLogo = mysql_real_escape_string($_POST['urlLogo']);
	$comments = mysql_real_escape_string($_POST['comments']);
	$price = mysql_real_escape_string($_POST['price']); 
	$portal = mysql_real_escape_string($_POST['portal']); 

	//Adiciona Post usando comando do Wordpress
	function add_new_page($title, $post_parent = 0) {
        $post = array();                                                 
        $post['post_title'] = $title;                                    
        $post['post_content'] = '';                                
        $post['post_parent'] = $post_parent;                             
        $post['post_status'] = 'publish'; // Can be 'draft' / 'private' / 'pending' / 'future'
        $post['post_author'] = 1; // This should be the id of the author.
        $post['post_type'] = 'page';
        $post_id = wp_insert_post($post);

        // check if wp_insert_post is successful
        if(0 != $post_id) {    
            // Set the page template
            update_post_meta($post_id, '_wp_page_template', "app.php"); // Change the default template to custom template
        }                                                
        return $post_id;
    }
	$postId = add_new_page($title);

	//Insere na tabela apps
	$strSQL = "insert into apps (appId, postId, title, description, thumb, nature, comments, ";
	$strSQL = $strSQL .	"portal, issued, sourcecode) ";
	$strSQL = $strSQL . "values (null, ".$postId.",'".$title."','".$description."','".$urlLogo."','".$price."','".$comments."','";
	$strSQL = $strSQL . $portal."','" . dataHoraAtual() . "','".$urlSource."');";
	mysql_query($strSQL) or die(mysql_error() . "Erro ao inserir APP." . $strSQL);

	//Seleciona o maior appId e guarda
	$strSQL = "select max(appId) as appId from apps;";
	$ObjRst = mysql_query($strSQL) or die("Erro ao selecionar APP.");
	if ($row = mysql_fetch_assoc($ObjRst)) {
		$appId = $row['appId'];
	}

	//apps_agent
	$developer = mysql_real_escape_string($_POST['developer']);
	$org = mysql_real_escape_string($_POST['organizationName']);
	$orgEmail = mysql_real_escape_string($_POST['organizationEmail']);
	$orgURL = mysql_real_escape_string($_POST['organizationURL']);
	$personName = mysql_real_escape_string($_POST['personName']);
	$personEmail = mysql_real_escape_string($_POST['personEmail']);
	
	//Insere na tabela apps_agent 
	$strSQL = "insert into apps_agent (id, appId, type, organization, organizationUrl,organizationEmail, name, email) ";
	$strSQL = $strSQL . "values (null, ".$appId.",'".$developer."','".$org."','".$orgURL."','". $orgEmail."','".$personName."','".$personEmail."');";
	mysql_query($strSQL) or die("Erro ao inserir Agent.". $strSQL);

	//apps_platform
	$platformMobile = $_POST['platformMobile'];
	$platformWeb = $_POST['platformWeb'];
	$platformDesktop = $_POST['platformDesktop'];
	$platformTypeMobile = $_POST['platformTypeMobile'];
	$platformTypeWeb = $_POST['platformTypeWeb'];
	$platformTypeDesktop = $_POST['platformTypeDesktop'];
	$platformTypeURLMobile = $_POST['platformTypeURLMobile'];
	$platformTypeURLWeb = $_POST['platformTypeURLWeb'];
	$platformTypeURLDesktop = $_POST['platformTypeURLDesktop'];

	//Insere na tabela apps_platform
	if ($platformMobile == 'M') {
		$countMobile = count($platformTypeMobile); 
		for ($i = 0; $i < $countMobile; $i++) { 
		$strSQL = "insert into apps_platform (id, appId, platform, type, typeUrl) ";
		$strSQL = $strSQL . "values (null, ".$appId.",'M','".$platformTypeMobile[$i]."','".$platformTypeURLMobile[$i]."');";
		mysql_query($strSQL) or die("Erro ao inserir Platform.". $strSQL);
		} 
	}
	if ($platformWeb == 'W') { 
		$strSQL = "insert into apps_platform (id, appId, platform, type, typeUrl) ";
		$strSQL = $strSQL . "values (null, ".$appId.",'W','".$platformTypeWeb."','". $platformTypeURLWeb."');";
		mysql_query($strSQL) or die("Erro ao inserir Platform.". $strSQL);
	}
	if ($platformDesktop == 'D') {
		$countDesktop = count($platformTypeDesktop); 
		for ($i = 0; $i < $countDesktop; $i++) { 
		$strSQL = "insert into apps_platform (id, appId, platform, type, typeUrl) ";
		$strSQL = $strSQL . "values (null, ".$appId.",'D','".$platformTypeDesktop[$i]."','". $platformTypeURLDesktop[$i]."');";
		mysql_query($strSQL) or die("Erro ao inserir Platform.". $strSQL);
		} 
	}
	
	//apps_tags
	$keywords = $_POST['keywords'];
	$countkeywords = count($keywords); 
	if ($countkeywords >= 1) {
		for ($i = 0; $i < $countkeywords; $i++) { 
			if (strlen($keywords[$i]) >= 2) {
				$strSQL = "insert into apps_tags (id, appId, tagDescricao) ";
				$strSQL = $strSQL . "values (null, ".$appId.",'".$keywords[$i]."');";
				mysql_query($strSQL) or die("Erro ao inserir Tags.". $strSQL);
			}
		} 		
	}
	
	//apps_datasets
	$datasets = $_POST['datasets']; 
	$api = mysql_real_escape_string($_POST['api']);
	$datasetDescription = mysql_real_escape_string($_POST['datasetDescription']);
	$countDatasets = count($datasets); 
	if ($countDatasets >= 1) {
		for ($i = 0; $i < $countDatasets; $i++) { 
		$urlDataset = $portal . "/dataset/" . $datasets[$i];
		$strSQL = "insert into apps_datasets (id, appId, url, dataset, repository) ";
		$strSQL = $strSQL . "values (null, ".$appId.",'".$urlDataset."', '".$datasets[$i]."', '" . $api. "');";
		mysql_query($strSQL) or die("Erro ao inserir Datasets.". $strSQL);
		} 		
	}
	if (strlen($datasetDescription) >= 1) {
		$strSQL = "insert into apps_datasets (id, appId, url, description, repository) ";
		$strSQL = $strSQL . "values (null, ".$appId.",'', '".$datasetDescription."', '" . $api. "');";
		mysql_query($strSQL) or die("Erro ao inserir Datasets Description.". $strSQL);
	}

	//apps_category
	$category = $_POST['category'];
	$categoryDescription = mysql_real_escape_string($_POST['categoryDescription']);
	$countCategory = count($category); 
	if ($countCategory >= 1) {
		for ($i = 0; $i < $countCategory; $i++) { 
		$strSQL = "insert into apps_category (id, appId, description) ";
		$strSQL = $strSQL . "values (null, ".$appId.",'".$category[$i]."');";
		mysql_query($strSQL) or die("Erro ao inserir Category.". $strSQL);
		} 		
	}
	if (strlen($categoryDescription) >= 1) {
		$strSQL = "insert into apps_category (id, appId, description_2) ";
		$strSQL = $strSQL . "values (null, ".$appId.",'".$categoryDescription."');";
		mysql_query($strSQL) or die("Erro ao inserir Category Description.". $strSQL);
	}

	//PermanLink
	$permanLink = $variavel_limpa = strtolower(ereg_replace("[^a-zA-Z0-9-]", "-", strtr(utf8_decode(trim($title)), utf8_decode("áàãâéêíóôõúüñçÁÀÃÂÉÊÍÓÔÕÚÜÑÇ"),"aaaaeeiooouuncAAAAEEIOOOUUNC-")));


	Header('Location: /application-successfully-inserted?repository=' . $permanLink);
	die;
} else {

	Header('Location: /error-inserting-your-application');
	die;
}

?> 

   