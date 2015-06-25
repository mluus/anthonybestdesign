<?php
/**
 * Shareprints Gallery
 *
 * @package   Shareprints
 * @author    JR w/Freak Plugins <jr@freakplugins.com>
 * @license   GPLv3+
 * @link      http://freakplugins.com
 * @copyright Copyright (c) 2014 Freak Plugins, LLC - All Rights Reserved
 */
class Shareprints_Lightbox {
	
	/**
	 *
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since 1.0.0
	 */
	function __construct(){

		// lightbox
		add_action('shareprints/create_lightbox', array($this, 'create_lightbox'), 5, 1);
		add_action('shareprints/load_lightbox_panels', array($this, 'load_lightbox_default_panels'), 5, 1);
		add_action('shareprints/load_lightbox_controls', array($this, 'load_lightbox_controls'), 5, 1);
		add_action('shareprints/load_share_controls', array($this, 'load_share_controls'), 5, 1);

		// Comments Ajax
		add_action('wp_ajax_shareprints_get_comments', array( $this, 'shareprints_get_comments'));
			add_action('wp_ajax_nopriv_shareprints_get_comments', array( $this, 'shareprints_get_comments'));
		add_action('wp_ajax_shareprints_comments_post', array( $this, 'shareprints_comments_post'));
			add_action('wp_ajax_nopriv_shareprints_comments_post', array( $this, 'shareprints_comments_post'));
			
	}

	/**
	 *
	 * create_lightbox()
	 *
	 * enqueues lightbox styles, scripts and outputs the template in the page footer
	 *
	 * @since 1.0.0
	 */
	public function create_lightbox(){
		
		// enqueue scripts
		wp_enqueue_script('shareprints_lightbox');		
		wp_enqueue_script('shareprints_js_plugins');
		
		// add the template to the footer
		add_action('wp_footer', array( $this, 'lightbox_template'));
		
	}
	
	/**
	 * Outputs the lightbox template
	 *
	 *
	 * @since 1.0.0
	 */
	public function lightbox_template(){

		echo '<script type="text/html" id="tmpl-shareprints_lightbox">';
			echo '<div id="shareprints_lightbox">';
				echo '<div class="wrapper">';
					echo '<div class="holder">';
						
					// Master Close Button
						echo '<a id="shareprints_close" class="shareprints_toggle no_touch ion-ios7-close-outline" title="'.__('Close','shareprints').'">'.__('Close','shareprints').'</a>';						
						
					// Flexslider 
						echo '<div class="shareprints_lightbox_primary_panel">';
							echo '<div class="shareprints_flexslider">';
								echo '<div class="slides"></div>';
							echo '</div>';
						echo '</div>';

					// Flexslider Controls	
						echo '<div class="shareprints_flexslider_controls_wrap no_touch"></div>'; 

						do_action('shareprints/load_lightbox_panels');

					// Controls
						echo '<div class="shareprints_controls_wrap no_touch">';
							echo '<div class="shareprints_controls">';
								 do_action('shareprints/load_lightbox_controls');
							echo '</div>';
						echo '</div>';
					echo '</div>'; // holder
						
					// thumbnav
					echo '<div class="shareprints_thumbnav_wrap">';
						echo '<div class="shareprints_thumbnav"><div class="control_thumbs"></div></div>';
					echo '</div>';
						
				echo '</div>';
			echo '</div>';
		echo '</script>';

	}

// DEFAULT LIGHTBOX PANELS
	/**
	 *
	 * load_lightbox_default_panels()
	 *
	 * Loads default lightbox panels
	 *
	 * @since 1.0.0
	 */
	function load_lightbox_default_panels(){
		
		// Image Info	
		echo '<div id="shareprints_info_panel" class="shareprints_lightbox_secondary_panel">';
			echo '<div class="secondary_panel_container centered_panel">';
				echo '<div class="secondary_panel_content scrollable_panel"></div>';
			echo '</div>';
		echo '</div>';
		
		// Comments
		echo '<div id="shareprints_comments_panel" class="shareprints_lightbox_secondary_panel">';
			echo '<div class="secondary_panel_container">';
				echo '<div class="secondary_panel_header">';
					echo '<h4 class="shareprints_panel_title">'.__('Comments','shareprints').'</h4>';
					echo '<span class="shareprints_panel_loading ion_icon ion-load-c">'.__('Loading...', 'shareprints').'</span>';
				echo '</div>';
				echo '<div class="secondary_panel_content scrollable_panel"></div>';							
				if ( get_option( 'comment_registration' ) && !is_user_logged_in() ){
					echo '<p class="must_log_in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.', 'shareprints' ), wp_login_url( apply_filters( 'the_permalink', get_permalink(  ) ) ) ) . '</p>';
				}else{
					echo '<div class="secondary_panel_slideout">';
							echo '<a id="sp_comment_toggle" class="shareprints_toggle slideout_toggle ion-ios7-compose-outline" title="'.__('Leave a comment','shareprints').'"><span>'.__('Leave a comment','shareprints').'</span></a>';
							$this->load_comment_form();
					echo '</div>';
				}
			echo '</div>';
		echo '</div>';
		
		// Sharing
		echo '<div id="shareprints_share_panel" class="shareprints_lightbox_secondary_panel no_touch">';
			echo '<div class="secondary_panel_container ">'; //centered_panel
				echo '<div class="secondary_panel_content centered_panel">';							
					do_action('shareprints/load_share_controls');
				echo '</div>';
			echo '</div>';
		echo '</div>';
		
	}

