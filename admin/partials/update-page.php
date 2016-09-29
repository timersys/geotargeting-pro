<h2>GeoTarget Pro - db update</h2>
<?php
if( ! file_exists( WP_CONTENT_DIR . '/uploads/geotargeting/GeoLite2-City.mmdb') )
    echo '<p>
    '.__('Downloading Maxmind database, please wait....','geot').'
    </p>';
 ?>
<script type="text/javascript">
    (function($){

        geot_ajax();
        var geot_updater = null;
        function geot_ajax(){
            $.ajax({
                method : 'POST',
                url: ajaxurl,
                data: {
                    action : 'geot_updater',
                    object: 'mmdb'
                }
            }).done(function(reponse) {
                    clearTimeOut(geot_updater);
            }).error(function(reponse) {
                    clearTimeOut(geot_updater);
            });
            geot_updater = setTimeOut( geot_ajax, 5000);
        }
    })(jQuery)
</script>
