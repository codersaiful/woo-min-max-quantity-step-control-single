<?php 

if( isset( $_POST['ca-module-submit'] ) && filter_input_array(INPUT_POST) ){
    $data = filter_input_array( INPUT_POST );
    $values = $data['data'] ?? array();
    $this->update($values);

}
$module_datas = $this->modules;
$modules_list = $this->get_module_list();
// var_dump($modules_list);
?>
<form action="" method="POST">


<div class="ca-module-list">
<?php 
foreach( $modules_list as $key=>$modl ){

    $key_name = $modl['key'] ?? '';
    $name = $modl['name'] ?? '';
    $desc = $modl['desc'] ?? '';
    $status = $modl['status'] ?? '';
    $checkbox = $status == 'off' ? '' : 'checked';
    

?>
    <div class="ca-each-module ca-m-<?php echo esc_attr( $key ); ?>">
        <input 
        id="<?php echo esc_attr( $key ); ?>"
        type="checkbox"
        value="on"
        <?php echo esc_attr( $checkbox ); ?>
        name="data[<?php echo esc_attr( $key ); ?>]">
        <label
        for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $name ); ?></label>
        <p><?php echo esc_html( $desc ); ?></p>

    </div>
<?php 
}
?>
    <div class="ca-module-submit">
        <input name="ca-module-submit" type="submit" value="Save Change">
    </div>
</div>

</form>
