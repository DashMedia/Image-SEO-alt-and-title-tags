<?php
$containerConfig = $modx->getOption('img_seo_containers');
$containerConfig = $containerConfig ? $containerConfig : 'article, section, header, footer, aside';

$headerConfig = $modx->getOption('img_seo_headings');
$headerConfig = $headerConfig ? $headerConfig : 'h1,h2,h3,h4,h5,h6';

$containers = explode($containerConfig, ',');
$headers = explode($headerConfig, ',');
$output = &$modx->resource->_output; // get a reference to the output
$dom = new DOMDocument;
@$dom->loadHTML($output);

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