// DEFAULT LIGHTBOX CONTROLS
	/**
	 *
	 * load_lightbox_controls()
	 *
	 * outputs the lightbox controls
	 *
	 * @since 1.0.0
	 */
	function load_lightbox_controls(){

		$controls = array(
			
			'info' => array(
				'id' => 'shareprints_info',
				'class' => 'shareprints_toggle ion-ios7-information-outline panel_toggle',
				'title' => __('Show Info','shareprints'),
				'text' => __('Show Info','shareprints'),
				'data' => array(
					'l10n' => array(
						'active' => __('Hide Info','shareprints'),
						'normal' => __('Show Info','shareprints'),
					),
					'events' => array(
						'namespace' => 'sp_app',
						'open'  => array( 'func'=> 'open_info', 'args'=> array(), ),
					),
				),
			),
			'comments' => array(
				'id' => 'shareprints_comments',
				'class' => 'shareprints_toggle ion-ios7-chatbubble-outline panel_toggle',
				'title' => __('Show Comments','shareprints'),
				'text' => __('Show Comments','shareprints'),
				'data' => array(
					'comment-count' => 0,
					'l10n' => array(
						'active' => __('Hide Comments','shareprints'),
						'normal' => __('Show Comments','shareprints'),
					),
					'events' => array(
						'namespace' => 'sp_app',
						'open'  => array( 'func'=> 'open_comments', 'args'=> array(), ),
					),
				),
			),
			'share' => array(
				'id' => 'shareprints_share',
				'class' => 'shareprints_toggle ion-ios7-upload-outline panel_toggle',
				'title' => __('Show Share Options','shareprints'),
				'text' => __('Show Share Options','shareprints'),
				'data' => array(
					'l10n' => array(
						'active' => __('Hide Share Options','shareprints'),
						'normal' => __('Show Share Options','shareprints'),
					),
				),
			),
			'thumbnav' => array(
				'id' => 'shareprints_thumbnav',
				'class' => 'shareprints_toggle ion-ios7-film-outline-horz',
				'title' => __('Show Thumbnails','shareprints'),
				'text' => __('Show Thumbnails','shareprints'),
				'data' => array(
					'l10n' => array(
						'active' => __('Hide Thumbnails','shareprints'),
						'normal' => __('Show Thumbnails','shareprints'),
					),
				),
			),
			'theme' => array(
				'id' => 'shareprints_theme',
				'class' => 'shareprints_toggle ion-ios7-sunny-outline',
				'title' => __('Invert Colors','shareprints'),
				'text' => __('Invert Colors','shareprints'),
				'data' => array(),
			),
		);
		
		$controls = apply_filters('shareprints/lightbox_controls', $controls);		
		
		$html = '';
		
		if($controls)
		foreach ($controls as $n => $v){
			$html.= '<a ';
				if($v['id'])
					$html.= 'id="'.$v['id'].'" ';
				if($v['class'])
					$html.= 'class="'.$v['class'].'" ';
				if($v['title'])
					$html.= 'title="'.$v['title'].'" ';
				if($v['data']){
					foreach($v['data'] as $dk => $dv){
						$html.= 'data-'.$dk.'="'.htmlspecialchars(json_encode($dv), ENT_QUOTES, get_bloginfo( 'charset' )).'" ';
					}
				}
			$html.= '>';
				if($v['text'])
					$html.= $v['text'];
			$html.='</a>';
			
		}
		
		echo $html;
		
	}

