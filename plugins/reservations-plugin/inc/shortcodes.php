<?php

/**
 * Shortcode reservation form
 */

function reservations_shortcode($atts) {
    $calendarIcon = plugin_dir_url( __FILE__ ) . 'assets/img/calendar.png' ;
    $locationIcon = plugin_dir_url( __FILE__ ) . 'assets/img/location.png' ;
    $peopleIcon = plugin_dir_url( __FILE__ ) . 'assets/img/people.png' ;
    global $wpdb;
    $restaurantInfoTable = $wpdb->prefix. 'restaurant_options';
    $restaurantInfo = $wpdb->get_results( "SELECT * FROM $restaurantInfoTable", OBJECT);

	$Content .= "
        <div class='containerShortcode'>
            <div class='containerFormReservation' id='containerFormOne'>
                <div class='containerTitleReservation'>
                    <p class='titleReservation'>RESERVA ONLINE</p>
                </div>
                <div class='formReservationContainer'>
                    <form class='formReservation' id='formReservationOne' action='' method='POST'>
                        <div class='indicationsContainer'>
                            <p>" . $restaurantInfo[0]->name . "</p>
                        </div>
                        <div class='indications'>
                            <div class='indicationsDetails'>
                                <div class='indicationRead'>
                                    <div class='indicationColor indicationAvailable'></div>
                                    <p class='indicationLegend'>Disponible</p>
                                </div>
                                <div class='indicationRead'>
                                    <div class='indicationColor indicationClosed'></div>
                                    <p class='indicationLegend'>Cerrado</p>
                                </div>
                            </div>
                            <div class='indicationsDetails'>
                                <div class='indicationRead'>
                                    <div class='indicationColor indicationSelected'></div>
                                    <p class='indicationLegend'>Dia seleccionado</p>
                                </div>
                                <div class='indicationRead'>
                                    <div class='indicationColor indicationComplete'></div>
                                    <p class='indicationLegend'>Completo</p>
                                </div>
                            </div>
                        </div>
                        <div class='indicationStepContainer'>
                            <div class='indicationNumberContainer'>
                                <div class='indicationStep'>
                                    <p>1</p>
                                </div>
                                <div class='indicationStepUnifier'></div>
                                <div class='indicationStep indicationStepHidden'>
                                    <p>2</p>
                                </div>
                            </div>
                            <div class='indicationNumberContainer'>
                                <p class='indicationActive'>Encontrar</p>
                                <div class='indicationStepSeparator'></div>
                                <p class='indicationHidden'>Confirmar</p>
                            </div>
                        </div>
                        <div class='containerinputs'>
                            <select name='persons' id='persons' class='inputForm' required>
                                <option value='2' selected>2 Personas</option>
                                <option value='3'>3 Personas</option>
                                <option value='4'>4 Personas</option>
                                <option value='5'>5 Personas</option>
                            </select>
                        </div>
                        <div class='containerinputs'>
                            <input name='dateReserve' readonly class='inputForm' placeholder='DD/MM/YY' id='dateReserve' required />
                        </div>
                        <div class='containerinputs'>
                            <select name='zoneReserve' id='zoneReserve' disabled class='inputForm' required>
                                <option hidden seleted>Seleccione una zona</option>
                            </select>
                        </div>
                        <div class='containerinputs'>
                            <select name='timeReserve' id='timeReserve' class='inputForm' disabled required>
                                <option hidden seleted>Seleccione una hora</option>
                            </select>
                        </div>
                        <p class='reservationNext reservationNextHidden' id='continue'>Continuar</p>
                    </form>
                </div>
            </div>

            <div class='containerFormReservation hidden' id='containerFormTwo'>
                <div class='containerTitleReservation'>
                    <p class='titleReservation'>RESERVA ONLINE</p>
                </div>
                <form class='formReservation' actions='' id='formReservation' method='POST'>
                    <div class='indicationsContainer'>
                        <p>Restaurant</p>
                    </div>
                    <div class='indicationStepContainer'>
                        <div class='indicationNumberContainer'>
                            <div class='indicationStep indicationStepHidden'>
                                <p class=''i>1</p>
                            </div>
                            <div class='indicationStepUnifier'></div>
                            <div class='indicationStep'>
                                <p>2</p>
                            </div>
                        </div>
                        <div class='indicationNumberContainer'>
                            <p class='indicationHidden'>Encontrar</p>
                            <div class='indicationStepSeparator'></div>
                            <p class='indicationActive'>Confirmar</p>
                        </div>
                    </div>
                    <div class='reservationDetail'>
                        <div class='detailContents'>
                            <img class='iconImg' src=" . $calendarIcon . " alt='calendar' />
                            <p id='detailDate'></p>
                        </div>
                        <div class='detailContents'>
                            <img class='iconImg' src=" . $peopleIcon . " alt='people' />
                            <p id='detailPeople'></p>
                        </div>
                        <div class='detailContents'>
                            <img class='iconImg' src=" . $locationIcon .  " alt='location' />
                            <p id='detailLocation'>" . $restaurantInfo[0]->direction . "</p>
                        </div>
                    </div>
                    <div class='containerinputs'>
                        <p class='inputTag'>Nombre:</p>
                        <input name='name' id='name' placeholder='Ej: Juan Perez' class='inputForm' required />
                    </div>
                    <div class='containerinputs'>
                        <p class='inputTag'>Telefono:</p>
                        <div class='phoneComplete'>
                            <input type='phone_number' id='input-phone' class='inputForm' />
                        </div>
                    </div>
                    <div class='containerinputs'>
                        <p class='inputTag'>Email:</p>
                        <input name='email' id='email' type='email' placeholder='Ej: juanperez@gmail.com' class='inputForm' required />
                    </div>

                    <div class='termsConditions'>
                        <label class='termsConditionsContents'><input type='checkbox' id='cbox1' value='first_checkbox' required> <a href='' target='_blank'>Acepto las condiciones de uso, política de privacidad y aviso legal</a></label>
                        <label class='termsConditionsContents'><input type='checkbox' id='cbox2' value='second_checkbox' required> <a href='' target='_blank'> Consiento el tratamiento de datos personales</a></label>
                        <label class='termsConditionsContents'><input type='checkbox' id='cbox3' value='third_checkbox' required> Consiento la recepción de comunicaciones del restaurante por e-mail y/o SMS con fines comerciales</label>
                    </div>

                    <div class='buttons'>
                        <p class='reservationNext goBack' id='return'>
                            Atras
                        </p>
                        <button type='submit' id='btn_submit' disabled class='reserveButton'>Reservar</button>
                    </div>
                </form>
            </div>

            <div class='container-info' id='container-info' >
            
            </div>
        </div>
";

    return $Content;
}

add_shortcode('reservations', 'reservations_shortcode');

function information_shortcode($atts){
    $Content .= '

    ';

    return $Content;
}

add_shortcode('restaurant_information', 'information_shortcode');
