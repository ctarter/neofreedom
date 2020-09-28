<?php

if (!defined('ABSPATH')) exit;

function gdpol_integrate_poll_icon() {
    gdpol()->bbpress()->show_poll_icon();
}

function gdpol_integrate_poll_in_topic() {
    gdpol()->bbpress()->show_poll_in_topic();
}

function gdpol_integrate_form_in_topic() {
    gdpol()->bbpress()->show_form_in_topic();
}
