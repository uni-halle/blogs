<div class="pager">
    <form class="awpcp-pagination-form" method="get">
        <?php foreach ($params as $name => $value): ?>
        <input type="hidden" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value) ?>" />
        <?php endforeach; ?>
        <table>
            <tbody>
                <tr>
                    <td><?php echo join('&nbsp;', $items); ?></td>
                    <td>
                        <select name="results">
                        <?php $options = array_merge(array(5), range(10, 100, 10)); ?>
                        <?php foreach ($options as $option): ?>
                            <?php if ($results == $option): ?>
                            <option value="<?php echo $option; ?>" selected="selected"><?php echo $option; ?></option>
                            <?php else: ?>
                            <option value="<?php echo $option; ?>"><?php echo $option; ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
