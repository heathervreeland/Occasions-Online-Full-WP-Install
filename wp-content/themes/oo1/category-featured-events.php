<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
/* 
Category Template: Featured Events Template
*/

get_header();
?>

<div class="ruled left"><span class="head2 ruled-text-left"><?php single_cat_title(); ?></span></div>
<h2 class="uppercase">By Issue</h2>
<div class="featured-issue"><a href="/summer-fall-2009"><img src="/media/images/issue-summer-fall-2009.jpg"></a><p class="centered">Summer/Fall 2009</p></div>
<div class="featured-issue"><a href="/winter-spring-2010"><img src="/media/images/issue-winter-spring-2010.jpg"></a><p class="centered">Winter/Spring 2010</p></div>
<div class="featured-issue"><a href="/summer-fall-2010"><img src="/media/images/issue-summer-fall-2010.jpg"></a><p class="centered">Summer/Fall 2010</p></div>
<div class="featured-issue"><a href="/winter-2011"><img src="/media/images/issue-winter-2011.jpg"></a><p class="centered">Winter 2011</p></div>

<div class="clear">&nbsp;</div>

<h2 class="uppercase">By Type</h2>
<div class="featured-type"><a href="/featured-events/weddings"><img src="/media/images/featured-weddings.png"></a></div>
<div class="featured-type"><a href="/featured-events/parties-and-celebrations"><img src="/media/images/featured-parties.png"></a></div>
<div class="featured-type"><a href="/featured-events/mitzvahs"><img src="/media/images/featured-mitzvahs.png"></a></div>


<?php get_footer(); ?>
