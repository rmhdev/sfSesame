<?php

/**
 * @param sfPager $pager
 * @param string $route
 * @return string 
 */
function sesame_render_pager($pager, $route){
    $html = "<div class=\"pagination\">\n";
    $html .= "<ul>\n";
    
    $urlPattern = "{$route}?page=%d";
    
    $html .= sprintf("  <li class=\"prev %s\"><a href=\"%s\">Previous</a></li>\n", 
        $pager->isFirstPage() ? "disabled" : "",
        $pager->isFirstPage() ? "#" : url_for(sprintf($urlPattern, 1))
    );
    
    foreach ($pager->getLinks(5) as $page){
        $html .= sprintf("  <li class=\"%s\">%s</li>\n", 
            ($pager->getPage() == $page) ? "active" : "",
            link_to($page, url_for(sprintf($urlPattern, $page)))
        );
    }
    
    $html .= sprintf("  <li class=\"next %s\"><a href=\"%s\">Next</a></li>\n", 
        $pager->isLastPage() ? "disabled" : "",
        $pager->isLastPage() ? "#" : url_for(sprintf($urlPattern, $pager->getNextPage()))
    );
    
    $html .= "</ul>\n";
    $html .= "</div>\n";
    
    return $html;
}
/**
 * @param string $label
 * @param string $route
 * @param array $options
 * @return string  
 */
function sesame_link_to_sort($label, $route, $options = array()) {
    if (!isset($options['sort']) || !isset($options['sort_type'])) {
        return $label;
    }
    if (!isset($options['field'])) {
        return $label;
    }
    
    $sortType = "asc";
    if (($options['sort'] === $options['field'])) {
        $label .= sprintf(" (%s)", $options['sort_type']);
        $sortType = ($options['sort_type'] === 'asc') ? 'desc' : 'asc';
    }
    $queryString = sprintf("sort=%s&sort_type=%s", $options['field'], $sortType);
    $options['query_string'] = $queryString;
    
    return link_to($label, $route, $options);
}
