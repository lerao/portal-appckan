<?php

// Display errors for demo
header('Content-Type: application/json');

function prettyPrint( $json )
{
    $result = '';
    $level = 0;
    $in_quotes = false;
    $in_escape = false;
    $ends_line_level = NULL;
    $json_length = strlen( $json );

    for( $i = 0; $i < $json_length; $i++ ) {
        $char = $json[$i];
        $new_line_level = NULL;
        $post = "";
        if( $ends_line_level !== NULL ) {
            $new_line_level = $ends_line_level;
            $ends_line_level = NULL;
        }
        if ( $in_escape ) {
            $in_escape = false;
        } else if( $char === '"' ) {
            $in_quotes = !$in_quotes;
        } else if( ! $in_quotes ) {
            switch( $char ) {
                case '}': case ']':
                    $level--;
                    $ends_line_level = NULL;
                    $new_line_level = $level;
                    break;

                case '{': case '[':
                    $level++;
                case ',':
                    $ends_line_level = $level;
                    break;

                case ':':
                    $post = " ";
                    break;

                case " ": case "\t": case "\n": case "\r":
                    $char = "";
                    $ends_line_level = $new_line_level;
                    $new_line_level = NULL;
                    break;
            }
        } else if ( $char === '\\' ) {
            $in_escape = true;
        }
        if( $new_line_level !== NULL ) {
            $result .= "\n".str_repeat( "\t", $new_line_level );
        }
        $result .= $char.$post;
    }

    return $result;
}

//Alternative connection
require_once("../wp-content/themes/apps/_alternative.php");

$portal = $_GET['portal'];
$nature = $_GET['nature'];


$geral = array("Help:" => "Use os parametros ?portal={string} ou ?nature={string} para filtrar os dados.", "success" => true);

//consulta os dados da App no banco de dados
$strSQL = "select appId, title, description, thumb, nature, comments, ";
$strSQL = $strSQL . "portal, issued, sourcecode from apps where title <> '' ";
if (strlen($portal) >=1) {
	$strSQL = $strSQL . "and portal like'%" . $portal . "%' ";
}
if (strlen($nature) >=1) {
	$strSQL = $strSQL . "and nature like'%" . $nature . "%' ";
}
$strSQL = $strSQL . "order by portal";

$ObjRstPrincipal = mysql_query($strSQL) or die("Erro ao selecionar dados do APP.");
while ($row = mysql_fetch_assoc($ObjRstPrincipal)) {
	
	//Recebe os dados de APP
	$appId = $row['appId'];
	$title = $row['title'];
	$appId = $row['appId'];
	$description = $row['description'];
	$thumb = $row['thumb'];
	$nature = $row['nature'];
	$comments = $row['comments'];
	$portal = $row['portal'];
	$issued = $row['issued'];
	$sourcecode = $row['sourcecode'];

	//consulta os dados do Agent no banco de dados
	$strSQL = "select type, organization, organizationURL, name, email, organizationEmail from apps_agent where appId = " . $appId;
	$ObjRst = mysql_query($strSQL) or die("Erro ao selecionar dados do Developer.");
	if ($row = mysql_fetch_assoc($ObjRst)) {
	  $organizationName = $row['organization'];
	  $organizationURL = $row['organizationURL'];
	  $name = $row['name'];
	  $developer = $row['type'];
	  $email = $row['email'];
	  $organizationEmail = $row['organizationEmail'];
	}

	//Monta Array Agent - Person
	if ($developer =="P") {
		$person = array(
			"type" => "person",
			"name" => utf8_encode($name)
		);	
	} else {
		$person = array(
		"type" => "organization",
		"name" => utf8_encode($organizationName),
		"homepage" => utf8_encode($organizationURL)
		);
	}

	//consulta os dados das Keywords/Tags no banco de dados e adiciona no arrat
	$strSQL = "select tagDescricao from apps_tags where appId = " . $appId;
	$ObjRst = mysql_query($strSQL) or die("Erro ao selecionar dados Keywords.");
	while ($row = mysql_fetch_assoc($ObjRst)) {
		$tags[] = utf8_encode($row['tagDescricao']);
	}

	//consulta os dados dos Datasets  no banco de dados
	$strSQL = "select url, dataset, description, repository from apps_datasets where appId = " . $appId;
	$ObjRst = mysql_query($strSQL) or die("Erro ao selecionar dados Datasets.");
	while ($row = mysql_fetch_assoc($ObjRst)) {
		$url = $row['url'];
		$dataset = $row['dataset'];
		$datasets["dataset"] = utf8_encode($dataset);
		$datasets["url"] = utf8_encode($url);
	}

	//consulta os dados da Category no banco de dados
	$strSQL = "select description, description_2 from apps_category where appId = " . $appId;
	$ObjRst = mysql_query($strSQL) or die("Erro ao selecionar dados Category.");
	while ($row = mysql_fetch_assoc($ObjRst)) {
		$catdescription = $row['description'];
	    $category[] = utf8_encode($catdescription);
	}

	//consulta os dados das Keywords/Tags no banco de dados
    $strSQL = "select platform, type, typeUrl from apps_platform where appId = " . $appId;
    $ObjRst = mysql_query($strSQL) or die("Erro ao selecionar dados Platform.");
    while ($row = mysql_fetch_assoc($ObjRst)) {
		$plat = $row['platform'];
		$type = $row['type'];
		$typeUrl = $row['typeUrl'];
		$platforms["platform"] = utf8_encode($plat);
		$platforms["type"] = utf8_encode($type);
		$platforms["typeUrl"] = utf8_encode($typeUrl);
	}

	// Array App
	$app = array(
	    "title" => utf8_encode($title),
	    "description" => utf8_encode($description),
	    "thumb" => $thumb,
	    "nature" => $nature,
	    "sourcecode" => $sourcecode,
	    "consumes" => $datasets, 
	    "agent" => $person, 
	    "platform" => $platforms, 
	    "category" => $category, 
	    "comments" => utf8_encode($comments),
	    "portal" => $portal,
	    "issued" => $issued,
	    "tags" => $tags 
	);

	$geral["Result"][] = $app;
}

if (count($geral) <= 2) {
	$geral = array("Help:" => "Use os parametros ?portal={string} ou ?nature={string} para filtrar os dados.", "success" => false, "Result" => utf8_encode("Não foram encontrados dados válidos com os os parametros informados. Redefina sua pesquisa."));
}

// formatada em JSON
$json = json_encode($geral);

//Saída JSON
echo prettyPrint($json);
?>