// DEFAULT LIGHTBOX SHARE CONTROLS
	/**
	 * 
	 * load_share_controls()
	 * 
	 * outputs the default lightbox share panel controls
	 *
	 * @since 1.0.0
	 */
	function load_share_controls( $share_controls ){
		
		if( !is_array($share_controls) ){
			$share_controls = array();
		}

		$share_controls = array(
			'facebook' => array(
				'id' => 'shareprints_facebook',
				'class' => 'shareprints_toggle ion-social-facebook-outline',
				'title' => __('Share on Facebook','shareprints'),
				'text' => __('Share on Facebook','shareprints'),
				'data' => '',
			),
			'twitter' => array(
				'id' => 'shareprints_twitter',
				'class' => 'shareprints_toggle ion-social-twitter-outline',
				'title' => __('Share on Twitter','shareprints'),
				'text' => __('Share on Twitter','shareprints'),
				'data' => '',
			),
			'pinterest' => array(
				'id' => 'shareprints_pinterest',
				'class' => 'shareprints_toggle ion-social-pinterest-outline',
				'title' => __('Share on Pinterest','shareprints'),
				'text' => __('Share on Pinterest','shareprints'),
				'data' => '',
			),
			'linkedin' => array(
				'id' => 'shareprints_linkedin',
				'class' => 'shareprints_toggle ion-social-linkedin-outline',
				'title' => __('Share on LinkedIn','shareprints'),
				'text' => __('Share on LinkedIn','shareprints'),
				'data' => '',
			),
			'googleplus' => array(
				'id' => 'shareprints_googleplus',
				'class' => 'shareprints_toggle ion-social-googleplus-outline',
				'title' => __('Share on Google+','shareprints'),
				'text' => __('Share on Google+','shareprints'),
				'data' => '',
			),
		);
		
		$share_controls = apply_filters('shareprints/social_links', $share_controls);		
		
		$html = '';
		
		if($share_controls){
		
		$html.= '<div class="share_controls">';
		
			foreach ($share_controls as $name => $link){
				$html.= '<a ';
					if($link['id'])
						$html.= 'id="'.$link['id'].'" ';
					if($link['class'])
						$html.= 'class="'.$link['class'].'" ';
					if($link['title'])
						$html.= 'title="'.$link['title'].'" ';
					if($link['data']){
						foreach($link['data'] as $k => $v){
							$html.= 'data-'.$k.'="'.$v.'"';
						}
					}
				$html.= '><span>'.$link['text'].'</span></a>';
				
			}
		
		$html.= '</div>';
		
		}
		echo $html;
		
	}

