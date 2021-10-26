<?php

/**
 * Setting page link from plugin page, so that: user can get easily from Plugin Install page.
 * 
 * @param type $links Getting wordpress default array as name $links
 * @return type Array
 * @since 1.0.0
 */
function wcmmq_add_action_links($links) {
    $wpt_links[] = '<a href="' . admin_url('admin.php?page=wcmmq_min_max_step') . '" title="Setting">Settings</a>';
    return array_merge($wpt_links, $links);
}
add_filter('plugin_action_links_woo-min-max-quantity-step-control-single/wcmmq.php', 'wcmmq_add_action_links');