<?php

function get_domain_name($url) {
    $pieces = @parse_url($url);
    return $domain = isset($pieces['host']) ? $pieces['host'] : $url;
    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        return $regs['domain'];
    }
    return false;
}

?>
