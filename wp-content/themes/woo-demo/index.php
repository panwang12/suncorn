<?php
    get_header();
   while(have_posts()){
    the_post();?>
    <h2>index11</h2>
    <h2>
        <a href="<?php the_permalink();?>">
            <?php the_title();?>
        </a>
    </h2>
    <p><?php the_content();?></p>
    <h1>sdcxc</h1>
    <br/>
    <?php
   }
   get_footer();
?>