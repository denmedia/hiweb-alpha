<?php
extract($args);
echo '<p>'.__('Print form widget','hiweb-core-4').'<br><code>[hiweb-theme-widget-form id="' . $post_id . '"]</code></p><p>'.__('Print button calling modal form','hiweb-core-4').'<br><code>[hiweb-theme-widget-form-button id="' . $post_id . '" html="'.__('Open contact form','hiweb-core-4').'"]</code></p>';