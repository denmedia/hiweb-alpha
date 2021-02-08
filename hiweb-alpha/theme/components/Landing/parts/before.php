<?php

$landingContext = isset($args) ? $args : null;
if ( !$landingContext instanceof \theme\Landing\Landing_Context) return;

?>
<div id="<?= the_landing()->get_wrap_id() ?>" class="<?= the_landing()->get_wrap_class() ?>">