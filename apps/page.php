<?php include('header.php'); ?>

        <div class="inner">
          
       <?php while ( have_posts() ) : the_post(); ?>
				<?php the_content(); ?>
				
		<?php endwhile; // end of the loop. ?>

		<br/><br/>
		<br/>
        </div>
      </div>

<?php include('sidebar.php'); ?>

      </div>
    </div>
    
<?php include('footer.php'); ?>