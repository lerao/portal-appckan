<?php 

// Display errors for demo
error_reporting(E_ALL ^ E_NOTICE);
@ini_set('display_errors', 'stdout');
ini_set('MAX_EXECUTION_TIME', -1);

//Header
include('header.php'); 

?>
  
        <?php if (!is_front_page('Home')): ?>
          <br/>  
          <h1><?php the_title(); ?></h1>
          <hr/>
        <?php endif; ?>
      

        <div class="inner">
          
        	<?php if ( ! is_front_page() ) : ?>
				<header class="page-header page-description">
					<h1 class="page-title">
						<?php
							if ( is_category() ) :
								single_cat_title();

							elseif ( is_tag() ) :
								printf( __( 'Tagged: %s', 'semicolon' ), single_tag_title( '', false ) );

							elseif ( is_author() ) :
								printf( __( 'Author: %s', 'semicolon' ), '<span class="vcard">' . get_the_author() . '</span>' );

							elseif ( is_day() ) :
								printf( __( 'Day: %s', 'semicolon' ), '<span>' . get_the_date() . '</span>' );

							elseif ( is_month() ) :
								printf( __( 'Month: %s', 'semicolon' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'semicolon' ) ) . '</span>' );

							elseif ( is_year() ) :
								printf( __( 'Year: %s', 'semicolon' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'semicolon' ) ) . '</span>' );

							elseif ( is_search() ) :
								printf( __( 'Search Results for: %s', 'semicolon' ), '<span>' . get_search_query() . '</span>' );

							elseif ( is_archive() ):
								_e( 'Archives', 'semicolon' );

							endif;
						?>
					</h1>
				</header><!-- .page-header -->
			<?php endif; // is_front_page ?>
			<?php include('content-list-apps.php'); ?>
        </div>
      </div>

	<?php include('sidebar.php'); ?>

      </div>
    </div>
    
<?php include('footer.php'); ?>