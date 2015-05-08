<?php
/*-----------------------------------------------------------------
 * Lexicon keys for System Settings follows this format:
 * Name: setting_ + $key
 * Description: setting_ + $key + _desc
 -----------------------------------------------------------------*/
return array(

    array(
        'key'  		=>     'imageseo.headings',
		'value'		=>     'h1,h2,h3,h4,h5,h6',
		'xtype'		=>     'textfield',
		'namespace' => 'imageseo',
		'area' 		=> 'imageseo:default'
    ),
    array(
        'key'  		=>     'imageseo.containers',
		'value'		=>     'article, section, header, footer, aside',
		'xtype'		=>     'textfield',
		'namespace' => 'imageseo',
		'area' 		=> 'imageseo:default'
    ),
);
/*EOF*/