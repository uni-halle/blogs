<div class="wrap">
	<h2><?php _e( 'The Events Calendar Shortcode' ); ?></h2>

	<p>The shortcode displays lists of your events. For example to shortcode to show next 8 events in the category festival in ASC order with date showing:</p>

	<pre>[ecs-list-events cat='festival' limit='8']</pre>



	<table>
		<tbody>
		<tr valign="top">
			<td valign="top">

				<div>
					<h2>Basic shortcode</h2>
						<blockquote>[ecs-list-events]</blockquote>

					<h2>Shortcode Options</h2>
					<?php do_action( 'ecs_admin_page_options_before' ); ?>

					<h3>cat</h3>
					<p>Represents single event category.  Use commas when you want multiple categories
						<blockquote>[ecs-list-events cat='festival']</blockquote>
						<blockquote>[ecs-list-events cat='festival, workshops']</blockquote>

					<?php do_action( 'ecs_admin_page_options_after_cat' ); ?>

					<h3>limit</h3>
					<p>Total number of events to show. Default is 5.</p>
						<blockquote>[ecs-list-events limit='3']</blockquote>
					<h3>order</h3>
					<p>Order of the events to be shown. Value can be 'ASC' or 'DESC'. Default is 'ASC'. Order is based on event date.</p>
						<blockquote>[ecs-list-events order='DESC']</blockquote>
					<h3>date</h3>
					<p>To show or hide date. Value can be 'true' or 'false'. Default is true.</p>
						<blockquote>[ecs-list-events eventdetails='false']</blockquote>
					<h3>venue</h3>
					<p>To show or hide the venue. Value can be 'true' or 'false'. Default is false.</p>
						<blockquote>[ecs-list-events venue='true']</blockquote>
					<h3>excerpt</h3>
					<p>To show or hide the excerpt and set excerpt length. Default is false.<p>
						<blockquote>[ecs-list-events excerpt='true']</blockquote>
						<blockquote>[ecs-list-events excerpt='300']</blockquote>
					<h3>thumb</h3>
					<p>To show or hide thumbnail/featured image. Default is false.</p>
						<blockquote>[ecs-list-events thumb='true']</blockquote>
					<p>You can use 2 other attributes: thumbwidth and thumbheight to customize the thumbnail size</p>
						<blockquote>[ecs-list-events thumb='true' thumbwidth='150' thumbheight='150']</blockquote>
					<h3>message</h3>
					<p>Message to show when there are no events. Defaults to 'There are no upcoming events at this time.'</p>
					<h3>viewall</h3>
					<p>Determines whether to show 'View all events' or not. Values can be 'true' or 'false'. Default to 'true'</p>
						<blockquote>[ecs-list-events cat='festival' limit='3' order='DESC' viewall='false']</blockquote>
					<h3>contentorder</h3>
					<p>Manage the order of content with commas. Default to title, thumbnail, excerpt, date, venue.</p>
						<blockquote>[ecs-list-events cat='festival' limit='3' order='DESC' viewall='false' contentorder='title, thumbnail, excerpt, date, venue']</blockquote>
					<h3>month</h3>
					<p>Show only specific Month. Type 'current' for displaying current month only, ie:</p>
						<blockquote>[ecs-list-events cat='festival' month='2015-06']</blockquote>
					<h3>past</h3>
					<p>Show outdated events (ie. events that have already happened)</p>
						<blockquote>[ecs-list-events cat='festival' past='yes']</blockquote>
					<h3>key</h3>
					<p>Order with start date</p>
						<blockquote>[ecs-list-events cat='festival' key='start date']</blockquote>

					<?php do_action( 'ecs_admin_page_options_after' ); ?>

				</div>

			</td>
			<td valign="top" class="styling">
				<h3>Styling/Design</h3>

				<?php do_action( 'ecs_admin_page_styling_before' ); ?>

				<?php if ( apply_filters( 'ecs_show_upgrades', true ) ): ?>

					<p>By default the plugin does not include styling. Events are listed in ul li tags with appropriate classes for styling and you can add your own CSS:</p>

					<ul>
						<li>ul class="ecs-event-list"</li>
						<li>li class="ecs-event"</li>
						<li>event title link is H4 class="entry-title summary"</li>
						<li>date class is time</li>
						<li>venue class is venue</li>
						<li>span .ecs-all-events</li>
						<li>p .ecs-excerpt</li>
					</ul>

					<p><em>Want a better looking design without adding any CSS?  Check out <a target="_blank" href="https://eventcalendarnewsletter.com/the-events-calendar-shortcode?utm_source=plugin&utm_medium=link&utm_campaign=tecs-help-design&utm_content=description">The Events Calendar Shortcode PRO</a></em></p>
					<p><a href="https://eventcalendarnewsletter.com/the-events-calendar-shortcode?utm_source=plugin&utm_medium=link&utm_campaign=tecs-help-design-image-1&utm_content=description"><img style="width: 200px;" src="<?php echo plugins_url( '/static/the-events-calendar-shortcode-pro-design.png', TECS_CORE_PLUGIN_FILE ) ?>"><br>Pro version default design</a></p>
					<p><a href="https://eventcalendarnewsletter.com/the-events-calendar-shortcode?utm_source=plugin&utm_medium=link&utm_campaign=tecs-help-design-image-2&utm_content=description"><img style="width: 200px;" src="<?php echo plugins_url( '/static/event-calendar-shortcode-compact-design.png', TECS_CORE_PLUGIN_FILE ) ?>"><br>Pro version compact design</a></p>

				<?php endif; ?>
			</td>
		</tr>
		</tbody>
	</table>

	<p><small>This plugin is not developed by or affiliated with The Events Calendar or Modern Tribe in any way.</small></p>
</div>