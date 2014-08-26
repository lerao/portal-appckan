<!-- Title -->
<div class="titulo">
  <div style="width: 60%; float: left; font-size: 20px; font-weight: bold;">Latest 15 Apps</div>
  <div style="width: 40%; float: left; text-align: right;"><a class="button-success pure-button" href="/add-apps">+ add your app »</a></div>
</div>

<br clear="all" />

<!-- List App -->
<div id="listApps">
<?php

//consulta os dados da App no banco de dados
$strSQL = "select post_name, post_title, description, thumb, portal from apps a inner join wp_posts w on (w.id = a.postId) order by issued DESC LIMIT 15";
$ObjRst = mysql_query($strSQL) or die("Erro ao selecionar dados dos APPs.");
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
  


