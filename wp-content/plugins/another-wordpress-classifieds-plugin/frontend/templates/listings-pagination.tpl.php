<div class="pager">
    <form class="awpcp-pagination-form" method="get">
        <?php echo awpcp_html_hidden_fields( $params ); ?>
        <table>
            <tbody>
                <tr>
                    <td><?php echo join('&nbsp;', $items); ?></td>
                    <?php if ( count( $options ) > 1 ): ?>
                    <td>
                        <select name="results">
                        <?php foreach ($options as $option): ?>
                            <?php if ($results == $option): ?>
                            <option value="<?php echo esc_attr( $option ); ?>" selected="selected"><?php echo esc_html( $option ); ?></option>
                            <?php else: ?>
                            <option value="<?php echo esc_attr( $option ); ?>"><?php echo esc_html( $option ); ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        </select>
                    </td>
                    <?php endif; ?>
                </tr>
            </tbody>
        </table>
    </form>
</div>