// LIGHTBOX COMMENT FORM
	/**
	 *
	 * load_comment_form()
	 *
	 * Outputs the lightbox Comment form
	 *
	 * @since 1.0.0
	 */
	function load_comment_form() {
		
		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$required = ($req) ? '*' : '';
		$aria_req = ( $req ? " aria-required='true'" : '' );
		$html5 = current_theme_supports( 'html5', 'comment-form' ) ? true : false;
		
		ob_start();
		
		echo '<div class="secondary_panel_slideout_content">';
			echo '<div class="shareprints_comments_form">';
				echo '<div id="sp_error"></div>';
				
				if ( !is_user_logged_in() ) {
				
					if(!$html5)
					echo '<label for="sp_author">'.__('Name', 'shareprints').'<span class="required">'.$required.'</span>';
						echo '<input id="sp_author" name="author" type="text" value="'.esc_attr( $commenter['comment_author'] ).'" '.($html5 ? 'placeholder="'.__('Name', 'shareprints').$required.'"' : 'class="haslabel"').' size="30" '.$aria_req.' />';
					if(!$html5)
					echo '</label>';
					
					if(!$html5)
					echo '<label for="sp_email">'.__('Email', 'shareprints').'<span class="required">'.$required.'</span>';
						echo '<input id="sp_email" name="email" '.( $html5 ? 'type="email"' : 'type="text" class="haslabel"' ).' value="'.esc_attr(  $commenter['comment_author_email'] ).'" '.($html5 ? 'placeholder="'.__('Email', 'shareprints').$required.'"' : '').' size="30"' . $aria_req . ' />';
					if(!$html5)
					echo '</label>';
					
					if(!$html5)
					echo '<label for="sp_url">'.__('Website', 'shareprints');
						echo '<input id="sp_url" name="url" '.( $html5 ? 'type="url"' : 'type="text" class="haslabel"' ).' value="'.esc_attr( $commenter['comment_author_url'] ).'" '.($html5 ? 'placeholder="'.__('Website', 'shareprints').'"' : '').' size="30" />';
					if(!$html5)
					echo '</label>';

				}else{
				
					global $current_user;
					get_currentuserinfo();
					
					if(empty($current_user->display_name))
					$current_user->display_name = $current_user->user_login;
      				
					echo '<input id="sp_author" name="author" type="hidden" value="'.wp_slash($current_user->display_name).'"/>';
					echo '<input id="sp_email" name="email" type="hidden" value="'.wp_slash($current_user->user_email).'"/>';
					echo '<input id="sp_url" name="url" type="hidden" value="'.wp_slash($current_user->user_url).'"/>';

				}
				
				if(!$html5)
				echo '<label for="sp_comment">'.__('Comment', 'shareprints');
					echo '<textarea id="sp_comment" name="sp_comment" aria-required="true" '.($html5 ? 'placeholder="'.__('Comment', 'shareprints').'"' : 'class="haslabel"').'></textarea>';
				if(!$html5)
				echo '</label>';
				
				if(!$html5)
				echo '<div class="haslabel">';
					echo '<a id="sp_submit" class="ion_icon ion-load-c" data-object="submit" title="'.__('Post Comment', 'shareprints').'">'.__('Post Comment', 'shareprints').'</a>';
					echo '<a id="sp_cancel" title="'.__('Cancel', 'shareprints').'">'.__('Cancel', 'shareprints').'</a>';
				if(!$html5)
				echo '</div>';
								
			echo '</div>';
		echo '</div>'; // slideout content
			
		echo ob_get_clean();		
	}

