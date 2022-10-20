<?php

function theme_sub_logo()
{
    $logo = get_theme_option('Sub Logo');
    if ($logo) {
        $storage = Zend_Registry::get('storage');
        $uri = $storage->getUri($storage->getPathByType($logo, 'theme_uploads'));
        return '<img src="' . $uri . '" alt="' . option('site_title') . '" />';
    }
}
function get_year_collections() {
    $collections = get_records('Collection', array(), 250);
    $year_collections = array_filter($collections, function($col) {
        return metadata($col, array('Dublin Core', 'Type')) == 'year';
    });
    return $year_collections;
}
function get_special_collection($name) {
    $collections = get_records('Collection', array(), 50);
    $collection = false;
    // for($i = 0, $size = count($collections); $i < $size; ++$i) {
    //     $col = $collections;
    //     echo all_element_texts($col);
    //     if($col != NULL) {
    //         print metadata($col, array('Dublin Core', 'Title'));
    //     } else {
    //         print 'no col';
    //     }
    //     // if( strtolower(metadata($col, array('Dublin Core', 'Title'))) == strtolower($name)) {
    //         $collection = $col;
    //         break;
    //     // }
    //     }
    return $collection;
}
// function link_to_home_page($text = null, $props = array())
// {
//     if (!$text) {
//         $text = option('site_title');
//     }
//     return '<a href="' . html_escape(WEB_ROOT) . '" '. tag_attributes($props) . '>' . $text . "</a>\n";
// }

function get_homepage_video() {
    $Items = get_records('Item', array(), 250);
    $Item = array_filter($Items, function($item) {
        return metadata($item, array('Dublin Core', 'Type')) == 'Homepage Video';
    });
    
    $videoItem = array_values($Item)[0];
    return '<figure>' .
    files_for_item(null, null, $videoItem) .
    '<figcaption>' . metadata($videoItem, array('Dublin Core', 'Source')) . '</figcaption>' .
    '</figure>';
}