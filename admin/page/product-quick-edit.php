<?php
use Product_Stock_Sync\App\Handle\Quick_Table;

?>
<div class="wrap wcmmq_wrap wcmmq-content">

    <h1 class="wp-heading "></h1>
    <div class="fieldwrap">

    <?php
    if( class_exists( 'Product_Stock_Sync\App\Handle\Quick_Table' ) ){
        $Quick_Table = Quick_Table::init();
        $Quick_Table->display_table_full();
    }else{
    
    /**
     * ekhane amra debo jate
     * install na thakle free version install dite pare
     */
        
    }
    
    ?>

        
    </div>
</div>