<?php 

/* Template Name: add-apps-success */

// Include Header
include('header.php'); 

//Retorna o repositório
$repository = getCampo($wp_query->query_vars['repository']);

?> 
        <?php if (!is_front_page('Home')): ?>
          <br/>  
          <h1>
            <?php

            the_title(); 
            
            ?>  
          </h1>
          <hr/>
        <?php endif; ?>
      

        <div class="inner">
        <BR><BR>  
      Your application was successfully inserted. 
<BR><BR><BR>
<a href="/<?php echo($repository); ?>" target="_blank">Click here to view it.</a>

		<br/><br/>
		<br/>
        </div>
      </div>

<?php include('sidebar.php'); ?>

      </div>
    </div>
    
<?php include('footer.php'); ?>