// LIGHTBOX COMMENTS AJAX 
	/**
	 * 
	 * shareprints_get_comments()
	 * 
	 * Called from the lightbox.
	 * Returns the comments and a comment form for the image id
	 *
	 * @since 1.0.0
	 */
	function shareprints_get_comments(){
		
		if( check_ajax_referer( 'check-and-post-comments', 'security_seed', false ) && $_POST && isset($_POST['imageID']) ){

			$imageID = absint($_POST['imageID']);
			$args = array( 'post_id' => $imageID, 'status' => 'approve' );

			ob_start();			
			
			if($comments = get_comments( $args )):
				foreach ($comments as $comment):
					$author = get_comment_author($comment->comment_ID);
					$author_url = get_comment_author_url($comment->comment_ID);
					$author_avatar = get_avatar( $comment->comment_author_email, 35 );
					echo '<div class="shareprints_comment">';
						echo '<div class="shareprints_comment_avatar">'.(($author_url !== '') ? '<a href="'.$author_url.'" target="_blank" title="'.$author_url.'">'.$author_avatar.'</a>' : $author_avatar).'</div>';
						echo '<div class="shareprints_comment_body">';
							echo '<h4 class="shareprints_comment_author">'.$author.'</h4>';
							echo '<time class="shareprints_comment_date">'.get_comment_date('', $comment->comment_ID).'</time>';
							echo '<div class="shareprints_comment_content">'.apply_filters('the_content', $comment->comment_content).'</div>';
						echo '</div>';
					echo '</div>';
				endforeach;
			else:
				echo '<div id="shareprints_default_comment" class="shareprints_comment">';
					echo '<span>'.__('No Comments','shareprints').'</span>';
				echo '</div>';
			endif;

			// New comment template				
			$commenter = wp_get_current_commenter();
			echo '<script type="text/html" id="tmpl-shareprints_comment">';
				echo '<div class="shareprints_comment">';
					echo '<div class="shareprints_comment_avatar">'.(($commenter['comment_author_url'] !== '') ? '<a href="'.$commenter['comment_author_url'].'" target="_blank" title="'.$commenter['comment_author_url'].'">'.get_avatar( $commenter['comment_author_email'], 30 ).'</a>' : get_avatar( $commenter['comment_author_email'], 35 )).'</div>';
					echo '<div class="shareprints_comment_body">';
						echo '<h4 class="shareprints_comment_author">{comment_author}</h4>';
						echo '<time class="shareprints_comment_date">{comment_date}</time>';
						echo '<div class="shareprints_comment_content">{comment_content}</div>';
					echo '</div>';
				echo '</div>';
			echo '</script>';

			echo ob_get_clean();

		}else{ 
			
			echo '"failure"'; // @todo : do something here in event of failure.
		}
	
		die(); // this is required to return a proper result
	}

	/**
	 * 
	 * shareprints_comments_post()
	 * 
	 * Called from the lightbox
	 * Handles the posting of comments from the lightbox
	 *
	 * @since 1.0.0
	 */
	function shareprints_comments_post(){

		if( check_ajax_referer( 'check-and-post-comments', 'security_seed', false ) && $_POST ){

			nocache_headers();

			$comment_post_ID = isset($_POST['comment_post_ID']) ? (int) $_POST['comment_post_ID'] : 0;
			$post = get_post($comment_post_ID);
			$comment_author       = ( isset($_POST['sp_author']) )  ? trim(strip_tags($_POST['sp_author'])) : null;
			$comment_author_email = ( isset($_POST['sp_email']) )   ? trim($_POST['sp_email']) : null;
			$comment_author_url   = ( isset($_POST['sp_url']) )     ? trim($_POST['sp_url']) : null;
			$comment_content      = ( isset($_POST['sp_comment']) ) ? trim($_POST['sp_comment']) : null;
				
			if(current_user_can('unfiltered_html')){
				if(!isset($_POST['_wp_unfiltered_html_comment']) || !wp_verify_nonce($_POST['_wp_unfiltered_html_comment'], 'unfiltered-html-comment_' . $comment_post_ID)){
					kses_remove_filters(); // start with a clean slate
					kses_init_filters(); // set up the filters
				}
			}
			
			$comment_type = '';
			$comment_parent = isset($_POST['comment_parent']) ? absint($_POST['comment_parent']) : 0;
			$commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID');
			$comment_id = wp_new_comment( $commentdata );
			$comment_content = apply_filters('the_content', $comment_content );
			$comment_date = get_comment_date('', $comment_id);
			$return = compact( 'comment_date', 'comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID');
			
			echo json_encode($return);

		}else{
			
			echo '"'.__('Your session has timed out. Please refresh the page.','shareprints').'"';
		}
	
		die(); // this is required to return a proper result
		
	}	
	
}
new Shareprints_Lightbox();