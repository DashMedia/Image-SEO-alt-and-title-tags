Image-SEO-alt-and-title-tags
============================

MODX Plugin which scans page output for `img` tags and generates alt and title text if none are present.

Uses headings inside markup when possible, but will fall-back to langtitle or pagetitle if it cannot find a heading which is a child of a container.

##Options

`img_seo_containers`: comma seperated list of element types to treat as containers
`img_seo_headings`: comma seperated list of element types to treat as headings

##Manual install

create a new plugin, copy contents of `Image SEO alt and title tags` and assign new plugin to the `OnWebPagePrerender` system event.