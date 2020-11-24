<?php



add_action( 'show_user_profile', 'co_admin_show_new_fields' );
add_action( 'edit_user_profile', 'co_admin_show_new_fields' );
add_action( 'user_new_form', 'co_admin_show_new_fields' );

function co_admin_show_new_fields( $user ) { 
    
    ?>
    
    <table class="form-table">
        <tr>
            <th><label for="cedula">Cedula</label></th>
            <td>
                <input type="text" name="cedula" id="cedula" value="<?php echo esc_attr( get_the_author_meta( 'cedula', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description">Ingresa el numero de cédula.</span>
            </td>
        </tr>
    </table>
    <table class="form-table">
        <tr>
            <th><label for="hijos">Numero de hijos</label></th>
            <td>
                <input type="number" name="hijos" id="hijos" value="<?php echo esc_attr( get_the_author_meta( 'hijos', $user->ID ) ); ?>" class="regular-text" /><br />
                <span class="description">Ingresa el numero de hijos.</span>
            </td>
        </tr>
    </table>
<?php }


add_action( 'personal_options_update', 'co_admin_save_new_fields' );
add_action( 'edit_user_profile_update', 'co_admin_save_new_fields' );
add_action('user_register', 'co_admin_save_new_fields');
add_action('profile_update', 'co_admin_save_new_fields');

function co_admin_save_new_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;

    update_user_meta( $user_id, 'cedula', $_POST['cedula'] );
    update_user_meta( $user_id, 'hijos', $_POST['hijos'] );
}





//editar tabla listar usuarios
function wpseq_270133_users($columns)
{

    unset($columns['role']);
    unset($columns['posts']);
    unset($columns['name']);
    $columns['cedula'] = 'Cedula';
    $columns['hijos'] = 'N° hijos';

    return $columns;
}
add_filter('manage_users_columns', 'wpseq_270133_users');

function new_modify_user_table_row($val, $column_name, $user_id)
{
    switch ($column_name) {
        case 'cedula':
            return get_user_meta($user_id, 'cedula')[0];
        case 'hijos':
            return get_user_meta($user_id, 'hijos')[0];;
        default:
    }
    return $val;
}
add_filter('manage_users_custom_column', 'new_modify_user_table_row', 10, 3);