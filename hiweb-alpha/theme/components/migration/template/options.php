<h1>hiWeb Site Migration Simple</h1>
<div class="card pressthis">
    <h2><?php use theme\migration\includes\db;
        use theme\migration\includes\tools;


        _e('Site ready to mirgate...', 'hiweb-core-4') ?></h2>
    <ol>
        <li><?php _e('Download site & MySQL dump files via FTP or Client Panel by you\'r hosting.', 'hiweb-core-4') ?></li>
        <li><?php _e('Upload this files on you\'r new server.', 'hiweb-core-4') ?></li>
        <li><?php _e('Connect site to new MySQL server, change connect data in wp-config.php file', 'hiweb-core-4') ?></li>
        <li><?php _e('Go to new home page. Now you see message: "hiWeb Migrate Site Process". This message indicates the beginning of a process to automatically replace the old ways to the new database. Wait until the end, it should not take more than few easy
			seconds.', 'hiweb-core-4') ?>
        </li>
        <li><?php _e('Done. Enjoy ;)', 'hiweb-core-4') ?></li>
    </ol>
</div><br><br>

<h3><?php _e('Force Re-Migrate', 'hiweb-core-4') ?></h3>

<form action="<?php tools::the_request_url(); ?>" method="post">
    <p><?php _e('This option is useful if the domain has changed, but the hosting site and the folder is not changed', 'hiweb-core-4') ?></p>
    <p>
        <strong><?php _e('From Old Domain... (select one or more)', 'hiweb-core-4') ?></strong><br/>
        <select name="old_domain" multiple="multiple" size="8">
            <option value="<?php echo get_option('siteurl') ?>" selected><?php echo get_option('siteurl') . ' (' . __('current URL', 'hiweb-core-4') . ')' ?></option>
            <?php
            $urls = db::get_DB_urls();
            foreach ($urls as $url => $times) {
                ?>
                <option value="<?php echo $url ?>"><?php echo $url . ' (count: ' . $times . ')' ?></option>
                <?php
            }
            ?>
        </select>
    </p>
    <p>
        <strong><?php _e('To New Domain...','hiweb-core-4') ?></strong><br/>
        <input placeholder="<?php tools::the_base_url() ?>" name="new_domain" size="36"/>
        <button type="submit" class="button button-primary button-large"><?php _e('RE-MIGRATE to New Domain', 'hiweb-core-4') ?></button>
    </p>
    <div class="describe">
        <?php _e('Enter new domain (or stay them clear) and click this button to force a re-migrate procedure to new domain.<br>
		Don\'t forget about <b>http://</b> prefix.', 'hiweb-core-4') ?>
    </div>
</form>