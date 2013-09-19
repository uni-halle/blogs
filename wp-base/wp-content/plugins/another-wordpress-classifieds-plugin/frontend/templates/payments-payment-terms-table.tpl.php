<?php $columns = $this->get_columns(); $group = ''; ?>

<table class="awpcp-payment-terms-table awpcp-table">
    <thead>
        <tr>
        <?php foreach ($columns as $column => $name): ?>
            <th class="<?php echo $column ?>"><?php echo $name ?></th>
        <?php endforeach ?>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($this->get_items() as $item): ?>

        <?php if (($_group = $this->item_group($item)) != $group): ?>
        <tr class="awpcp-group-header">
            <th colspan="<?php echo count($columns) ?>" scope="row"><?php echo $this->item_group_name($item) ?></th>
        </tr>
        <?php endif ?>

        <tr <?php echo $this->item_attributes($item) ?>>
            <?php foreach ($columns as $column => $name): ?>

                <?php if ($column === 'price' && $item->requires_payment()): ?>
            <td colspan="2"><?php echo $this->item_column($item, 'price'); ?></td>
                <?php elseif ($column === 'credits' && $item->requires_payment()): ?>
            <!-- -->
                <?php else: ?>
            <td><?php echo $this->item_column($item, $column); ?></td>
                <?php endif ?>

            <?php endforeach ?>
        </tr>

        <?php $group = $_group ?>

    <?php endforeach ?>
    </tbody>
</table>
