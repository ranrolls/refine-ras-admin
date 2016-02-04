<?php
/**
 * @package Content - Gallery for K2
 * @version 1.2.0
 * @subpackage Plugins - Content
 * @copyright Copyright (C) 2012 JLEX Team - All rights reserved. (http://www.joomla-extensions.info)
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @author JLEX Team
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.filesystem.file' );
jimport ( 'joomla.filesystem.folder' );
if (! class_exists ( 'Services_JSON' ))
	require_once (dirname ( __FILE__ ) . '/extend/class/json.class.php');
class plgContentGallery extends JPlugin {
	var $id_element = 1;
	var $admin_area = false;
	var $height = 133;
	var $width = 133;
	public function __construct(& $subject, $config) {
		$this->admin_area = JFactory::getApplication ()->isAdmin ();
		parent::__construct ( $subject, $config );
		$this->loadLanguage ();
		
		// Set height
		if (( int ) $this->params->get ( 'height', 133 ) >= 10 && ( int ) $this->params->get ( 'height', 133 ) <= 500) {
			$this->height = $this->params->get ( 'height', 133 );
		}
		if (( int ) $this->params->get ( 'width', 133 ) >= 10 && ( int ) $this->params->get ( 'width', 133 ) <= 500) {
			$this->width = $this->params->get ( 'width', 133 );
		}
	}
	public function onContentPrepare($context, &$row, &$params, $page = 0) {
		$pattern = '/\{jlex\}(.*?)\{\/jlex\}/s';
		if ($this->admin_area) {
			return false;
		} else {
			$row->text = preg_replace_callback ( $pattern, array (
					$this,
					'replace_content' 
			), $row->text );
		}
	}
	public function onContentAfterSave($context, $row, $isNew) {
		set_time_limit ( 0 );
		if (! $this->admin_area)
			return false;
		require_once (dirname ( __FILE__ ) . '/extend/class/class.image.php');
		// Create cache path if not exist
		if (! JFolder::exists ( JPATH_ROOT . '/images/cache' )) {
			JFolder::create ( JPATH_ROOT . '/images/cache' );
			JFile::copy ( JPATH_ROOT . '/images/index.html', JPATH_ROOT . '/images/cache/index.html' );
		}
		$pattern = '/\{jlex\}(.*?)\{\/jlex\}/s';
		preg_replace_callback ( $pattern, array (
				$this,
				'replace_content' 
		), $row->introtext . $row->fulltext );
	}
	public function replace_content($matches) {
		if (! isset ( $matches [1] ))
			return '';
		$value = trim ( $matches [1] );
		$pattern = '/^([A-z]+)\:(.+)/s';
		preg_match ( $pattern, $value, $matches );
		if (! isset ( $matches [1] ) || ! isset ( $matches [2] ))
			return '';
		$method = strtolower ( trim ( $matches [1] ) );
		$value = trim ( $matches [2] );
		$dataSource = array ();
		$APIImageSize = array (
				0 => "'small'",
				1 => "'thumb'",
				2 => "'medium'",
				3 => "'big'",
				4 => "'original'" 
		);
		if (! $this->admin_area) {
			$script = '';
			$themes = array (
					0 => 'classic',
					1 => 'azur',
					2 => 'twelve',
					3 => 'folio',
					4 => 'miniml' 
			);
			$theme = $themes [$this->params->get ( 'themes', 1 )];
			$doc = JFactory::getDocument ();
			$doc->addScript ( 'http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js' );
			$doc->addScript ( JURI::root ( true ) . '/plugins/content/gallery/extend/galleria-1.2.9.min.js' );
		}
		
		switch ($method) {
			case 'folder' :
				$folder = trim ( strip_tags ( $value ), " \t\n\r\0\x0B\x2F" );
				$folder_path = JPATH_ROOT . '/' . $folder;
				if (! JFolder::exists ( $folder_path ))
					return '';
				$files = JFolder::files ( $folder_path, '.jpeg|.jpg|.png|.gif|.JPEG|.JPG', false, true );
				if (! $files || count ( $files ) == 0)
					return '';
				foreach ( $files as $file ) {
					$n_item = array ();
					$imageSize = filesize ( $file );
					$imageName = basename ( $file );
					$imageExt = strtolower ( JFile::getExt ( $imageName ) );
					$imageCryt = md5 ( $imageName . '-' . $imageSize . '-' . $this->height );
					if ($this->admin_area) { // Create thumbnail
						if (! JFile::exists ( JPATH_ROOT . '/images/cache/' . $imageCryt . '.' . $imageExt )) {
							$imageClass = new Image ( $file );
							$imageClass->resize ( $this->width, $this->height, $this->params->get ( 'method_resize', 'fit' ) );
							$imageClass->save ( JPATH_ROOT . '/images/cache/' . $imageCryt . '.' . $imageExt );
						}
					}
					$n_item ['image'] = JURI::root () . $folder . '/' . $imageName;
					$n_item ['thumb'] = JURI::root () . 'images/cache/' . $imageCryt . '.' . $imageExt;
					$dataSource [] = $n_item;
				}
				break;
			case 'mix' :
				$pattern = '/\{([A-z]+)\:(.+?)\}/s';
				preg_match_all ( $pattern, $value, $items, PREG_SET_ORDER );
				if (count ( $items ) == 0)
					return '';
				foreach ( $items as $item ) {
					if (! isset ( $item [1] ) || ! isset ( $item [2] ))
						continue;
					$n_item = array ();
					$type = strtolower ( trim ( strip_tags ( $item [1] ) ) );
					$type_value = trim ( $item [2] );
					$parts = explode ( '|', $type_value );
					if ($type == 'image') {
						$n_item ['image'] = $parts [0];
						if (! preg_match ( '/^http/', $parts [0] )) { // Local
							$image_url = trim ( strip_tags ( $parts [0] ), " \t\n\r\0\x0B\x2F" );
							$image_path = JPATH_ROOT . '/' . $image_url;
							if (! JFile::exists ( $image_path )) {
								continue;
							}
							$imageSize = filesize ( $image_path );
							$imageName = basename ( $image_url );
							$imageExt = JFile::getExt ( $imageName );
							
							$imageCryt = md5 ( $imageName . '-' . $imageSize . '-' . $this->height );
							if ($this->admin_area) {
								if (! JFile::exists ( JPATH_ROOT . '/images/cache/' . $imageCryt . '.' . $imageExt )) {
									$imageClass = new Image ( $image_path );
									$imageClass->resize ( $this->width, $this->height, $this->height, $this->params->get ( 'method_resize', 'fit' ) );
									$imageClass->save ( JPATH_ROOT . '/images/cache/' . $imageCryt . '.' . $imageExt );
								}
							}
							$n_item ['thumb'] = JURI::root () . 'images/cache/' . $imageCryt . '.' . $imageExt;
							$n_item ['image'] = JURI::root () . $image_url;
						}
					} elseif ($type == 'youtube' || $type = 'vimeo') {
						$n_item ['video'] = $parts [0];
					}
					isset ( $parts [1] ) ? $n_item ['title'] = $parts [1] : null;
					isset ( $parts [2] ) ? $n_item ['description'] = html_entity_decode ( $parts [2] ) : null;
					isset ( $parts [3] ) ? $n_item ['link'] = $parts [3] : null;
					$dataSource [] = $n_item;
				}
				break;
			case 'picasa' :
				if ($this->admin_area) {
					return '';
				}
				$doc->addScript ( JURI::root ( true ) . '/plugins/content/gallery/extend/plugins/picasa/galleria.picasa.min.js' );
				$maxImgPicasa = ( int ) $this->params->get ( 'maxpicasa', 30 ) >= 1 && ( int ) $this->params->get ( 'maxpicasa', 30 ) <= 100 ? ( int ) $this->params->get ( 'maxpicasa', 30 ) : 30;
				$sizeImgPicasa = array_key_exists ( ( int ) $this->params->get ( 'sizeimgpicasa', 2 ), $APIImageSize ) ? $this->params->get ( 'sizeimgpicasa', 2 ) : 2;
				$sizeImgThumbPicasa = array_key_exists ( ( int ) $this->params->get ( 'sizeimgthumbpicasa', 1 ), $APIImageSize ) ? $this->params->get ( 'sizeimgthumbpicasa', 1 ) : 1;
				$script = "Galleria.run('#galleria-{$this->id_element}', { picasa: 'useralbum:{$value}',height: 0.5625 , picasaOptions: {max:{$maxImgPicasa},imageSize:" . $APIImageSize [$sizeImgPicasa] . ",thumbSize:" . $APIImageSize [$sizeImgThumbPicasa] . "}});";
				break;
			case 'flickr' :
				if ($this->admin_area) {
					return '';
				}
				$doc->addScript ( JURI::root ( true ) . '/plugins/content/gallery/extend/plugins/flickr/galleria.flickr.min.js' );
				$maxImgFlickr = ( int ) $this->params->get ( 'maxflickr', 30 ) >= 1 && ( int ) $this->params->get ( 'maxflickr', 30 ) <= 100 ? ( int ) $this->params->get ( 'maxflickr', 30 ) : 30;
				$sizeImgFlickr = array_key_exists ( ( int ) $this->params->get ( 'sizeimgflickr', 2 ), $APIImageSize ) ? $this->params->get ( 'sizeimgflickr', 2 ) : 2;
				$sizeImgThumbFlickr = array_key_exists ( ( int ) $this->params->get ( 'sizeimgthumbflickr', 1 ), $APIImageSize ) ? $this->params->get ( 'sizeimgthumbflickr', 1 ) : 1;
				$script = "Galleria.run('#galleria-{$this->id_element}', {flickr: 'set:{$value}',flickrOptions: {sort: 'date-posted-asc'},height: 0.5625 , flickrOptions: {max:{$maxImgFlickr},imageSize:" . $APIImageSize [$sizeImgFlickr] . ",thumbSize:" . $APIImageSize [$sizeImgThumbFlickr] . "}});";
				break;
		}
		
		if ($this->admin_area) {
			return '';
		}
		
		if ($method == 'folder' || $method == 'mix') {
			if (count ( $dataSource ) == 0)
				return '';
			$jsonClass = new Services_JSON ();
			$data = $jsonClass->encode ( $dataSource );
			$script = "Galleria.run('#galleria-" . $this->id_element . "', {dataSource:{$data},height: 0.5625});";
		}
		
		// Config parameter
		$config = array ();
		$imageCrop = array (
				0 => true,
				1 => false,
				2 => "height",
				3 => "width",
				4 => "landscape",
				5 => "portrait" 
		);
		$imageTransition = array (
				0 => "fade",
				1 => "flash",
				2 => "pulse",
				3 => "slide",
				4 => "fadeslide" 
		);
		$config ['maxScaleRatio'] = 1;
		if ($theme == 'folio')
			$config ['fullscreenTransition'] = 'fade';
		if ($this->params->get ( 'autoplay', 0 ) == 1)
			$config ['autoplay'] = $this->params->get ( 'time', 7 ) * 1000;
		if ($this->params->get ( 'lightbox', false ))
			$config ['lightbox'] = true;
		if (array_key_exists ( ( int ) $this->params->get ( 'imagecrop', 2 ), $imageCrop ))
			$config ['imageCrop'] = $imageCrop [$this->params->get ( 'imagecrop', 2 )];
		if (! $this->params->get ( 'showcounter', true ))
			$config ['showCounter'] = false;
		if (array_key_exists ( ( int ) $this->params->get ( 'imagetran', '2' ), $imageTransition ))
			$config ['transition'] = $imageTransition [$this->params->get ( 'imagetran', '2' )];
		if (! $this->params->get ( 'truefullscreen', true ))
			$config ['trueFullscreen'] = false;
		if (! $this->params->get ( 'showimagenav', true ))
			$config ['showImagenav'] = false;
		
		$jsonClass = new Services_JSON ();
		$config = $jsonClass->encode ( $config );
		$config_script = "Galleria.configure(" . $config . ");";
		
		// Load libs
		$doc->addStyleDeclaration ( '.galleria-errors {display:none !important}' );
		$theme_path = JURI::root ( true ) . '/plugins/content/gallery/extend/themes/' . $theme . '/galleria.' . $theme . '.min.js';
		$theme_path_css = JURI::root ( true ) . '/plugins/content/gallery/extend/themes/' . $theme . '/galleria.' . $theme . '.css';
		$doc->addScript ( $theme_path );
		$doc->addStyleSheet ( $theme_path_css );
		
		$result = '<div id="galleria-' . $this->id_element . '"></div><script>' . $config_script . $script . '</script>';
		
		$this->id_element ++;
		return $result;
	}
}