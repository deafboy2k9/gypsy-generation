<?php
//
// prints html share links (the basic ones)
//
function the_share_links(){
    $output = new ShareAndFollow();

    $output->the_share_links();

}
//
// Shows the links that are clicked in the admin screen.
//
function my_share_links($args =  array() ){
    $defaults = array('echo' => '0',
                       'id'=> 'self',
                       'title'=> 'self',
        );
    $args = wp_parse_args( $args, $defaults );
    $output = new ShareAndFollow();
    $output->my_share_links($args);
}


// links made for wp ecommerce. not that it is a good shop to be honest, with such poor support and upgrades.
function my_wp_ecommerce_share_links(){
    $output = new ShareAndFollow();

    $output->my_wp_ecommerce_share_links();
}


function my_follow_links(){
    $output = new ShareAndFollow();

    $output->my_follow_links();
}
//
//  shows interactive links needs function arguments
//
function show_interactive_links($args = array()){
    $output = new ShareAndFollow();

    $output->show_interactive_links($args);
}

//
// returns html share links as HTML
//

function get_the_share_links(){
    $output = new ShareAndFollow();

    $output->get_the_share_links();
}
//
//show social links function, complete function.
//
function social_links($args){
    $output = new ShareAndFollow();

    $output->social_links($args);
}
//
// the follow links setup
//

function follow_links($args){
    $output = new ShareAndFollow();

    $output->follow_links($args);
}
?>
