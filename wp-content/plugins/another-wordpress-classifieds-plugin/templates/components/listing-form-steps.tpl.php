<div class="awpcp-form-steps">
    <ul>
    <?php foreach ( $form_steps as $step ):
        ?><li class="awpcp-form-steps-step awpcp-form-steps-<?php echo esc_attr( $step['class'] ); ?>-step">
            <a href="" title="<?php echo esc_attr( $step['name'] ); ?>">
                <span class="awpcp-form-steps-step-number"><?php echo esc_html( $step['number'] ); ?></span>
                <span class="awpcp-form-steps-step-name"><?php echo esc_html( $step['name'] ); ?></span>
            </a>
        </li><?php
          endforeach; ?>
    </ul>
</div>
