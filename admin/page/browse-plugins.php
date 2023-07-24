<div class="wrap wcmmq_wrap wcmmq-content">
    <h1 class="wp-heading "></h1>
    <div class="fieldwrap">

        <div class="wcmmq-section-panel no-background wcmmq-clearfix">
            <?php 
            
            $wp_list_table = _get_list_table( 'WP_Plugin_Install_List_Table' );
            
            $wp_list_table->prepare_items();

            echo '<form id="plugin-filter" method="post">';
            $wp_list_table->display();
            echo '</form>';
            ?>
        </div>

    </div>

</div>