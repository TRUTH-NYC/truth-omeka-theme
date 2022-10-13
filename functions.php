<?php
/**
 * Modify a hex color by the given number of steps (out of 255).
 *
 * Adapted from a solution by Torkil Johnsen.
 *
 * @param string $color
 * @param int $steps
 * @link http://stackoverflow.com/questions/3512311/how-to-generate-lighter-darker-color-with-php
 */
function thanksroy_brighten($color, $steps) {
    $steps = max(-255, min(255, $steps));
    $hex = str_replace('#', '', $color);
    $r = hexdec(substr($hex,0,2));
    $g = hexdec(substr($hex,2,2));
    $b = hexdec(substr($hex,4,2));

    $r = max(0,min(255,$r + $steps));
    $g = max(0,min(255,$g + $steps));  
    $b = max(0,min(255,$b + $steps));

    $r_hex = str_pad(dechex($r), 2, '0', STR_PAD_LEFT);
    $g_hex = str_pad(dechex($g), 2, '0', STR_PAD_LEFT);
    $b_hex = str_pad(dechex($b), 2, '0', STR_PAD_LEFT);

     return '#'.$r_hex.$g_hex.$b_hex;
}
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
    return files_for_item(null, null, $videoItem);
}