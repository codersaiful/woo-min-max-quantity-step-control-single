<?php

add_filter('plugin_action_links_WC_Min_Max_Quantity/wcmmq.php', 'wcmmq_add_action_links');
//add_filter('plugin_action_links_woo-product-table-pro/woo-product-table-pro.php', 'wpt_add_action_links');

function wcmmq_add_action_links($links) {
    $wpt_links[] = '<a href="' . admin_url('admin.php?page=wcmmq_min_max_step') . '" title="Setting">Settings</a>';
    //$wpt_links[] = '<a href="' . admin_url('admin.php?page=woo-product-table-config') . '" title="Configure for default">Configure</a>';
    //$wpt_links[] = '<a title="See FAQ - How to use." href="' . admin_url('admin.php?page=woo-product-table-faq') . '">FAQ - Shortcode</a>';
    //$links[] = '<a href="' . admin_url( 'options-general.php?page=myplugin' ) . '">Settings</a>';
    return array_merge($wpt_links, $links);
}
