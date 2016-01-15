<?php 
/*
 * This file contains the HTML generated for full calendars. You can copy this file to yourthemefolder/plugins/events-manager/templates and modify it in an upgrade-safe manner.
 * 
 * There are two variables made available to you: 
 * 
 * 	$calendar - contains an array of information regarding the calendar and is used to generate the content
 *  $args - the arguments passed to EM_Calendar::output()
 * 
 * Note that leaving the class names for the previous/next links will keep the AJAX navigation working.
 */
$cal_count = count($calendar['cells']); //to prevent an extra tr
$col_count = $tot_count = 1; //this counts collumns in the $calendar_array['cells'] array
$col_max = count($calendar['row_headers']); //each time this collumn number is reached, we create a new collumn, the number of cells should divide evenly by the number of row_headers
?>

<div class="calendar">
    <div class="row header">
        <div class="small-12 columns">
            <h1 class="page-title">
                <span><?php echo ucfirst( date_i18n( get_option( 'dbem_full_calendar_month_format' ), $calendar['month_start'] ) ); ?></span>
            </h1>
            <ul class="inline-list month-navigation">
                <li>
                    <a href="<?php echo $calendar['links']['previous_url']; ?>">&lt; prev</a>
                </li>
                <li>
                    <a href="<?php echo $calendar['links']['next_url']; ?>">next &gt;</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row month">
        <div class="small-12 medium-8 columns">
            <table class="current">
                <tbody>
                    <tr class="day-names">
                        <th><?php echo implode('</th><th>',$calendar['row_headers']); ?></th>
                    </tr>
                    <tr>
                        <?php
                        foreach ($calendar['cells'] as $date => $cell_data ):
                            $class = ( !empty($cell_data['events']) && count($cell_data['events']) > 0 ) ? 'eventful':'eventless';
                            if (!empty($cell_data['type'])) {
                                $class .= "-".$cell_data['type']; 
                            }
                            //In some cases (particularly when long events are set to show here) long events and all day events are not shown in the right order. In these cases, 
                            //if you want to sort events cronologically on each day, including all day events at top and long events within the right times, add define('EM_CALENDAR_SORTTIME', true); to your wp-config.php file 
                            if( defined('EM_CALENDAR_SORTTIME') && EM_CALENDAR_SORTTIME ) ksort($cell_data['events']); //indexes are timestamps
                            ?>
                            <td class="<?php echo $class; ?>">
                                <?php if( !empty($cell_data['events']) && count($cell_data['events']) > 0 ): ?>
                                <!--<a href="<?php echo esc_url($cell_data['link']); ?>" title="<?php echo esc_attr($cell_data['link_title']); ?>"><?php echo date('j',$cell_data['date']); ?></a>-->
                                <a href="#" class="day-with-events" data-date="<?= $date ?>"><?php echo date('j',$cell_data['date']); ?></a>
                                <ul class="no-bullet  mcas-date-options">
                                    <?php echo EM_Events::output($cell_data['events'],array('format'=>get_option('dbem_full_calendar_event_format'))); ?>
                                    <?php if( $args['limit'] && $cell_data['events_count'] > $args['limit'] && get_option('dbem_display_calendar_events_limit_msg') != '' ): ?>
                                    <li><a href="<?php echo esc_url($cell_data['link']); ?>"><?php echo get_option('dbem_display_calendar_events_limit_msg'); ?></a></li>
                                    <?php endif; ?>
                                </ul>
                                <?php else:?>
                                <?php echo date('j',$cell_data['date']); ?>
                                <?php endif; ?>
                            </td>
                            <?php
                            //create a new row once we reach the end of a table collumn
                            $col_count= ($col_count == $col_max ) ? 1 : $col_count+1;
                            echo ($col_count == 1 && $tot_count < $cal_count) ? '</tr><tr>':'';
                            $tot_count ++; 
                        endforeach;
                        ?>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="small-12 medium-4 columns" id="event-display">
            <?php
            ini_set('display_errors', true);
            $fmt = new IntlDateFormatter('en-EN', null, null, null, null, 'EEEE, d. MMMM y');
            ?>
            <?php
            $dateElements = array();
            foreach ($calendar['cells'] as $date => $data): ?>
                <?php if (count($data['events'])): ?>
                    <?php $dateElements[] = 'date_' . $date; ?>
                    <div id="date_<?= $date ?>" class="event preview" itemscope itemtype="http://schema.org/Event">
                        <?php
                        /** @var EM_Event $event */
                        foreach ($data['events'] as $event): ?>
                            <?php
                            $startDate = DateTime::createFromFormat(
                                'Y-m-d H:i:s',
                                $event->event_start_date . ' ' . $event->event_start_time
                            );
                            $endDate = DateTime::createFromFormat(
                                'Y-m-d H:i:s',
                                $event->event_end_date . ' ' . $event->event_end_time
                            );
                            ?>
                            <p class="meta">
                                <?php
                                /** @var EM_Category $category */
                                $category = $event->get_categories()->get_first();
                                echo $category->name;
                                ?>
                            </p>
                            <h2 class="title">
                                <a href="<?= $event->get_permalink() ?>" itemprop="url">
                                    <span itemprop="name"><?= $event->event_name ?></span>
                                </a>
                                <span class="consultant">
                                    <?= $event->get_event_meta()['consultant_name'][0] ?>
                                </span>
                            </h2>
                            <div class="content">
                                <h3><?= _e('Event Information', 'muhlenbergcenter') ?></h3>
                                <ul>
                                    <li>
                                        <span itemprop="startDate" content="<?= $startDate->format('c') ?>">
                                            <?= $fmt->format($startDate) ?>
                                        </span>
                                    </li>
                                    <li>
                                        <?= $startDate->format('h:i a') ?> – <?= $endDate->format('h:i a') ?>
                                    </li>
                                    <li>
                                        <?= $event->location->location_address ?><br>
                                        <?= $event->location->location_postcode ?>
                                        <?= $event->location->location_town ?>
                                    </li>
                                </ul>
                            </div>
                        <?php endforeach ?>
                    </div>
                <?php endif ?>
            <?php endforeach ?>
        </div>
    </div>
</div>
<script>
    var dateElements = <?= json_encode($dateElements); ?>;
</script>
