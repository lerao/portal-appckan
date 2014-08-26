<?php 

/* Template Name: not-api-ckan-add-apps */

// Include Header
include('header.php'); 

?> 
        <?php if (!is_front_page('Home')): ?>
          <br/>  
          <h1>
            <?php

            the_title(); 
            
            if (isset($repository)) {
              echo(" [Repository:" . $repository . "]");
            }
            ?>  
          </h1>
          <hr/>
        <?php endif; ?>
      

        <div class="inner">
          
       <?php while ( have_posts() ) : the_post(); ?>
				<?php the_content(); ?>
				
		<?php endwhile; // end of the loop. ?>

      
<form class="pure-form pure-form-stacked" action="add-apps" method="get" accept-charset="UTF-8">
<fieldset style="font-size: 12px">
  
  <!-- app:name -->    
  Enter the <b>URL for the CKAN API</b> used for developing your application
  <input id="repository" name="repository" class="pure-input-1" type="text" placeholder="http://www.portal.com/api" required>
  
  <br clear="all">

  <button type="submit" class="pure-button pure-button-primary">Continue</button>

</form>

		<br/><br/>
		<br/>
        </div>
      </div>

<?php include('sidebar.php'); ?>

      </div>
    </div>
    
<?php include('footer.php'); ?>