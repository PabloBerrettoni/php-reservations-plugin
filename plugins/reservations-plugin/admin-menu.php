<?php
/** Step 2 (from text above). */
function admin_menu_info() {
    add_menu_page(
        __('Restaurant Information', 'admin-menu'),
        __('Restaurant Information', 'admin-menu'),
        'manage_options',
        'info-settings-page',
        'info_settings_template_callback',
        '',
        null
    );
}
add_action('admin_menu', 'admin_menu_info');

// template
function info_settings_template_callback() {
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
</style>
<?php
    if(!$_GET['info_id']){
    ?>
        <div class="wrap">
            <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
            <form action="" method="post">
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row">Titulo de la seccion</th>
                            <td>
                                <input name="title" type="text" class="regular-text" value="" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Cuerpo de la seccion</th>
                            <td>
                                <textarea name="body" type="text" class="regular-text" value="" required></textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php submit_button('Guardar') ?>
            </form>
        </div>
        <div class="accordion" id="accordionExample">
    <?php
        global $wpdb;
        $tableRestaurantInfo = $wpdb->prefix.'restaurant_info';
        $infos = $wpdb->get_results( "SELECT * FROM $tableRestaurantInfo", OBJECT); 
        
        foreach ($infos as $key => $info) {?>
            
            <div class="accordion-item">
            <h2 class="accordion-header" id="heading<?php echo $info->id ?>">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $info->id ?>" aria-expanded="true" aria-controls="collapse<?php echo $info->id ?>">
                    <?php echo($info->title); ?>
                </button>
                </h2>
                <div id="collapse<?php echo $info->id ?>" style="position: relative;" class="accordion-collapse collapse <?php echo($key == 0 ? 'show' : '') ?>" aria-labelledby="heading<?php echo $info->id ?>" data-bs-parent="#accordionExample">
                    <div style="background: #f3f3f3;" class="accordion-body">
                    <strong></strong> <p><?php echo nl2br($info->body);  ?></p>
                    <button style="position: absolute; bottom: 0; right: 0;border: none;box-shadow: 0 3px 6px #00000070;border-radius: 5px;">
                        <a style="color: black; text-decoration: none;" href="?page=info-settings-page&info_id=<?php echo $info->id ?>">editar</a>
                    </button>
                    <form onSubmit=" return confirm('¿Esta seguro que desea eliminar a este mensaje?')" method="POST" class="options">
                        <input value="<?php echo( $info->id); ?>" style="display: none;" name="delete">                    
                        <button type="submit" style="position: absolute; top: 0; right: 0;border: none;box-shadow: 0 3px 6px #00000070;border-radius: 5px;font-size: 14px;">
                            Eliminar
                        </button>
                    </form>  
                    </div>
                </div>
            </div>
    <?php
        }
        if($_POST['title']){
            $title = $wpdb->escape($_POST['title']);
            $body = $wpdb->escape($_POST['body']);

            $data = array(
                'title' => $title,   
                'body'  => $body
            );

            $wpdb->insert($tableRestaurantInfo, $data);

            $id = $wpdb->insert_id;

            echo '<div class="accordion-item">
                <h2 class="accordion-header" id="heading' . $id . '">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse' . $id . '" aria-expanded="true" aria-controls="collapse' . $id . '">
                        ' . $title . '
                    </button>
                </h2>
                <div id="collapse' . $id .'" class="accordion-collapse collapse show" aria-labelledby="heading' . $id .'" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                    <strong></strong><p style="margin: 0;">' . nl2br($body) . '</p>
                </div>
                </div>
                </div>';
                
        }else if($_POST['delete']){
            $id = $wpdb->escape($_POST['delete']);

            $wpdb->delete($tableRestaurantInfo, array( 'id' => $id ));

            echo "<meta http-equiv='refresh' content='0'>";
        }
    ?>
        </div>
    <?php
    }else {
        $infoID = $_GET['info_id'];
        global $wpdb;
        $tableRestaurantInfo = $wpdb->prefix.'restaurant_info';
        $infos = $wpdb->get_results( "SELECT * FROM $tableRestaurantInfo WHERE id = $infoID");

        if(sizeof($infos) > 0){
            foreach ($infos as $info) {?>
                <div class="wrap">
                    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
                    <form action="" method="post">
                        <table class="form-table" role="presentation">
                            <tbody>
                                <tr>
                                    <th scope="row">Titulo de la seccion</th>
                                    <td>
                                        <input name="title" type="text" class="regular-text" value="<?php echo $info->title; ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Cuerpo de la seccion</th>
                                    <td>
                                        <textarea name="body" type="text" class="regular-text" value="" required><?php echo $info->body; ?></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php submit_button('Guardar') ?>
                    </form>
                </div>
            <?php
            }
            if($_POST['title']){
                $title = $wpdb->escape($_POST['title']);
                $body = $wpdb->escape($_POST['body']);
                $format = array('%s','%d');
    
                $data = array(
                    'title' => $title,   
                    'body'  => $body
                );
    
                $wpdb->update($tableRestaurantInfo, $data,array( 'id' => $infoID));

                echo('<meta http-equiv="refresh" content="0;url=?page=info-settings-page">');
            }
        }
    }   
}
?>
