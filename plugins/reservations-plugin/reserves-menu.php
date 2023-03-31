<?php
/** Step 2 (from text above). */
function reservations_plugin() {
    add_menu_page(
        __('Reservations', 'reservations-plugins'),
        __('Reservations', 'reservations-plugins'),
        'manage_options',
        'reservations-settings-page',
        'reservations_settings_template_callback',
        '',
        null
    );
}
add_action('admin_menu', 'reservations_plugin');

// template
function reservations_settings_template_callback() {
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js" integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

<style>
    .demo {
        width: 100%;
        border:1px solid #C0C0C0;
        border-collapse:collapse;
        padding:5px;
    }
    .demo th {
    border:1px solid #C0C0C0;
        padding:5px;
        background:#F0F0F0;
    }
    .demo td {
        border:1px solid #C0C0C0;
        padding:5px;
    }
    button{
        border: none;
        box-shadow: 0 3px 6px #00000099;
        border-radius: 5px;
        border: 1px solid #0000005c;
        width: 90px;
    }
</style>
    <div class="wrap">
        <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    </div>
    <table class="demo">
        <caption>Reservas</caption>
        <thead>
            <tr>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Personas</th>
            <th>Hora</th>
            <th>Fecha</th>
            <th>Zona</th>
            <th>Estado</th>
            <th>Opciones</th>
            </tr>
    </thead>
    <tbody>
<?php
    global $wpdb;
    $restaurantInfoTable = $wpdb->prefix. 'restaurant_options';
    $restaurantInfo = $wpdb->get_results( "SELECT * FROM $restaurantInfoTable", OBJECT);
    $tableReserves = $wpdb->prefix.'reservation';
    $infos = $wpdb->get_results( "SELECT * FROM $tableReserves", OBJECT); 
    
    foreach ($infos as $info) {?>
        <tr>
            <td><?php echo($info->name); ?></td>
            <td><?php echo($info->phone); ?></td>
            <td><?php echo($info->email)?></td>
            <td><?php echo($info->persons); ?></td>
            <td><?php echo($info->time); ?></td>
            <td><?php echo($info->date);?></td>
            <td><?php echo($info->area); ?></td>
            <td><?php echo($info->status == 'no_confirm' ? 'No confirmado' : 'confirmado'); ?></td>
            <td style="display: flex;flex-direction: column;align-items: center;gap: 5px;">
                <form action="" method="POST">
                        <input type="hidden" hidden value="<?php echo $info->id; ?>" name="delete"/>
                        <button>Cancelar</button>
                </form>
                <?php 
                    if($info->status == 'no_confirm' ) {?>
                        <form action="" method="POST">
                        <input type="hidden" hidden value="<?php echo $info->id; ?>" name="update"/>
                            <button>Confirmar</button>
                        </form>
                <?php } ?>
            </td>
	    </tr>
<?php
    }
    if($_POST['update']){
        $id = $_POST['update'];
        
        $wpdb->update($tableReserves, array( 'status' => 'confirm'), array( 'id' => $id ));

        $current_user = wp_get_current_user();
        $to = $info->email;
        $subject = "Su reserva en " . $restaurantInfo[0]->name . " a sido confirmada" ;
        $body = "
            Su reserva en " . $restaurantInfo[0]->name . "
            a las $info->time hs del día $info->date a sido confirmada!
        ";
        $headers = "From: " . $current_user->user_email ."";

        wp_mail( $to, $subject, $body, $headers );

        echo "<meta http-equiv='refresh' content='0'>";
    }else if($_POST['delete']){
        $id = $_POST['delete'];

        $wpdb->delete($tableReserves, array( 'id' => $id ));

        echo "<meta http-equiv='refresh' content='0'>";
    }
?>
	</tbody>
    </table>
<?php
}
?>
