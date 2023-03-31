<?php
/** Step 2 (from text above). */
function reserves_options() {
    add_menu_page(
        __('Reserves Options', 'reserves-options'),
        __('Reserves Options', 'reserves-options'),
        'manage_options',
        'reserves-settings-page',
        'reserves_settings_template_callback',
        '',
        null
    );
}
add_action('admin_menu', 'reserves_options');

function reserves_settings_template_callback() {
    global $wpdb;
    $tableRestaurantOptions = $wpdb->prefix . 'restaurant_options';
    $tableDates = $wpdb->prefix . 'available_days';
    $tableTimes = $wpdb->prefix . 'available_times';
    $tableAreas = $wpdb->prefix . 'zones';
    $tableDatesRelationships = $wpdb->prefix . 'zones_available_times';
    $options = $wpdb->get_results( "SELECT * FROM $tableRestaurantOptions", OBJECT);
?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
    .button-n{
    padding: 10px 14px;
    background: #2271b1;
    color: white;
    text-decoration: none;
    border-radius: 3px;
    font-size: 15px;
    }
    .button-n:hover {
        color: white;
        background: #135e96;
    }
    .select2-container--default {
        width: 100% !important;
    }
    hr{
        margin: 0 !important;
    }
</style>
<?php
    if(!$options){
    ?>
        <div class="wrap">
            <h2>Para comenzar llena el siguiente formulario</h2>
            <form action="" method="post">
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row">Nombre del restaurant</th>
                            <td>
                                <input name="name" type="text" class="regular-text" value="" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Direccion del restaurant</th>
                            <td>
                                <input name="direction" type="text" class="regular-text" value="" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">¿Qué días trabaja?</th>
                            <td>
                                <select id="days" class="select2-selection select2-selection--multiple" name="days[]" multiple="multiple">
                                    <option value="1">Lunes</option>
                                    <option value="2">Martes</option>
                                    <option value="3">Miercoles</option>
                                    <option value="4">Jueves</option>
                                    <option value="5">Viernes</option>
                                    <option value="6">Sabado</option>
                                    <option value="7">Domingo</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">¿Qué zonas tiene el restaurante?</th>
                            <td>
                                <select class="form-control" multiple="multiple" id="zones" name="zones[]">
                                    
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php submit_button('Guardar') ?>
            </form>
        </div>
        <script>
           $(document).ready(function() {
                $('#days').select2();
                $("#zones").select2({
                    tags: true,
                    tokenSeparators: [',', ' ']
                })
            });
        </script>
    <?php
        if($_POST['direction']){
            $name = $_POST['name'];
            $direction = $_POST['direction'];
            $daysinput = $_POST['days'];
            $zonesInput = $_POST['zones'];
            $data = array(
                'name'      => $name,
                'direction' => $direction
            );

            foreach ($daysinput as $v) {
                $day = $wpdb->get_results( "SELECT * FROM $tableDates WHERE id = $v", OBJECT);
                if($day){
                    $wpdb->update($tableDates, array( 'status' => 'true'), array( 'id' => $v ));
                }
            }

            foreach ($zonesInput as $zone){
                $wpdb->insert($tableAreas, array('area' => $zone));
            }

            $wpdb->insert($tableRestaurantOptions, $data);

            echo "<meta http-equiv='refresh' content='0'>";
        }
    } else if($_GET['day']){?>
        <div class="wrap">
            <h2>Dias a eliminar</h2>
            <form action="" method="post">
            <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row">Selecciona los dias</th>
                            <td>
                                <select id="days" class="select2-selection select2-selection--multiple" name="days[]" multiple="multiple">
                                    <option value="1">Lunes</option>
                                    <option value="2">Martes</option>
                                    <option value="3">Miercoles</option>
                                    <option value="4">Jueves</option>
                                    <option value="5">Viernes</option>
                                    <option value="6">Sabado</option>
                                    <option value="7">Domingo</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Opciones</th>
                            <td>
                                <select class="js-example-basic-single" name="option" required>
                                    <option value="delete">Eliminar dias </option>
                                    <option value="add">Agregar dias</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php submit_button('Guardar') ?>
                <a href="?page=reserves-settings-page" class="button-n">Atras</a>
            </form>
        </div>
        <script>
           $(document).ready(function() {
                $('#days').select2();
            });
        </script>
    <?php 
        if($_POST['days']){
            $daysinput = $_POST['days'];
            $optionDay = $_POST['option'];
            if($optionDay == 'delete'){
                foreach ($daysinput as $v) {
                    $wpdb->update($tableDates, array( 'status' => 'false'), array( 'id' => $v ));
                }
                echo('<meta http-equiv="refresh" content="0;">');
            }else if($optionDay == 'add'){
                foreach ($daysinput as $v) {
                    $day = $wpdb->get_results( "SELECT * FROM $tableDates WHERE id = $v", OBJECT);
                    if($day){
                        $wpdb->update($tableDates, array( 'status' => 'true'), array( 'id' => $v ));
                    }
                }
                echo('<meta http-equiv="refresh" content="0;">');
            }
        }
    } else if($_GET['zone']){?>
        <div class="wrap">
            <h2>Dias a eliminar</h2>
            <form action="" method="post">
            <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row">Selecciona o escribe las zonas</th>
                            <td>
                            <select class="form-control" multiple="multiple" id="zones" name="zones[]">
                                    <?php
                                        $reservesZones = $wpdb->get_results( "SELECT * FROM $tableAreas", OBJECT);

                                        foreach($reservesZones as $zone){
                                            echo '<option selected="selected">' . $zone->area .'</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Opciones</th>
                            <td>
                                <select class="js-example-basic-single" name="option" required>
                                    <option value="add">Agregar zonas</option>
                                    <option value="delete">Eliminar zonas </option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php submit_button('Guardar') ?>
                <a href="?page=reserves-settings-page" class="button-n">Atras</a>
            </form>
        </div>
        <script>
           $(document).ready(function() {
            $("#zones").select2({
                    tags: true,
                    tokenSeparators: [',', ' ']
                })
            });
        </script>
    <?php 
        if($_POST['zones']){
            $zonesinput = $_POST['zones'];
            $optionDay = $_POST['option'];

            if($optionDay == 'add'){
                foreach($zonesinput as $zone){
                    $reserveZone = $wpdb->get_results( "SELECT * FROM $tableAreas WHERE area = '$zone'", OBJECT);
                    if(sizeof($reserveZone) <= 0){
                        $wpdb->insert($tableAreas, array('area' => $zone));
                    }
                }
                echo('<meta http-equiv="refresh" content="0;">');
            }else if($optionDay == 'delete'){
                foreach($zonesinput as $zone){
                    $reserveZone = $wpdb->get_results( "SELECT * FROM $tableAreas WHERE area = '$zone'", OBJECT);
                    if(sizeof($reserveZone) > 0){
                        foreach($reserveZone as $z){
                            $zonesRelationships = $wpdb->get_results( "SELECT * FROM $tableDatesRelationships WHERE zone_id = '$z->id'", OBJECT);
                            foreach($zonesRelationships as $re){
                                $wpdb->delete($tableDatesRelationships, array( 'zone_id' => $z->id ));
                            }
                        }
                        $wpdb->delete($tableAreas, array( 'area' => $zone ));
                    }
                }
                echo('<meta http-equiv="refresh" content="0;">');
            }

        }
    } else{
        $reservesTimes = $wpdb->get_results( "SELECT * FROM $tableTimes", OBJECT);
        $reservesZones = $wpdb->get_results( "SELECT * FROM $tableAreas", OBJECT);
        ?>
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
        </style>
        <div class="wrap">
            <h2>Seleccione la disponibilidad</h2>
            <form action="" method="post">
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row">Seleccione el día</th>
                            <td>
                                <select class="js-example-basic-single" name="day">
                                    <?php
                                        $days = $wpdb->get_results( "SELECT * FROM $tableDates WHERE status = 'true'", OBJECT);
                                        foreach($days as $d){
                                            
                                            switch ($d->date) {
                                                case "Mon":
                                                    echo '<option value="1">Lunes</option>';
                                                    break;
                                                case "Tue":
                                                    echo '<option value="2">Martes</option>';
                                                    break;
                                                case "Wed":
                                                    echo '<option value="3">Miercoles</option>';
                                                    break;
                                                case "Thu":
                                                    echo '<option value="4">Jueves</option>';
                                                    break;
                                                case "Fri":
                                                    echo '<option value="5">Viernes</option>';
                                                    break;
                                                case "Sat":
                                                    echo '<option value="6">Sabado</option>';
                                                    break;
                                                case "Sun":
                                                    echo '<option value="7">Domingo</option>';
                                                    break;
                                            }
                                            
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Selecione la zona</th>
                            <td>
                                <select class="js-example-basic-single" name="zone">
                                    <?php
                                        foreach ($reservesZones as $zone){
                                            echo '<option value="' . $zone->id .'">' . $zone->area . '</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Horas disponibles</th>
                            <td>
                                <select id="times" class="select2-selection select2-selection--multiple" name="times[]" multiple="multiple">
                                    <?php
                                        foreach ($reservesTimes as $time){
                                            echo '<option value="' . $time->id .'">' . $time->time . '</option>';
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php submit_button('Guardar') ?>
                <a href="?page=reserves-settings-page&day=true" class="button-n">Opciones dias</a>
                <a href="?page=reserves-settings-page&zone=true" class="button-n">Opciones zonas</a>
            </form>
            <table class="demo">
	        <caption>Disponibilidad</caption>
	        <thead>
                <tr>
                    <th>Dias disponibles</th>
                    <th>Zonas disponibles</th>
                    <th>Horas disponibles</th>
                </tr>
            </thead>
            <tbody>
                
                <?php 
                    $days = $wpdb->get_results( "SELECT * FROM $tableDates WHERE status = 'true'", OBJECT);
                    foreach($days as $d){
                        $zonesA = $wpdb->get_results ( "
                            SELECT DISTINCT(zo.area), zo.id
                            FROM $tableDatesRelationships as zat, $tableAreas as zo 
                            WHERE zat.date_id = $d->id AND zo.id = zat.zone_id
                        " );
                    ?>
                        <tr>
                            <td><?php 
                                 switch ($d->date) {
                                case "Mon":
                                    echo 'Lunes';
                                    break;
                                case "Tue":
                                    echo 'Martes';
                                    break;
                                case "Wed":
                                    echo 'Miercoles';
                                    break;
                                case "Thu":
                                    echo 'Jueves';
                                    break;
                                case "Fri":
                                    echo 'Viernes';
                                    break;
                                case "Sat":
                                    echo 'Sabado';
                                    break;
                                case "Sun":
                                    echo 'Domingo';
                                    break;
                            }
                            ?></td>
                            <td>
                            <?php
                                foreach($zonesA as $availableZ){ 
                                    echo('<section>' . $availableZ->area .'</section> <hr/>');
                                }
                            ?>
                            </td>
                            <td colspan="2">
                            <?php
                                foreach($zonesA as $availableZ){ 
                                    $timesZ = $wpdb->get_results ( "
                                        SELECT DISTINCT(at.time), at.id
                                        FROM $tableDatesRelationships as zat, $tableTimes as at
                                        WHERE zat.date_id = $d->id AND zat.zone_id = $availableZ->id AND at.id = zat.time_id
                                        ", OBJECT );?>
                                    <section>
                                    <?php
                                        for ($i = 0; $i <= 10; $i++) {
                                            if($timesZ[$i]){
                                                echo($timesZ[$i]->time . ', ');
                                            }
                                        }?>
                                        ...
                                    </section><hr/>
                                    <?php
                                }
                            ?>
                            </td>
                        </tr>
                    <?php 
                    }
                    ?>
                    
            </tbody>
    </table>
        </div>
        <script>
           $(document).ready(function() {
                $("#times").select2({
                    tags: true,
                    tokenSeparators: [',', ' ']
                })
            });
        </script>
        
    <?php
        if($_POST['times']){
            $day   = $_POST['day'];
            $times = $_POST['times'];
            $zone  = $_POST['zone'];
            $datesRelationships = $wpdb->get_results( "SELECT * FROM $tableDatesRelationships WHERE date_id = $day AND zone_id = $zone", OBJECT);
            
            if (sizeof($datesRelationships) == 0){
                foreach ($times as $time) {
                    $wpdb->insert($tableDatesRelationships, array('time_id' => $time, 'zone_id' => $zone, 'date_id' => $day));
                }    
            }else {
                foreach ($times as $time) {
                    $datesZonesRelationships = $wpdb->get_results( "SELECT * FROM $tableDatesRelationships WHERE date_id = $day AND zone_id = $zone AND time_id = $time", OBJECT);
                    if (sizeof($datesZonesRelationships) == 0){
                        $wpdb->insert($tableDatesRelationships, array('time_id' => $time, 'zone_id' => $zone, 'date_id' => $day));
                    }
                }
            }
            echo "<meta http-equiv='refresh' content='0'>";
        }

    }
}
?>
