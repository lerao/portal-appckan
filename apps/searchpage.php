<?php
/*
Template Name: Página de Busca
*/

//Header
include('header.php');

//Retorna o repositório
$busca = getCampo($wp_query->query_vars['search']);

if (strpos($busca, "open")) {
  $busca = "opensource";
}
if (strpos($busca, "source")) {
  $busca = "opensource";
}
if ($busca == "open source") {
  $busca = "opensource";
}
?>

        <div class="inner">
       <form name="search_form2" action="./" method="get" accept-charset="utf-8" id="search_form2" role="search" class="search-form">
       <?php while ( have_posts() ) : the_post(); ?>
				<?php the_content(); ?>
				
		<?php endwhile; // end of the loop. ?>

      <?php if (strlen($busca) <= 0) {?>
        <h1>Enter the text below to search:</h1>
        <hr />
        <BR>
        You can search by application name, type, operating system, data portal, datasets, keywords, organization and developer.
        <BR>
          <BR>
        <input type="search" class="search-field" placeholder="Search Apps..." value="<?php echo($busca);?>" name="search" title="Search Apps..." style="width: 60%">
        <input type="submit" value="Search!" class="pure-button pure-button-primary" style="margin-left: 10px; height: 36px">
      <?php } else { ?>
        <h1>New Search</h1>
        <hr />
        <BR>
        You can search by application name, type, operating system, data portal, datasets, keywords, organization and developer.
        <BR>
          <BR>
        <input type="search" class="search-field" placeholder="Search Apps..." value="<?php echo($busca);?>" name="search" title="Search Apps..." style="width: 60%">
        <input type="submit" value="Search!" class="pure-button pure-button-primary" style="margin-left: 10px; height: 36px">
        <BR><BR>
        <h1>Results for: <?php echo($busca); ?></h1>
        <hr />
        <BR clear="all" />
        <!-- List App -->
        <div id="listApps">
        <?php

        //consulta os dados da App no banco de dados
        $strSQL = "select post_name, post_title, a.description, thumb, portal from apps a inner join wp_posts w on (w.id = a.postId) ";
        $strSQL = $strSQL ."left outer join apps_datasets d on (a.appId = d.appId) left outer join apps_category c on (c.appId = a.appId) ";
        $strSQL = $strSQL ."left outer join apps_platform p on (a.appId = p.appId) left outer join apps_tags t on (t.appId = a.appId) ";
        $strSQL = $strSQL ."left outer join apps_agent ag on (ag.appId = a.appId) ";
        $strSQL = $strSQL . "where w.post_name like '%".$busca."%' or w.post_title like '%".$busca."%' or a.description like '%".$busca."%' ";
        $strSQL = $strSQL . "or a.portal like '%".$busca."%' or d.dataset like '%".$busca."%' or c.description like '%".$busca."%' ";
        $strSQL = $strSQL . "or t.tagDescricao like '%".$busca."%' or p.type like '%".$busca."%' or ag.organization like '%".$busca."%' ";
        $strSQL = $strSQL . "or ag.name like '%".$busca."%' or a.nature like '%".$busca."%' ";
        $strSQL = $strSQL . "group by a.appId order by post_title";
        $ObjRst = mysql_query($strSQL) or die("Erro ao buscar dados dos APPs.");
        while ($row = mysql_fetch_assoc($ObjRst)) {

          $post_name = $row['post_name'];
          $title = $row['post_title'];
          $thumb = $row['thumb'];
          $description = $row['description'];
          $portal = urlHostLimpa($row['portal']);

          if (strlen($description) >= 50) {
            $description = substr($description, 0, 50) . "...";
          }

        ?>  <!-- App -->
          <div class="item">
            <div class="related-item media-item is-expander masonry-brick" data-module="related-item">       
              <!-- Logo -->
              <img class="media-image" src="<?php echo($thumb); ?>" alt=""<?php echo($title); ?>"" width="100%" style="padding-top: 8px !important; margin-bottom: 8px;" />
              <!-- Title -->
              <h3 class="media-heading"><?php echo($title); ?></h3>
              <!-- Description -->
              <div class="prose">
                <?php echo($description); ?>
                <a class="truncator-link truncator-more" href="<?php echo($post_name); ?>">See more..</a>
              </div>
              <!-- Link App -->
              <a class="media-view" title="Go to Application Details" href="<?php echo($post_name); ?>" target="_self">
                
                <!-- Portal App -->
                <span class="banner"><?php echo($portal); ?></span>
              </a>
             </div>
          </div>

        <?php 
          }
        ?>
        </div>


      <?php
      }
      ?>
      
		<br/><br/>
		<br/>

  </form>
        </div>
      </div>

<?php include('sidebar.php'); ?>

      </div>
    </div>
    
<?php include('footer.php'); ?>