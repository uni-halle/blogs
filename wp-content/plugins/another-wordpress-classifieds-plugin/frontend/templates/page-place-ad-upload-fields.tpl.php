    <div class="clearfix">
    <?php for ( $i = 0; $i < $files_left; $i++ ): ?>
        <div class="uploadform">
            <input class="image-upload-field" type="file" name="AWPCPfileToUpload<?php echo $i; ?>" id="AWPCPfileToUpload<?php echo $i; ?>" size="18" />
            <ul class="upload-field-actions clearfix">
                <?php if (!$is_primary_set && $i == 0): ?>
                <li class="primary">
                    <input id="awpcp-image-upload-field-<?php echo $i; ?>" checked="checked" type="radio" name="primary-image" value="field-<?php echo $i; ?>" />
                <?php else: ?>
                <li class="not-primary">
                    <input id="awpcp-image-upload-field-<?php echo $i; ?>" type="radio" name="primary-image" value="field-<?php echo $i; ?>" />
                <?php endif ?>
                    <?php $label = _x( 'Use as Primary Image.', 'images upload step', 'AWPCP' ); ?>
                    <label for="awpcp-image-upload-field-<?php echo $i; ?>" title="<?php echo esc_attr( $label ); ?>"><span><?php echo esc_html( $label ); ?></span></label>
                </li>
            </ul>
        </div>
    <?php endfor; ?>
    </div><br/>
