<?php

function lang($word)
{
    static $words = array(
        // navbar
        'home'         => 'Home',
        'categories'   => 'Categories',
        'items'        => 'Items',
        'members'      => 'Members',
        'statistics'   => 'Statistics',
        'logs'         => 'Logs',
        'comment'      => 'Comments'

    );

return $words[$word];

}


?>