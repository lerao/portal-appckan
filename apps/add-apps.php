<?php 

/* Template Name: Form-Add-Apps */

// Display errors for demo
error_reporting(E_ALL ^ E_NOTICE);
@ini_set('display_errors', 'stdout');
ini_set('MAX_EXECUTION_TIME', -1);

// Include Header
include('header.php'); 

// Include Ckan_client
require_once('functions-ckan.php');

if ($_POST['submit'])
  die("aqui");

//Retorna o repositório
$repository = getCampo($wp_query->query_vars['repository']);
if (strpos($repository, "http") === false) {
  $repository = "http://" . $repository;
}
if (empty($repository)) {
  Header("Location: not-api-ckan-add-apps");
  die;
} else {
  $repository = urlHost($repository);
  $repositoryApi = urlApi($repository);

  //Verifica qual é a API do endereço URL
  $versionApi = getApiUrl($repositoryApi);

  //Se tiver correto, cria um Objeto CKAN
  if (($versionApi >=4) || ($versionApi <= 0)) {
    Header("Location: not-api-ckan-add-apps");
    die;
  } else {
    $ckan = new Ckan_client($repositoryApi, $versionApi, ""); 
  } 
}

?> 
<script>
	function trim(str, chars) {
		return ltrim(rtrim(str, chars), chars);
	}

	function ltrim(str, chars) {
		chars = chars || "\\s";
		return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
	}

	function rtrim(str, chars) {
		chars = chars || "\\s";
		return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
	}

	function MakeLinkSafe(){
		var e = document.getElementById('WebsiteAddress')
		str = trim(e.value);
		if(str.substr(0, 7) == 'http://'){
			e.value = str.substr(7);
		}
		return true;
	}
</script>

<form class="pure-form pure-form-stacked" action="/add-apps/" method="post" id="add-apps" accept-charset="UTF-8">
<?php if (!is_front_page('Home')): ?>
  <br/>  
  <h1>
    <?php

    the_title(); 
    
    if (isset($repository)) {
      echo(" [Repository: " . $repository . "]");
    }
    ?>  
  </h1>
  <hr/>
<?php endif; ?>

<input type="hidden" value="<?php echo($repository); ?>" name="portal">
<input type="hidden" value="<?php echo($repositoryApi); ?>" name="api">

<div class="inner">
  
<?php while ( have_posts() ) : the_post(); ?>
  <?php the_content(); ?>
<?php endwhile; // end of the loop. ?>


