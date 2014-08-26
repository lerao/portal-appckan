<?php 

/* Template Name: add-apps-error */

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
      Error inserting your application. Try again. 


<BR><BR><BR>
<a href="javascript:history.back(-1)" target="_blank">Click here to return to previous page.</a>

    <br/><br/>
    <br/>
        </div>
      </div>

<?php include('sidebar.php'); ?>

      </div>
    </div>
    
<?php include('footer.php'); ?>