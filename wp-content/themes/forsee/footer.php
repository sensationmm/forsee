    <footer>
  		<div class="body">
  			<div class="col3">
    				<div class="col-header">Contact Us</div>
    				<dl>
            <?php
                $address = get_field('company_address', 8);
                $telephone = get_field('company_telephone', 8);
                $email = get_field('company_email', 8);

                if($address != '') echo '<dt>Address:</dt><dd>'.$address.'</dd>';
                if($telephone != '') echo '<dt>Phone:</dt><dd>'.$telephone.'</dd>';
                if($email != '') echo '<dt>Email:</dt><dd><a href="mailto:'.$email.'" title="Email us">'.$email.'</a></dd>';

                $facebook = get_field('company_facebook', 8);
                $linkedin = get_field('company_linkedin', 8);
                $twitter = get_field('company_twitter', 8);
                $google = get_field('company_google', 8);
                $youtube = get_field('company_youtube', 8);

                echo '<div class="social">';

                if($facebook != '')
                    echo '<a href="'.$facebook.'" title="Forsee on Facebook" target="_blank"><img src="assets/images/icon-facebook.png" alt="Facebook" /></a>';

                if($linkedin != '')
                    echo '<a href="'.$linkedin.'" title="Forsee on LinkedIn" target="_blank"><img src="assets/images/icon-linkedin.png" alt="LinkedIn" /></a>';

                if($twitter != '')
                    echo '<a href="'.$twitter.'" title="Forsee on Twitter" target="_blank"><img src="assets/images/icon-twitter.png" alt="Twitter" /></a>';

                if($google != '')
                    echo '<a href="'.$google.'" title="Forsee on Google+" target="_blank"><img src="assets/images/icon-google.png" alt="Google+" /></a>';

                if($youtube != '')
                    echo '<a href="'.$youtube.'" title="Forsee on YouTube" target="_blank"><img src="assets/images/icon-youtube.png" alt="YouTube" /></a>';

                echo '</div>';
            ?>
  				</dl>
  			</div>
  			
  			<div class="col3">
  			</div>
  			
  			<div class="col3"> 
    				<div class="col-header">Quick links</div>
            <?php
              $quicklinks = wp_get_nav_menu_items('quick-links');
              if(sizeof($quicklinks) > 0) {
                  for($i=0; $i<sizeof($quicklinks); $i++) {
                      $navPage = get_field('_menu_item_object_id', $quicklinks[$i]->ID);
                      $navPage = get_post($navPage);
                      echo '<p>';
                      echo 'Go to <a href="'.get_permalink($navPage->ID).'" title="Go to '.$navPage->post_title.'">'.$navPage->post_title.'</a>';
                      echo '</p>';
                  }
              }
            ?>
  			</div>

  		</div>
  		<div class="footer-strip">
          Copyright 2015 Forsee Pty Ltd.  ABN 76 128 447 914. 
          <?php
              $footer = wp_get_nav_menu_items('footer-strip');
              if(sizeof($footer) > 0) {
                  for($i=0; $i<sizeof($footer); $i++) {
                      $navPage = get_field('_menu_item_object_id', $footer[$i]->ID);
                      $navPage = get_post($navPage);
                      echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
                      echo '<a href="'.get_permalink($navPage->ID).'" title="Go to '.$navPage->post_title.'">'.$navPage->post_title.'</a>';
                  }
              }
            ?>
      </div>
  	</footer>

    <div class="mobile-alert"><p>Optimised for Landscape viewing on mobile devices</p><div class="mobile-alert-close">Close</div></div>
    <div class="mask"></div>

    <script type="text/javascript" src="assets/js/modernizr.min.js"></script>
  	<script type="text/javascript" src="assets/js/app.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.1.2/js/swiper.jquery.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
          if(!Modernizr.svg) {
              $('img[src*="svg"]').attr('src', function() {
                  return $(this).attr('src').replace('.svg', '.png');
              });
          }
      });
    </script>
    <?php wp_footer(); ?>
</body>
</html>