<fieldset style="font-size: 12px">
  
  <!-- app:name -->    
  <b>Name App*</b>
  <input id="title" name="title" class="pure-input-1" type="text" required>
  
  <!-- foaf:Agent -->
  <b>Developer*</b>
  <br clear="all">
  <input id="developer" name="developer" value="P" type="radio" checked="checked"> Person
  &nbsp;&nbsp;&nbsp;
  <input id="developer" name="developer" value="O" type="radio"> Organization
  <br clear="all">
  
  <div id= "organization" style="display: none">
    <div class="grid5" style="margin-left: 0 !important;">
      <b>Organization Name*</b> <input id="organizationName" class="pure-input-1" name="organizationName"  type="text">
    </div>
    <div class="grid4">
      <b>Organization e-mail*</b> <input id="organizationEmail" class="pure-input-1" name="organizationEmail" type="email">
    </div>
    <div class="grid4">
      <b>Homepage Organization*</b> <input id="organizationURL" class="pure-input-1" name="organizationURL" type="text">
    </div>
  </div>
  <div id= "person">
    <div class="grid8" style="margin-left: 0 !important;">
      <b>Name*</b> <input id="personName" class="pure-input-1" name="personName" type="text">
    </div>
    <div class="grid6">
      <b>E-mail*</b> <input id="personEmail" class="pure-input-1" name="personEmail" type="email">
    </div>
  </div>

  <br clear="all">

  <b>Description*</b>
  <textarea name="description" id="description" class="pure-input-1" style="height: 60px"></textarea>
  
  <b>Datasets*</b>
  <br clear="all">
  <?php
    try
    {
      $data = $ckan->get_package_register();
      if (count($data) == 0) {
        $data = $ckan->get_dataset_register();
      }
      if (count($data) >= 1) {
      ?>
        <select id="datasets" name="datasets[]" class="pure-input-1" style="height: 160px" multiple="multiple">
        <?php
          if (sort($data)) {
            foreach($data as &$value) {
              $data2 = $ckan->get_package_entity($value);
              if ($data2){
                if ($data2->title) {
                  echo('<option value='.$value.'>'.$data2->title .'</option>');
                } else {
                  echo('<option value='.$value.'>'.$value .'</option>');
                }
              }
            }        
          }
        ?>
        </select>
      <?php
      } else {
        ?>
         <textarea name="datasetDescription" id="datasetDescription" class="pure-input-1" style="height: 40px"></textarea>
      <?php
      }
    }
    catch (Exception $e)
    {
      print '<p><strong>Caught exception: ' . $e->getMessage() . 
        '</strong></p>';
    }
  ?>

  <b>Category/Group*</b>
  <br clear="all">
  <?php
    try
    {
      $dataGroup = $ckan->get_group_register();
      if (count($dataGroup) == 0) {
        $dataGroup = $ckan->get_group_dataset_register();
      }
      if (count($dataGroup) >= 1) {
      ?>
        <select id="category" name="category[]" class="pure-input-1" multiple="multiple">
        <?php
        if (sort($dataGroup)):
          foreach($dataGroup as &$group) {
            $data2Group = $ckan->get_group_entity($group);
            if ($data2Group){
              if ($data2Group->title) {
                echo('<option value='.$group.'>'.$data2Group->title .'</option>');
              } else {
                echo('<option value='.$group.'>'.$group .'</option>');
              }
              #echo('<option value=''' . $value .'''>'. $value .'</option>');
            }
          }        
        endif;
        ?>
        </select>
      <?php
      } else {
      ?>
        <textarea name="categoryDescription" id="categoryDescription" class="pure-input-1" style="height: 40px"></textarea>      
      <?php
      }

    }
    catch (Exception $e)
    {
      print '<p><strong>Caught exception: ' . $e->getMessage() . 
        '</strong></p>';
    }
  ?>

  <div id="typePrice">
    <div class="grid4" style="margin-left: 0 !important;">
    <b>Nature/Price*</b>
    <select id="price" name="price" class="pure-input-2">
      <option value="_none">- Select a value -</option>
      <option value="opensource">Free / Open Source</option>
      <option value="partial">Partially Free</option>
      <option value="paid">Paid for</option>
    </select>
    </div>
    <div class="grid8">
      If <b>Open Source</b>, URL to source <input id="urlSource" class="pure-input-1" name="urlSource" type="text">
    </div>
  </div>

  <br clear="all">

  <!-- foaf:Agent -->
  <b>Platform*</b>
  <br clear="all">
  <input id="platform" name="platformMobile" value="M" type="checkbox" checked="checked"> Mobile
  &nbsp;&nbsp;&nbsp; 
  <input id="platform" name="platformWeb" value="W" type="checkbox"> Web
  &nbsp;&nbsp;&nbsp;
  <input id="platform" name="platformDesktop" value="D" type="checkbox"> Desktop

  <br clear="all">
  
  <div id="mobile" style="padding: 8px; border: 1px dashed orange">
    <b>System:</b>
    <br clear="all">
    <input id="platformType" name="platformTypeMobile[]" value="Android" type="checkbox">
    <b>Android </b>       
    <input id="platformType" name="platformTypeMobile[]" value="iOS" type="checkbox">
    <b>iOS</b> 
    <input id="platformType" name="platformTypeMobile[]" value="Blackberry" type="checkbox">
    <b>Blackberry</b> 
    <input id="platformType" name="platformTypeMobile[]" value="WindowsPhone" type="checkbox">
    <b>Windows Phone</b> 
    <div id="Android" style="display: none">
      <b>URL Android </b> <input id="platformTypeURL" class="pure-input-1" name="platformTypeURLMobile[]" type="text">
    </div>
    <div id="iOS" style="display: none">
      <b>URL iOS </b> <input id="platformTypeURL" class="pure-input-1" name="platformTypeURLMobile[]" type="text">
    </div>
    <div id="Blackberry" style="display: none">
      <b>URL Blackberry </b> <input id="platformTypeURL" class="pure-input-1" name="platformTypeURLMobile[]" type="text">
    </div>
    <div id="WindowsPhone" style="display: none">
      <b>URL Windows Phone </b> <input id="platformTypeURL" class="pure-input-1" name="platformTypeURLMobile[]" type="text">
    </div> 
  </div>

  <div id="web" style="padding: 8px; border: 1px dashed blue; display: none; margin-top: 2px">
    <b>All navigators:</b>
    <br clear="all">
    <b>URL: </b> 
    <inpu type="hidde" name="platformTypeWeb" value="All navigators">
    <input id="platformTypeURL" class="pure-input-1" name="platformTypeURLWeb" type="text">
  </div>
  <div id="desktop" style="padding: 8px; border: 1px dashed red; display: none; margin-top: 2px">
    <b>System:</b>
    <br clear="all">
    <input id="platformType" name="platformTypeDesktop[]" value="Windows8" type="checkbox">
    <b>Windows 8</b>       
    <input id="platformType" name="platformTypeDesktop[]" value="Linux" type="checkbox">
    <b>Linux</b> 
    <div id="Windows8" style="display: none">
      <b>URL Windows 8 </b> <input id="platformTypeURL" class="pure-input-1" name="platformTypeURLDesktop[]" type="text">
    </div>
    <div id="Linux" style="display: none">
      <b>URL Linux </b> <input id="platformTypeURL" class="pure-input-1" name="platformTypeURLDesktop[]" type="text">
    </div>
  </div>

  <b>URL App thumbnail*</b>
  <input id="urlLogo" name="urlLogo" class="pure-input-1" type="text">
  
  <b>Tags/Keywords</b>
  <br clear="all">
  <div class="grid2" style="margin-left: 0 !important">
    <input id="keywords" name="keywords[]" class="pure-input-1" type="text">
  </div>
  <div class="grid2">
    <input id="keywords" name="keywords[]" class="pure-input-1" type="text">
  </div>
  <div class="grid2">
    <input id="keywords" name="keywords[]" class="pure-input-1" type="text">
  </div>
  <div class="grid2">
    <input id="keywords" name="keywords[]" class="pure-input-1" type="text">
  </div>
  <div class="grid2">
    <input id="keywords" name="keywords[]" class="pure-input-1" type="text">
  </div>
  <div class="grid2">
    <input id="keywords" name="keywords[]" class="pure-input-1" type="text">
  </div>

  <br clear="all">

  <b>Additional comments*</b>
  <textarea name="comments" id="comments" class="pure-input-1" style="height: 60px"></textarea>


  <br clear="all">


  <input type="hidden" value="submit" name="submit" id="submit">
  <input type="submit" class="pure-button pure-button-primary" value="Submit App">

</form>

		<br/><br/>
		<br/>
        </div>
      </div>

<?php include('sidebar.php'); ?>

      </div>
    </div>
    
<?php include('footer.php'); ?>