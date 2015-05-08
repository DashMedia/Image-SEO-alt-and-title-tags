Image-SEO (alt and title tags)
============

Scans the rendered output of every html page for images (pages who's `content_type = 1`), then checks if they have empty alt or title text, if they do, the nearest heading will be used as the missing values.


Installation
============

Install via package management or grab the .zip from the lastest release

Setup
=====

- `imageseo.headings`: this is the headings to use as alt and/or title text on images which do not have either
	- default: `h1,h2,h3,h4,h5,h6`
- `imageseo.containers`: this specifies which elements to scan for images
	- default: `article, section, header, footer, aside`


Known issues
============

As this uses the default `DOMDocument` class which relies on php's `libxml2` you can end up with some odd errors with CDATA and some non HTML4 markup

Author: Jason Carney <jason@dashmedia.com.au>

Documentation and bugs <https://github.com/DashMedia/Image-SEO-alt-and-title-tags>

Built using Repoman <https://github.com/craftsmancoding/repoman>