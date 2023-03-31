<?php
// Creates tables in datebase
function db_creating_tables_reservations() {
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    global $wpdb;
    $tableInfo = $wpdb->prefix . 'reservation';
    $tableDates = $wpdb->prefix . 'available_days';
    $tableTimes = $wpdb->prefix . 'available_times';
    $tableAreas = $wpdb->prefix . 'zones';
    $tableDatesRelationships = $wpdb->prefix . 'zones_available_times';
    $tableRestaurantInfo = $wpdb->prefix . 'restaurant_info';
    $tableRestaurantOptions = $wpdb->prefix . 'restaurant_options';
    $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";
    $format = array('%s','%d');

    $wpdb->query(
        "CREATE TABLE IF NOT EXISTS $tableInfo (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            phone int(100) NOT NULL,
            email varchar(100) NOT NULL,
            persons int(5) NOT NULL,
            area varchar(100) NOT NULL,
            date varchar(50) NOT NULL,
            time varchar(10) NOT NULL,
            status varchar(100) NOT NULL,
            cbox1 varchar(100) NOT NULL,
            cbox2 varchar(100) NOT NULL,
            cbox3 varchar(100) NOT NULL,
            PRIMARY KEY (id)
        ) {$charset_collate};"   
    );
    dbDelta();

    $wpdb->query(
        "CREATE TABLE IF NOT EXISTS $tableTimes (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            time varchar(10) NOT NULL,
            PRIMARY KEY (id)
        ) {$charset_collate};"   
    );
    dbDelta();

    $wpdb->query(
        "CREATE TABLE IF NOT EXISTS $tableAreas (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            area varchar(100) NOT NULL,
            PRIMARY KEY (id)
        ) {$charset_collate};"   
    );
    dbDelta();

    $wpdb->query(
        "CREATE TABLE IF NOT EXISTS $tableDates (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            date varchar(50) NOT NULL,
            status varchar(100) NOT NULL,
            PRIMARY KEY (id)
        ) {$charset_collate};"   
    );
    dbDelta();

     $wpdb->query(
        "CREATE TABLE IF NOT EXISTS $tableDatesRelationships (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            time_id bigint(20) NOT NULL,
            zone_id bigint(20) NOT NULL,
            date_id bigint(20) NOT NULL,
            PRIMARY KEY  (id),
            KEY time_key (time_id),
            KEY area_key (zone_id),
            KEY date_key (date_id),
            FOREIGN KEY (time_id) REFERENCES $tableTimes(id),
            FOREIGN KEY (zone_id) REFERENCES $tableAreas(id),
            FOREIGN KEY (date_id) REFERENCES $tableDates(id)
        ) {$charset_collate};"
    );
    dbDelta();

    $wpdb->query(
        "CREATE TABLE IF NOT EXISTS $tableRestaurantInfo (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            title varchar(100) NOT NULL,
            body varchar(300) NOT NULL,
            PRIMARY KEY (id)
        ) {$charset_collate};"   
    );
    dbDelta();

    $wpdb->query(
        "CREATE TABLE IF NOT EXISTS $tableRestaurantOptions (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            direction varchar(200) NOT NULL,
            PRIMARY KEY (id)
        ) {$charset_collate};"   
    );
    dbDelta();

    $days = $wpdb->get_results( "SELECT id FROM $tableDates", OBJECT);
    $times = $wpdb->get_results( "SELECT id FROM $tableTimes", OBJECT);

    if (!$days) {
        $wpdb->insert($tableDates, array('id' => '1', 'date' => 'Mon', 'status' => 'false'));
        $wpdb->insert($tableDates, array('id' => '2', 'date' => 'Tue', 'status' => 'false'));
        $wpdb->insert($tableDates, array('id' => '3', 'date' => 'Wed', 'status' => 'false'));
        $wpdb->insert($tableDates, array('id' => '4', 'date' => 'Thu', 'status' => 'false'));
        $wpdb->insert($tableDates, array('id' => '5', 'date' => 'Fri', 'status' => 'false'));
        $wpdb->insert($tableDates, array('id' => '6', 'date' => 'Sat', 'status' => 'false'));
        $wpdb->insert($tableDates, array('id' => '7', 'date' => 'Sun', 'status' => 'false'));
    }

    if (!$times) {
        for ($i=0; $i <= 23; $i++) { 
            $wpdb->insert($tableTimes, array('time' => $i . ':00'));
            $wpdb->insert($tableTimes, array('time' => $i . ':30'));
        }
    }
}
register_activation_hook(__FILE__, db_creating_tables_reservations() );
?>
