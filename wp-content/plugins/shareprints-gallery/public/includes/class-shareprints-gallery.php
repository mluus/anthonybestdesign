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
class Shareprints_Gallery {
	/**
	*  Vars
	*
	*  @description: 
	* @since 1.0.0
	*  
	*/
	var $name,
		$label,
		$defaults,
		$l10n;
	
	/**
	*  __construct()
	*
	*  Adds neccessary Actions / Filters
	*
	* @since 1.0.0
	*/
	function __construct(){

		// register gallery
		add_filter('shareprints/registered_galleries', array($this, 'registered_galleries'), 10, 1);
		add_filter('shareprints/load_gallery_defaults/gallery_type='.$this->name, array($this, 'load_gallery_defaults'), 10, 1);

		// gallery
		$this->add_action('shareprints/create_gallery/gallery_type='.$this->name, array($this, 'create_gallery'), 10, 1);
		$this->add_action('shareprints/enqueue_gallery_styles/gallery_type='.$this->name, array($this, 'enqueue_gallery_styles'), 10, 1);
		$this->add_action('shareprints/enqueue_gallery_scripts/gallery_type='.$this->name, array($this, 'enqueue_gallery_scripts'), 10, 1);

	}
	
	/**
	*
	*  add_filter
	*
	* @credit to Elliot Condon for this
	*
	*  @description: checks if the function is_callable before adding the filter
	*  @since: 1.0.0
	*  
	*/
	function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1){
		if( is_callable($function_to_add) ){
			add_filter($tag, $function_to_add, $priority, $accepted_args);
		}
	}

	/**
	*
	*  add_action
	*
	* @credit to Elliot Condon for this
	*
	*  @description: checks if the function is_callable before adding the action
	*  @since: 1.0.0
	*  
	*/
	function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1){
		if( is_callable($function_to_add) ){
			add_action($tag, $function_to_add, $priority, $accepted_args);
		}
	}
	
	/**
	*  registered_galleries()
	*
	*  Adds the gallery to the gallery_type list when inserting a gallery into a page/post
	*
	*  @type	filter
	*  @since	1.0.0
	*
	*  @param	$galleries	- the array of all registered galleries
	*
	*  @return	$galleries - the array of all registered galleries
	*/
	function registered_galleries( $galleries ){

		// add to array
		$galleries[ $this->name ] = $this->label;
		
		// return array
		return $galleries;

	}
	
	/**
	*
	*  load_gallery_defaults
	*
	*  @since	1.0.0
	*
	*  @param	$gallery	{array}
	*  @return	$gallery	{array}
	*/
	function load_gallery_defaults( $gallery ){
		if( !empty($this->defaults) ){
			foreach( $this->defaults as $k => $v ){
				if( !isset($gallery[ $k ]) ){
					$gallery[ $k ] = $v;
				}
			}
		}
		
		return $gallery;
	}
	
}