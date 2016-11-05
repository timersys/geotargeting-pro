<h2>Ip Test page</h2>
<p>
    Check what Ip's yor server return
</p>
<p>
    <?php echo '$_SERVER[REMOTE_ADDR] = "'; echo isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : 'not resolved';?>
</p>
<p>
<?php
    echo 'cloudflare<br />';
    echo '$_SERVER[HTTP_CF_CONNECTING_IP] = '; echo isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : 'not resolved';
?>
</p>
<p>
<?php
    echo 'Reblaze<br />';
    echo '$_SERVER[X-Real-IP] = '; echo isset( $_SERVER['X-Real-IP'] ) ? $_SERVER['X-Real-IP'] : 'not resolved';
?>
</p>
<p>
<?php

echo 'Sucuri<br />';
echo '$_SERVER[HTTP_X_SUCURI_CLIENTIP] = '; echo isset( $_SERVER['HTTP_X_SUCURI_CLIENTIP'] ) ? $_SERVER['HTTP_X_SUCURI_CLIENTIP'] : 'not resolved';
?>
</p>
<p>
<?php
echo 'Ezoic<br />';
echo '$_SERVER[X-FORWARDED-FOR] = '; echo isset( $_SERVER['X-FORWARDED-FOR'] ) ? $_SERVER['X-FORWARDED-FOR'] : 'not resolved';
 ?>
</p>
<p>
<?php
    echo 'Akamai<br />';
    echo '$_SERVER[True-Client-IP] = '; echo isset( $_SERVER['True-Client-IP'] ) ? $_SERVER['True-Client-IP'] : 'not resolved';
?>
</p>
<p>
    <?php
    echo 'Clouways<br />';
    echo '$_SERVER[HTTP_X_FORWARDED_FOR] = '; echo isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : 'not resolved';

     ?>
</p>
