<?php
$popup = $this->currentMap['params']['map_display_mode'] == 'popup' ? true : false;

$viewId = $this->currentMap['view_id'];
$mapHtmlId = $this->currentMap['view_html_id'];
$mapPreviewClassname = @$this->currentMap['html_options']['classname'];
$mapOptsClassname = $popup ? 'display_as_popup' : '';
?>
<?php if($popup){ ?>
	<div class="map-preview-img-container">
		<img src="<?php echo GMP_IMG_PATH . 'gmap_preview.png'?>" class="show_map_icon map_num_<?php echo $this->currentMap['id']; ?>"
			 data-map_id="<?php echo $this->currentMap['id']; ?>" title = "Click to view map" alt="Map Widget" style="display: none;">
	</div>
	<div id="gmpWidgetMapPopupWnd">
<?php } ?>
		<div class="gmp_map_opts <?php echo $mapOptsClassname;?>" id="mapConElem_<?php echo $viewId;?>"
			data-id="<?php echo $this->currentMap['id']; ?>" data-view-id="<?php echo $viewId;?>"
			<?php if(!empty($this->mbsIntegrating)) {
				echo 'data-mbs-gme-map="' . $this->currentMap['id'] . '" style="display:none;"';
			} else if(!empty($this->mbsMapId) && !empty($this->mbsMapInfo)) {
				echo "data-mbs-gme-map-id='" . $this->mbsMapId . "' data-mbs-gme-map-info='" . $this->mbsMapInfo . "'";
			}
			?>
			>
			<div class="gmpMapDetailsContainer" id="gmpMapDetailsContainer_<?php echo $viewId ;?>">
				<i class="gmpKMLLayersPreloader fa fa-spinner fa-spin" aria-hidden="true" style="display: none;"></i>
				<div class="gmp_MapPreview <?php echo $mapPreviewClassname;?>" id="<?php echo $mapHtmlId ;?>"></div>
			</div>
			<div class="gmpMapProControlsCon" id="gmpMapProControlsCon_<?php echo $viewId;?>">
				<?php dispatcherGmp::doAction('addMapBottomControls', $this->currentMap); ?>
			</div>
			<div class="gmpMapProDirectionsCon" id="gmpMapProDirectionsCon_<?php echo $viewId;?>" >
				<?php dispatcherGmp::doAction('addMapDirectionsData', $this->currentMap); ?>
			</div>
			<div class="gmpMapProKmlFilterCon" id="gmpMapProKmlFilterCon_<?php echo $viewId;?>" >
				<?php dispatcherGmp::doAction('addMapKmlFilterData', $this->currentMap); ?>
			</div>
			<div class="gmpSocialSharingShell gmpSocialSharingShell_<?php echo $viewId ;?>">
				<?php echo $this->currentMap['params']['ss_html'];?>
			</div>
			<div style="clear: both;"></div>
		</div>
<?php if($popup){ ?>
	</div>
<?php } ?>