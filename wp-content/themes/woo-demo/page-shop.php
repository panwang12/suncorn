<?php
    get_header();
   while(have_posts()){
    the_post();?>
    <h1>shop111</h1>
    <p><?php the_content();?></p>
   
    <br/>
    <?php
   }
   get_footer();
?>