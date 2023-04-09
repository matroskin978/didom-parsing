<?php

$img = 'https://cdn11.bigcommerce.com/s-ww1fqjacln/images/stencil/500x659/products/6654/12588/apiqqzj4r__30011.1646844573.jpg?c=1';

//$data = explode('.', $img);
//$data = explode('?', end($data));
//var_dump($data[0]);

function get_ext($file_name)
{
    $data = explode('.', $file_name);
    $data = explode('?', end($data));
    return $data[0];
}

echo get_ext($img);
