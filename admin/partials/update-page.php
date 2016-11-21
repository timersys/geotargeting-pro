<h2>GeoTarget Pro - db update</h2>
<style>
		.meter {
			height: 20px;  /* Can be anything */
			position: relative;
			margin: 0px 0 20px 0; /* Just for demo spacing */
			background: #555;
			-moz-border-radius: 25px;
			-webkit-border-radius: 25px;
			border-radius: 25px;
			padding: 10px;
			-webkit-box-shadow: inset 0 -1px 1px rgba(255,255,255,0.3);
			-moz-box-shadow   : inset 0 -1px 1px rgba(255,255,255,0.3);
			box-shadow        : inset 0 -1px 1px rgba(255,255,255,0.3);
		}
		.meter > span {
			display: block;
			height: 100%;
			   -webkit-border-top-right-radius: 8px;
			-webkit-border-bottom-right-radius: 8px;
			       -moz-border-radius-topright: 8px;
			    -moz-border-radius-bottomright: 8px;
			           border-top-right-radius: 8px;
			        border-bottom-right-radius: 8px;
			    -webkit-border-top-left-radius: 20px;
			 -webkit-border-bottom-left-radius: 20px;
			        -moz-border-radius-topleft: 20px;
			     -moz-border-radius-bottomleft: 20px;
			            border-top-left-radius: 20px;
			         border-bottom-left-radius: 20px;
			background-color: rgb(43,194,83);
			background-image: -webkit-gradient(
			  linear,
			  left bottom,
			  left top,
			  color-stop(0, rgb(43,194,83)),
			  color-stop(1, rgb(84,240,84))
			 );
			background-image: -moz-linear-gradient(
			  center bottom,
			  rgb(43,194,83) 37%,
			  rgb(84,240,84) 69%
			 );
			-webkit-box-shadow:
			  inset 0 2px 9px  rgba(255,255,255,0.3),
			  inset 0 -2px 6px rgba(0,0,0,0.4);
			-moz-box-shadow:
			  inset 0 2px 9px  rgba(255,255,255,0.3),
			  inset 0 -2px 6px rgba(0,0,0,0.4);
			box-shadow:
			  inset 0 2px 9px  rgba(255,255,255,0.3),
			  inset 0 -2px 6px rgba(0,0,0,0.4);
			position: relative;
			overflow: hidden;
		}
		.meter > span:after, .animate > span > span {
			content: "";
			position: absolute;
			top: 0; left: 0; bottom: 0; right: 0;
			background-image:
			   -webkit-gradient(linear, 0 0, 100% 100%,
			      color-stop(.25, rgba(255, 255, 255, .2)),
			      color-stop(.25, transparent), color-stop(.5, transparent),
			      color-stop(.5, rgba(255, 255, 255, .2)),
			      color-stop(.75, rgba(255, 255, 255, .2)),
			      color-stop(.75, transparent), to(transparent)
			   );
			background-image:
				-moz-linear-gradient(
				  -45deg,
			      rgba(255, 255, 255, .2) 25%,
			      transparent 25%,
			      transparent 50%,
			      rgba(255, 255, 255, .2) 50%,
			      rgba(255, 255, 255, .2) 75%,
			      transparent 75%,
			      transparent
			   );
			z-index: 1;
			-webkit-background-size: 50px 50px;
			-moz-background-size: 50px 50px;
			-webkit-animation: move 2s linear infinite;
			   -webkit-border-top-right-radius: 8px;
			-webkit-border-bottom-right-radius: 8px;
			       -moz-border-radius-topright: 8px;
			    -moz-border-radius-bottomright: 8px;
			           border-top-right-radius: 8px;
			        border-bottom-right-radius: 8px;
			    -webkit-border-top-left-radius: 20px;
			 -webkit-border-bottom-left-radius: 20px;
			        -moz-border-radius-topleft: 20px;
			     -moz-border-radius-bottomleft: 20px;
			            border-top-left-radius: 20px;
			         border-bottom-left-radius: 20px;
			overflow: hidden;
		}

		.animate > span:after {
			display: none;
		}

		@-webkit-keyframes move {
		    0% {
		       background-position: 0 0;
		    }
		    100% {
		       background-position: 50px 50px;
		    }
		}

		.orange > span {
			background-color: #f1a165;
			background-image: -moz-linear-gradient(top, #f1a165, #f36d0a);
			background-image: -webkit-gradient(linear,left top,left bottom,color-stop(0, #f1a165),color-stop(1, #f36d0a));
			background-image: -webkit-linear-gradient(#f1a165, #f36d0a);
		}

		.red > span {
			background-color: #f0a3a3;
			background-image: -moz-linear-gradient(top, #f0a3a3, #f42323);
			background-image: -webkit-gradient(linear,left top,left bottom,color-stop(0, #f0a3a3),color-stop(1, #f42323));
			background-image: -webkit-linear-gradient(#f0a3a3, #f42323);
		}

		.nostripes > span > span, .nostripes > span:after {
			-webkit-animation: none;
			background-image: none;
		}
	</style>
<div class="geot_updater">
	<p>
		<?php _e('Downloading Maxmind database, please wait....','geot');?>
	</p>
	<div class="meter" style="width: 320px;">
		<span style="width: 1%"></span>
	</div>
</div>
<script type="text/javascript">
    (function($){

		<?php if( isset($_GET['safe_mmdb']) ) : ?>
			geot_update_mmdb(true);
		<?php elseif( isset($_GET['csv_only']) ) : ?>
			geot_update_csv();
		<?php else: ?>
        	geot_update_mmdb();
			geot_progress_check();
		<?php endif;?>


        var geot_progress = null,
            progress_url  = '<?php echo content_url('uploads/geot_plugin/progress.json');?>';

        function geot_update_mmdb( backup_mode ){
			var opts = {
                method : 'POST',
                url: ajaxurl,
                data: {
                    action : 'geot_updater',
                    object: 'mmdb'
                },
				dataType: 'json'
			};
			if( backup_mode )
				opts.data.object = 'safe_mmdb';

            $.ajax(opts).done(function(response) {
                if( response.error ){
                    $('.meter').replaceWith(response.error);
					clearTimeout(geot_progress);
					$('.geot_updater').append('<p><?php _e("Downloading Mmdb in safe mode, please wait....","geot");?></p><div class="meter" style="width: 320px;"><span style="width: 1%"></span></div>');
					geot_update_mmdb(true);
					geot_progress_check();
				}
				if( response.success) {
					$('.meter span').animate({
						width: '100%'
					}, 500);
                    clearTimeout(geot_progress);
					$('.meter').replaceWith('Database Updated');
					geot_update_csv();
				}
            }).error(function(response) {
                if( response.error ) {
                    $('.meter').replaceWith(response.error);
					setTimeout(function(){clearTimeout(geot_progress)},1000);
				}
            }).fail(function(response){
				var msg = (response.responseText || 'Something failed, please upload database manually');
				$('.meter').replaceWith(msg);
				$('.geot_updater').append('<p><?php _e("Downloading Mmdb in safe mode, please wait....","geot");?></p><div class="meter" style="width: 320px;"><span style="width: 1%"></span></div>');
				geot_update_mmdb(true);
				geot_progress_check();
			});
        }
		function geot_update_csv(){
			$('.geot_updater').append('<p><?php _e("Downloading Cities csv database, please wait....","geot");?></p><div class="meter" style="width: 320px;"><span style="width: 1%"></span></div>');
			geot_progress_check();
			var opts = {
				method : 'POST',
				url: ajaxurl,
				data: {
					action : 'geot_updater',
					object: 'csv'
				},
				dataType: 'json'
			};
			<?php if( isset($_GET['csv_install_only']) ) : ?>
				opts.data.install_only = true;
			<?php endif;?>
			$.ajax(opts).done(function(response) {
				if( response.error ){
					$('.meter').replaceWith(response.error);
					setTimeout(function(){clearTimeout(geot_progress)},1000);
				}
				if( response.success) {
					$('.meter span').animate({
						width: '100%'
					}, 500);
					clearTimeout(geot_progress);
					$('.meter').replaceWith('Cities csv database Updated');
				}
				if( response.refresh ) {
					location.replace("<?php echo admin_url('admin.php?page=geot-settings');?>");
				}
			}).error(function(response) {
				if( response.error ) {
					$('.meter').replaceWith(response.error);
					setTimeout(function(){clearTimeout(geot_progress)},1000);
				}
			}).fail(function(response){
				var msg = (response.responseText || 'Something failed, please upload database manually');
				$('.meter').replaceWith(msg);
			});
		}
        function geot_progress_check(){
            $.ajax({
                method : 'GET',
                url: progress_url,
				dataType: 'json',
				cache: false
            }).done(function(response) {
                if( response.progress )
                    $('.meter span').animate({
						width: response.progress + '%'
					}, 500);
            }).error(function(response) {
				clearTimeout(geot_progress);
            });
            geot_progress = setTimeout( geot_progress_check, 3000);
        }
    })(jQuery)
</script>
