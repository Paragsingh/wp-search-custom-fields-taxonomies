<?php
//COPY THIS CODE INTO YOUR THEME OR PLUGIN
add_filter('posts_join','custom_search_join');
add_filter('posts_where','custom_search_where');
add_filter('posts_distinct','custom_search_distinct');

function custom_search_join($join){

      global $wpdb;

    if ( is_search() ) {
        $join .=' LEFT JOIN '.$wpdb-&gt;postmeta. ' ON '. $wpdb-&gt;posts . '.ID = ' . $wpdb-&gt;postmeta . '.post_id ';
        $join .= "LEFT JOIN {$wpdb-&gt;term_relationships} tr ON {$wpdb-&gt;posts}.ID = tr.object_id INNER JOIN {$wpdb-&gt;term_taxonomy} tt ON tt.term_taxonomy_id=tr.term_taxonomy_id INNER JOIN {$wpdb-&gt;terms} t ON t.term_id = tt.term_id";
        }

    return $join;
}

function custom_search_where($where){

    global $pagenow, $wpdb;

    if ( is_search() ) {
        $where = preg_replace(
            "/\(\s*".$wpdb-&gt;posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
            "(".$wpdb-&gt;posts.".post_title LIKE $1) OR (".$wpdb-&gt;postmeta.".meta_value LIKE $1)", $where );
              $where .= "OR (t.name LIKE '%".get_search_query()."%' AND {$wpdb-&gt;posts}.post_status = 'publish')";



    }

    return $where;

}

function custom_search_distinct($where){
    global $wpdb;

    if ( is_search() ) {
        return "DISTINCT";
    }

    return $where;
}
