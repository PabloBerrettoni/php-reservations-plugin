<?php 
function available_days(){
    global $wpdb;
    $tableDates = $wpdb->prefix . 'available_days';

    $days = $wpdb->get_results ( "
        SELECT *
        FROM $tableDates
    " );
    
    $available = [];

    if(sizeof($days) > 0){

        foreach($days as $day){   
            if($day->status == 'true'){
                array_push($available, ['id' => $day->id]);
            }
        }
    }

    wp_send_json_success($available);
}

add_action('wp_ajax_send_available_days', 'available_days');
add_action('wp_ajax_nopriv_send_available_days', 'available_days');

function available_zones_by_day(){
    global $wpdb;
    $dayP = $_POST['day'];
    $tableDates = $wpdb->prefix . 'available_days';
    $tableAreas = $wpdb->prefix . 'zones';
    $tableDatesRelationships = $wpdb->prefix . 'zones_available_times';

    $days = $wpdb->get_results ( "
        SELECT *
        FROM $tableDates
        WHERE date = '$dayP'
    " );

    $zones = [];

    if(sizeof($days) > 0){
        foreach($days as $day){   
            $zonesA = $wpdb->get_results ( "
                SELECT DISTINCT(zo.area), zo.id
                FROM $tableDatesRelationships as zat, $tableAreas as zo 
                WHERE zat.date_id = $day->id AND zo.id = zat.zone_id
            " );
            foreach($zonesA as $availableZ){ 
                array_push($zones, ['zone' => $availableZ->area, 'id' => $availableZ->id, 'day_id' => $day->id]);
            }
        }
    }

    wp_send_json_success($zones);
}

add_action('wp_ajax_send_available_zones', 'available_zones_by_day');
add_action('wp_ajax_nopriv_send_available_zones', 'available_zones_by_day');

function available_times_by_zone(){
    global $wpdb;
    $dayP = $_POST['day'];
    $zoneId = $_POST['zone'];
    $tableDates = $wpdb->prefix . 'available_days';
    $tableAreas = $wpdb->prefix . 'zones';
    $tableTimes = $wpdb->prefix . 'available_times';
    $tableDatesRelationships = $wpdb->prefix . 'zones_available_times';

    $days = $wpdb->get_results ( "
        SELECT *
        FROM $tableDates
        WHERE date = '$dayP'
    " );

    $times = [];

    if(sizeof($days) > 0){
        foreach($days as $day){ 
            $timesZ = $wpdb->get_results ( "
                SELECT DISTINCT(at.time), at.id
                FROM $tableDatesRelationships as zat, $tableTimes as at
                WHERE zat.date_id = $day->id AND zat.zone_id = $zoneId AND at.id = zat.time_id
            " );
            foreach($timesZ as $availableZ){ 
                array_push($times, ['time' => $availableZ->time, 'id' => $availableZ->id]);
            }
    }}

    wp_send_json_success($times);
}

add_action('wp_ajax_send_available_times', 'available_times_by_zone');
add_action('wp_ajax_nopriv_send_available_times', 'available_times_by_zone');

function available_reservation(){
    global $wpdb;
    $tableInfo = $wpdb->prefix . 'reservation';
    $date = $_POST['date'];
    $area = $_POST['area'];
    $time = $_POST['time'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $persons = $_POST['persons'];
    $email = $_POST['email'];
    $cbox1 = $_POST['cbox1'];
    $cbox2 = $_POST['cbox2'];
    $cbox3 = $_POST['cbox3'];

    $dataInsert = array(
        'name'    => $name,
        'email'   => $email,
        'persons' => $persons,
        'area'    => $area,
        'date'    => $date ,
        'time'    => $time,
        'phone'   => $phone,
        'status'  => 'no_confirm',
        'cbox1'   => $cbox1,
        'cbox2'   => $cbox2,
        'cbox3'   => $cbox3,
    );

    $wpdb->insert($tableInfo, $dataInsert);

    wp_send_json_success(true);
}

add_action('wp_ajax_send_reservation', 'available_reservation');
add_action('wp_ajax_nopriv_send_reservation', 'available_reservation');

function restaurant_info(){
    global $wpdb;
    $tableRestaurantInfo = $wpdb->prefix.'restaurant_info';
    $infos = $wpdb->get_results( "SELECT * FROM $tableRestaurantInfo", OBJECT); 
    
    $sections = [];
    if(sizeof($infos) > 0){
        foreach($infos as $info){
            array_push($sections, $info);
        }
    }

    wp_send_json_success($sections);
}

add_action('wp_ajax_send_restaurant_info', 'restaurant_info');
add_action('wp_ajax_nopriv_send_restaurant_info', 'restaurant_info');

function reservations(){
    global $wpdb;
    $tableReservations = $wpdb->prefix . 'reservation';
    $reserves = $wpdb->get_results( "SELECT * FROM $tableReservations", OBJECT); 
    
    wp_send_json_success($reserves);
}

add_action('wp_ajax_send_reservations', 'reservations');
add_action('wp_ajax_nopriv_send_reservations', 'reservations');

function enqueue_assets()
{
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css">';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.min.js"></script>';
}
add_action('wp_enqueue_scripts', 'enqueue_assets');
function add_this_script_footer(){
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>';
} 
add_action('wp_footer', 'add_this_script_footer'); ?>