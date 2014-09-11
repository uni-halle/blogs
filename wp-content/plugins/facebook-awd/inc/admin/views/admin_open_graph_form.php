<?php
/**
 *
 * @author alexhermann
 *
 */
?>
<h1><?php _e('1. Define Object',$this->ptd); ?></h1>

<?php
$object = array(
	'id'=>'',
	'title'=>'%TITLE%',
	'description'=>'%DESCRIPTION%',
	'custom_type'=> isset($this->options['app_infos']['namespace']) ? $this->options['app_infos']['namespace'].':' : ''.'Your_custom_type',
	'type'=> 'article',
	'url'=>'%URL%',
	'site_name'=>'%BLOG_TITLE%',
	'auto_load_videos_attachment' => 0,
	'auto_load_images_attachment' => 0,
	'auto_load_audios_attachment' => 0
);
$form = new AWD_facebook_form('form_create_opengraph_object', 'POST', null, $this->plugin_option_pref);
if($object_id instanceof AWD_facebook_form){
	$form = $object_id;
}else if(is_array($object_id)){
	$object = $object_id;
}else if($object_id != ''){
	$object = wp_parse_args($this->options['opengraph_objects'][$object_id], $object);
}

if($copy == 'true')
	$object['id'] = '';
if(!isset($object['object_title']))
	$object['object_title'] = '';
	
$ogp = $this->opengraph_array_to_object($object);

if(!is_array($object_id))
	echo $form->start();
?>
<div class="alert alert-info">
	<p><?php _e('Template Values: you can use those values in each field of the form, select the input where you want to place a variable then click on the button of your choice.',$this->ptd); ?></p>
	<div class="btn-group opengraph_placeholder">
		<button class="btn btn-mini">%BLOG_TITLE%</button>
		<button class="btn btn-mini">%BLOG_DESCRIPTION%</button>
		<button class="btn btn-mini">%BLOG_URL%</button>
		<button class="btn btn-mini">%TITLE%</button>
		<button class="btn btn-mini">%DESCRIPTION%</button>
		<button class="btn btn-mini">%IMAGE%</button>
		<button class="btn btn-mini">%URL%</button>
	</div>
</div>
<div class="row">
	<?php
	//id of object
	echo $form->addInputHidden('awd_ogp[id]', $object['id']);
	//title of object
	echo $form->addInputText(__('Title of object (only for reference)',$this->ptd),  'awd_ogp[object_title]', $object['object_title'], 'span4', array('class'=>'span4'));
	//Locale
	$locales = $ogp->supported_locales();
	$_locales = array();
	foreach($locales as $locale => $label){ $_locales[] = array('value'=>$locale, 'label'=> $label ); }
	echo $form->addSelect(__('Locale',$this->ptd), 'awd_ogp[locale]', $_locales, $ogp->getLocale(), 'span4', array('class'=>'span2'));					
	?>
</div>
<div class="row">
	<?php
	//Determiners
	$_determiners = array(
		array('value'=> 'auto', 'label'=> __('Auto',$this->ptd)),
		array('value'=> 'a', 'label'=> __('A',$this->ptd)),
		array('value'=> 'an', 'label'=> __('An',$this->ptd)),
		array('value'=> 'the', 'label'=> __('The',$this->ptd))
	);
	echo $form->addSelect(__('The determiner',$this->ptd), 'awd_ogp[determiner]', $_determiners, $ogp->getDeterminer(), 'span2', array('class'=>'span2'));					

	//title of the page
	echo $form->addInputText(__('Title',$this->ptd),  'awd_ogp[title]', $ogp->getTitle(), 'span4', array('class'=>'span4'));
	?>
</div>
<div class="row">
	<?php
	//type
	$types = $ogp->supported_types(true);
	$types[] = 'custom';
	foreach($types as $type){ $options[] = array('value'=>$type, 'label'=> ucfirst($type)); }
	echo $form->addSelect(__('Type',$this->ptd).' '.$this->get_the_help('type'), 'awd_ogp[type]', $options, $object['type'], 'span2', array('class'=>'span2'));
	echo $form->addInputText(__('Custom object type',$this->ptd),  'awd_ogp[custom_type]', $object['custom_type'], 'span3 dn depend_opengraph_custom_type', array('class'=>'span3'));
	?>
</div>
<div class="row">
	<?php
	//Description
	echo $form->addInputText('Description',  'awd_ogp[description]', $ogp->getDescription(), 'span6', array('class'=>'span6'));
	?>
</div>
<div class="row">
	<?php
	//Site name
	echo $form->addInputText('Site Name', 'awd_ogp[site_name]', $ogp->getSiteName(), 'span4', array('class'=>'span4'));
	//Url
	echo $form->addInputText('Url',  'awd_ogp[url]', $ogp->getUrl(), 'span4', array('class'=>'span4'));
	?>
</div>
<h1><?php _e('2. Add Media to Object',$this->ptd); ?></h1>
<h2><?php _e('Images',$this->ptd); ?> <button class="btn btn-mini awd_add_media_field" data-label="<?php _e('Image url',$this->ptd); ?>" data-label2="<?php _e('Upload an Image',$this->ptd); ?>" data-type="image" data-name="awd_ogp[images][]"><i class="icon-picture"></i><?php _e('Add a custom image', $this->ptd); ?></button></h2>
<div class="row">
	<?php 
	echo $form->addSelect(__('Auto load images attachments ?',$this->ptd), 'awd_ogp[auto_load_images_attachment]', array(
		array('value'=>0, 'label'=>__('No',$this->ptd)),
		array('value'=>1, 'label'=>__('Yes',$this->ptd))									
	), $object['auto_load_images_attachment'], 'span3', array('class'=>'span2'));
	?>
