<?php
/**
 * @name Image SEO alt and title tags
 * @description This is an example plugin.  List the events it attaches to in the PluginEvents.
 * @PluginEvents OnWebPagePrerender
 */

// Your core_path will change depending on whether your code is running on your development environment
// or on a production environment (deployed via a Transport Package).  Make sure you follow the pattern
// outlined here. See https://github.com/craftsmancoding/repoman/wiki/Conventions for more info
$core_path = $modx->getOption('imageseo.core_path', null, MODX_CORE_PATH.'components/imageseo/');
include_once $core_path .'vendor/autoload.php';

use Masterminds\HTML5;

if($modx->resource->get('content_type') == 1){ //only run on HTML doc types
	$containerConfig = $modx->getOption('imageseo.containers');
	$containerConfig = $containerConfig ? $containerConfig : 'article, section, header, footer, aside';

	$headerConfig = $modx->getOption('imageseo.headings');
	$headerConfig = $headerConfig ? $headerConfig : 'h1,h2,h3,h4,h5,h6';

	$containers = explode(',', $containerConfig);
	$headers = explode(',', $headerConfig);
	$output = &$modx->resource->_output; // get a reference to the output
	$html5 = new HTML5();
	@$dom = $html5->loadHTML($output);

	foreach ($dom->getElementsByTagName('img') as $node) {
		//find parents matching container array
		if(!$node->getAttribute('alt')){
			// we do not have alt text
			setAltText($node, $headers, $containers, $modx);
		}
		if(!$node->getAttribute('title')){
			//we do not have title text, set it to the same as the alt text
			$node->setAttribute('title', $node->getAttribute('alt'));
		}
	}

	$output = $dom->saveHTML();
}

function getParentNode($node, $types){
	$parent = $node->parentNode;
	if(in_array($parent->tagName, $types)){
		return $parent;
	} else {
		if($parent->tagName == 'body'){
			return false;
		} else {
			return getParentNode($parent, $types);
		}
	}
}

function getHeadingNode($parent, $headers, $containers){
	$search = true;
	$index = 0;
	for($index = 0; $index < count($headers); $index++){
		$tmp = $parent->getElementsByTagName($headers[$index]);
		if($tmp->length > 0){
			//we have headings, return the first
			return $tmp->item(0);
		}
	}
	$nextParent = getParentNode($parent, $containers);
	if($nextParent){
		return getHeadingNode($nextParent, $headers, $containers);
	} else {
		// no more parents
		return false;
	}
}

function setAltText($node, $headers, $containers, $modx){
	$heading = getHeadingNode($node->parentNode, $headers, $containers);
	if($heading){
		//we've found a heading, use it's value as image alt
		$node->setAttribute('alt', $heading->nodeValue);
	} else {
		//use pagetitle
		$alt = $modx->resource->get('pagetitle');
		$longtitle = $modx->resource->get('longtitle');
		if($longtitle){
			$alt = $longtitle;
		}
		$node->setAttribute('alt', $alt);
	}
}