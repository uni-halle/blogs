<div id="sidebar">

	<?php 
		global $user_identity, $user_ID, $user_email, $user_login, $current_user;
		get_currentuserinfo();
		
		if ( is_user_logged_in() ) { ?>
				
		<ul id="admin-box">
            <li id="hello"><h2>
            	<?php 
			 		echo get_avatar( $user_email, $size = '20' );
				 ?>
				<?php
					$greetings = array(
				    'Hallo',
				    'Hello',
				    'Schalom',
				    'Bonjour',
				    'Ciao',
				    'Salaam',
				    'Hala',
				    'Ni hao',
				    'Szia',
				    'Ahoy',
				    'Hi',
				    'Mingalaba',
				    'Merhaba',
				    );
					$number = count($greetings)-1;
					$randNumber = rand(0, $number);
					echo $greetings[$randNumber];
				?>
            	<?php echo $current_user->user_firstname; ?>
            	</h2>
            </li>
            
            <!-- For all logged in users -->
            <li class="dashboard"><a href="<?php echo get_option('home'); ?>/wp-admin/">Dashboard</a></li>
            <li class="profile"><a href="<?php echo get_option('home'); ?>/wp-admin/profile.php">Dein Profil</a></li>
            
            <!-- Just for Editors and above -->
            <?php if ( current_user_can('level_5') ) : ?>
            
            	<li class="articles overview"><a href="<?php echo get_option('home'); ?>/wp-admin/edit.php">Artikel &Uuml;bersicht</a></li>
            
            <?php endif ?>
            
            <!-- Just for Admins -->
            <?php if (current_user_can('level_10')) : ?>
            
            	<li class="sidebar-widgets"><a href="<?php echo get_option('home'); ?>/wp-admin/widgets.php">Sidebar Widgets</a></li>
            	<li class="new-user"><a href="<?php echo get_option('home'); ?>/wp-admin/users.php?page=wpmu_ldap_adduser.functions.php">Neuer Benutzer</a></li>
            
            <?php endif	?>
            
            <li class="logout"><a href="<?php echo wp_logout_url( $_SERVER['REQUEST_URI'] ); ?>">Logout</a></li>
        </ul>
	
	<?php }	?>

	<ul>
		<?php 
		if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) {
			echo prologue_widget_recent_comments_avatar(array('before_widget' => ' <li id="recent-comments" class="widget widget_recent_comments"> ', 'after_widget' => '</li>', 'before_title' =>'<h2>', 'after_title' => '</h2>'  ));
		
			$before = '<li><h2>'.__('Recent Tags', 'p2')."</h2>\n";
			$after = "</li>\n";
			$num_to_show = 35;
			echo prologue_recent_projects( $num_to_show, $before, $after );
		} // if dynamic_sidebar
		?>
	</ul>
	
<div style="clear: both;"></div>
</div> <!-- // sidebar -->