</div>
<div class="row">
	<div class="awd_ogp_fields_image">
		<?php
		if(!isset($object['images']))
			$object['images'] = array();
		$images = $object['images'];
		if(count($images))
		{
			echo $form->addMediaButton('Image url', 'awd_ogp[images][]', $images[0],'span8', array('class'=>'span6'), array('data-title'=>__('Upload an Image',$this->ptd), 'data-type'=> 'image'), false);
			unset($images[0]);
			foreach($images as $image)
			{
				echo $form->addMediaButton('Image url', 'awd_ogp[images][]', $image,'span8', array('class'=>'span6'), array('data-title'=>__('Upload an Image',$this->ptd), 'data-type'=> 'image'), true);
			}
		}else{
			echo $form->addMediaButton('Image url', 'awd_ogp[images][]', '','span8', array('class'=>'span6'), array('data-title'=>__('Upload an Image',$this->ptd), 'data-type'=> 'image'), false);
		}
		?>
	</div>
</div>
<h2><?php _e('Videos',$this->ptd); ?> <button class="btn btn-mini awd_add_media_field" data-label="<?php _e('Video url',$this->ptd); ?>" data-label2="<?php _e('Upload an Image',$this->ptd); ?>" data-type="video" data-name="awd_ogp[videos][]"><i class="icon-film"></i> <?php _e('Add a custom video', $this->ptd); ?></button></h2>
<div class="row">
	<?php 
	echo $form->addSelect(__('Auto load videos attachments ?',$this->ptd), 'awd_ogp[auto_load_videos_attachment]', array(
		array('value'=>0, 'label'=>__('No',$this->ptd)),
		array('value'=>1, 'label'=>__('Yes',$this->ptd))									
	), $object['auto_load_videos_attachment'], 'span3', array('class'=>'span2'));
	?>
</div>
<div class="row">
	<div class="awd_ogp_fields_video">
		<?php
		if(!isset($object['videos']))
			$object['videos'] = array();
		$videos = $object['videos'];
		if(count($videos))
		{
			echo $form->addMediaButton('Video url', 'awd_ogp[videos][]', $videos[0],'span8', array('class'=>'span6'), array('data-title'=>__('Upload a Video',$this->ptd), 'data-type'=> 'video'), false);
			unset($videos[0]);
			foreach($videos as $video)
			{
				echo $form->addMediaButton('Video url', 'awd_ogp[videos][]', $video,'span8', array('class'=>'span6'), array('data-title'=>__('Upload a Video',$this->ptd), 'data-type'=> 'video'), true);
			}
		}else{
			echo $form->addMediaButton('Video url', 'awd_ogp[videos][]', '','span8', array('class'=>'span6'), array('data-title'=>__('Upload a Video',$this->ptd), 'data-type'=> 'video'), false);
		}
		?>		
	</div>
</div>
<h2><?php _e('Audios',$this->ptd); ?> <button class="btn btn-mini awd_add_media_field" data-label="<?php _e('Audio url',$this->ptd); ?>" data-label2="<?php _e('Upload an Image',$this->ptd); ?>" data-type="audio" data-name="awd_ogp[audios][]"><i class="icon-music"></i> <?php _e('Add a custom audio', $this->ptd); ?></button></h2>
<div class="row">
	<?php 
	echo $form->addSelect(__('Auto load audios attachments ?',$this->ptd), 'awd_ogp[auto_load_audios_attachment]', array(
		array('value'=>0, 'label'=>__('No',$this->ptd)),
		array('value'=>1, 'label'=>__('Yes',$this->ptd))									
	), $object['auto_load_audios_attachment'], 'span3', array('class'=>'span2'));
	?>
</div>
<div class="row">
	<div class="awd_ogp_fields_audio">
		<?php
		if(!isset($object['audios']))
			$object['audios'] = array();
		$audios = $object['audios'];
		if(count($audios))
		{
			echo $form->addMediaButton('Audio url', 'awd_ogp[audios][]', $audios[0],'span8', array('class'=>'span6'), array('data-title'=>__('Upload Audio',$this->ptd), 'data-type'=> 'audio'), false);
			unset($audios[0]);
			foreach($audios as $audio)
			{
				echo $form->addMediaButton('Audio url', 'awd_ogp[audios][]', $audio,'span8', array('class'=>'span6'), array('data-title'=>__('Upload Audio',$this->ptd), 'data-type'=> 'audio'), true);
			}
		}else{
			echo $form->addMediaButton('Audio url', 'awd_ogp[audios][]', '','span8', array('class'=>'span6'), array('data-title'=>__('Upload Audio',$this->ptd), 'data-type'=> 'audio'), false);
		}
		?>		
	</div>
</div>
<?php if(!is_array($object_id)){ ?>
	<div class="form-actions">
		<div class="btn-group pull-right">
			<button class="btn btn-primary awd_submit_ogp"><i class="icon-ok icon-white"></i> <?php _e('Save this object',$this->ptd); ?></button>
			<button class="btn btn-danger pull-right hide_ogp_form"><i class="icon-remove icon-white"></i> <?php _e('Cancel',$this->ptd); ?></button>
		</div>
	</div>
	<?php wp_nonce_field($this->plugin_slug.'_save_ogp_object',$this->plugin_option_pref.'_nonce_options_save_ogp_object'); ?>
	<?php 
	echo $form->end();
}