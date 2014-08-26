<?php 
/* Template Name: App */
#require_once("_alternative.php");

// Turn off all error reporting
error_reporting(0);
ini_set('MAX_EXECUTION_TIME', 10000);

// Include Header
include('header.php'); 

// Include Ckan_client
require_once('functions-ckan.php');

//Pega o Id do App
$postId = get_the_ID();

//consulta os dados da App no banco de dados
$strSQL = "select appId, description, thumb, nature, comments, ";
$strSQL = $strSQL . "portal, issued, sourcecode from apps where postId = " . $postId;
$ObjRst = mysql_query($strSQL);// or die("Erro ao selecionar dados do APP.");
if ($row = mysql_fetch_assoc($ObjRst)) {
  $appId = $row['appId'];
  $description = $row['description'];
  $thumb = $row['thumb'];
  $nature = $row['nature'];
  $comments = $row['comments'];
  $portal = $row['portal'];
  $issued = $row['issued'];
  $sourcecode = $row['sourcecode'];
}

//consulta os dados do Agent no banco de dados
$strSQL = "select type, organization, organizationURL, name from apps_agent where appId = " . $appId;
$ObjRst = mysql_query($strSQL);// or die("Erro ao selecionar dados do Developer.");
if ($row = mysql_fetch_assoc($ObjRst)) {
  $organizationName = $row['organization'];
  $organizationURL = $row['organizationURL'];
  $name = $row['name'];
  $developer = $row['type'];
}

//consulta os dados das Keywords/Tags no banco de dados
$strSQL = "select tagDescricao from apps_tags where appId = " . $appId;
$ObjRst = mysql_query($strSQL);// or die("Erro ao selecionar dados Keywords.");
$tagDescricao = "";
while ($row = mysql_fetch_assoc($ObjRst)) {
  $descricao = $row['tagDescricao'];
  if (strlen($tagDescricao)==0) {
    $tagDescricao = $descricao;
  } else {
    $tagDescricao = $tagDescricao . ", " . $descricao;
  }
}

//consulta os dados dos Datasets  no banco de dados
$strSQL = "select url, dataset, description, repository from apps_datasets where appId = " . $appId;
$ObjRst = mysql_query($strSQL);// or die("Erro ao selecionar dados Datasets.");
$datasets = "";
$contDataset = 0;
while ($row = mysql_fetch_assoc($ObjRst)) {
  if ($contDataset == 0) {
    $repositoryApi = $row['repository'];
    $versionApi = getApiUrl($repositoryApi);
    $ckan = new Ckan_client($repositoryApi, $versionApi, ""); 
  }
  $contDataset = $contDataset + 1;
  $url = $row['url'];
  $dataset = $row['dataset'];
  $datasetdescription = $row['description'];
  $data2 = $ckan->get_package_entity($dataset);
  if (strlen($datasets)==0) {
    $datasets = "<a href='" . $url . "' target='_blank'>" . $data2->title . "</a>";
  } else {
    $datasets = $datasets . "<BR><a href='" . $url . "' target='_blank'>" . $data2->title . "</a>";
  }
  if (strlen($datasetdescription) >= 1) {
    $datasets = $datasets . "<BR>" . $datasetdescription;
  }
}

//consulta os dados da Category no banco de dados
$strSQL = "select description from apps_category where appId = " . $appId . " order by id;";
$ObjRst = mysql_query($strSQL);// or die("Erro ao selecionar dados Category.");
$category = "";
while ($row = mysql_fetch_assoc($ObjRst)) {
  $catdescription = $row['description'];
  if ($ckan) {
    $dataGroup = $ckan->get_group_entity($catdescription);
  }
  if (strlen($category)==0) {
    if ($dataGroup->title) {
      $category = $dataGroup->title;
    } else {
      $category = $descricao;
    }
  } else {
    if ($dataGroup->title) {
      $category = $category . ", " . $dataGroup->title;
    } else {
      $category = $category . ", " . $catdescription;     
    }
  }
}

?> 

<?php if (!is_front_page('Home')): ?>
  <br/>  
  <h1>
    <?php
    echo("[App] ");
    the_title(); 
    ?>  
  </h1>
  <hr/>
<?php endif; ?>

<div class="inner">
 
<?php while ( have_posts() ) : the_post(); ?>
  <?php the_content(); ?>
<?php endwhile; // end of the loop. ?>

<div class="grid7" style="margin-left: 0 !important">
  <!-- foaf:Agent -->
  <b>Developer</b>
  <BR>
  <?php 
  if (($developer)=="P") {
    echo($name);
  } else {
    echo($organizationName . "&nbsp;&nbsp;&nbsp;&nbsp;Homepage: " . $organizationURL);
  }
  ?>

  <br clear="all"><BR>

  <b>Description*</b>
  <BR>
  <?php echo($description);?>

  <BR><BR>

  <b>Category/Group*</b>
  <br clear="all">
  <?php echo($category);?>

  <BR><BR>

  <b>Nature/Price*</b>
  <br clear="all">
  <?php 
  if ($nature == "paid") {
    echo("Paid for");
  } else if ($nature == "opensource") {
    echo("Free / Open Source &nbsp;&nbsp;&nbsp;&nbsp; URL to source: <a href='$sourcecode' target='_blank'>" . $sourcecode) . "</a>";
  } else if ($nature == "partial"){
    echo("Partially Free");
  } else {
    echo("Free");
  }
  ?>

</div>
<div class="grid6">
  <b>App thumbnail</b><BR>
  <img src="<?php echo($thumb);?>" width="400" />
</div>

  <BR>

    <BR clear="all">
      <BR clear="all">
  <b>Datasets of the portal: <?php echo($portal); ?></b>
  <br clear="all">
  <?php echo($datasets);?>

  <BR><BR>

  <b>Platforms</b>
  <table style="padding: 8px; border: 1px dashed orange" width="100%" cellpadding="4" cellspacing="4">
    <tr bgcolor="#D8D8D1">
      <td>Platform</td>
      <td>Type</td>
      <td>Download/Access</td>
    </tr>
    <?php
    //consulta os dados das Keywords/Tags no banco de dados
    $strSQL = "select platform, type, typeUrl from apps_platform where appId = " . $appId;
    $ObjRst = mysql_query($strSQL);// or die("Erro ao selecionar dados Platform.");
    while ($row = mysql_fetch_assoc($ObjRst)) {
      $platform = $row['platform'];
      $type = $row['type'];
      $typeUrl = $row['typeUrl'];
      if ($platform == "M") {
        echo("<tr><td>Mobile</td><td>" . $type . "</td><td><a href='".$typeUrl."'' target='_blank'>".$typeUrl."</a></td></tr>");
      } else if ($platform == "W") {
        echo("<tr><td>Web</td><td>" . $type . "</td><td><a href='".$typeUrl."'' target='_blank'>".$typeUrl."</a></td></tr>");
      } else if ($platform == "D"){
        echo("<tr><td>Desktop</td><td>" . $type . "</td><td><a href='".$typeUrl."'' target='_blank'>".$typeUrl."</a></td></tr>");
      }
    }
    ?>
  </table>
  <BR><BR>
 
  <b>Tags/Keywords</b>
  <br clear="all">
  <?php echo($tagDescricao);?>

  <br clear="all">
  <BR>

  <b>Additional comments*</b>
  <BR>
  <?php echo($comments);?>


  <br clear="all">

		<br/><br/>
		<br/>
        </div>
      </div>

<?php include('sidebar.php'); ?>

      </div>
    </div>
    
<?php include('footer.php'); ?>