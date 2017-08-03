<?php
/*
Plugin Name: RCI Snapshot
Description: Basic Plugin to check your wordpress installation, and let you know where your wordpress installation needs action for installing our theme.
Version:     1.0
Author:      Utkarsh Aggarwal
*/

/*
 * Add menu in sidebar on dashboard
 */
add_action('admin_menu', 'rci_snapshot_menu');

function rci_snapshot_menu()
{	
	add_menu_page('RCI Snapshot','RCI Snapshot','manage_options','rci-snapshot','rci_snapshot_check_options');
    wp_enqueue_style('rci-snapshot-stylesheet', plugins_url( "css/style.css", __FILE__ ) );
}

function rci_snapshot_check_options(){
	echo "<h1>RCI Snapshot</h1>";
	echo "<form name='rci_snapshot_run_form' method='POST'>";
	echo submit_button("Run");
	echo "</form>";
	rci_snapshot_run();
}

/*
 * Base function to run all options if run is clicked 
 */
function rci_snapshot_run(){	
	if(isset($_POST['submit']))
	{
		echo "<div id='result'>";
			echo "<div class='section_1'>";
				echo "<h3>General Information About Your Installation</h3>";
				echo "<div class='general_info'>";
				rci_snapshot_general_info();
				echo "</div>";
			echo "</div>";
			echo "<div class='section_2'>";
				echo "<h3>Check Other Information</h3>";
				echo "<div class='other_info'>";
					echo "<div class='row'>";
						echo "<div class='row_data header'>Configuration</div>";
						echo "<div class='row_data header'>Suggestion</div>";
						echo "<div class='row_data header'>Value</div>";
						echo "<div class='row_data header'>Status</div>";
					echo "</div>";
					rci_snapshot_check_wp_version();
					rci_snapshot_php_version();
					rci_snapshot_mysql();
				echo "</div>";
			echo "</div>";
		echo "</div>";
	}
}

/*
 * Function to present all general information about worpress installtion
 */
function rci_snapshot_general_info()
{	
	echo "<div class='row'><span class='title'>Site Title</span><span class='content'>".get_bloginfo('name')."</span></div>";	
	echo "<div class='row'><span class='title'>Site Description</span><span class='content'>".get_bloginfo('description')."</span></div>";	
	echo "<div class='row'><span class='title'>Wordpress URL</span><span class='content'>".get_bloginfo('wpurl')."</span></div>";	
	echo "<div class='row'><span class='title'>Admin Email</span><span class='content'>".get_bloginfo('admin_email')."</span></div>";	
	echo "<div class='row'><span class='title'>Language</span><span class='content'>".get_bloginfo('language')."</span></div>";	
	echo "<div class='row'><span class='title'>Server Software</span><span class='content'>".$_SERVER['SERVER_SOFTWARE']."</span></div>";	
}

/*
 * Function to check wordpress version
 */
function rci_snapshot_check_wp_version()
{
	global $wp_version;
	$base_version = '4.0';
	$core_update = get_core_updates();
	if(isset($core_update[0]->response) && $core_update[0]->response == 'latest')
	{
		$status = "<div class='row_data ideal'>Ideal</div>";
	}
	else
	{
		$status = version_compare($wp_version,$base_version) < 0 ? "<div class='row_data error'>Problem</div>" : "<div class='row_data upgrade'>Upgrade</div>";			
	}
	echo "<div class='row'>";
		echo "<div class='row_data'>Wordpress Version</div>";
		echo "<div class='row_data'>Should be >= $base_version</div>";
		echo "<div class='row_data'>".$wp_version."</div>";
		echo $status;
	echo "</div>";
}

/*
 * Function to check PHP version
 */
function rci_snapshot_php_version()
{
	$base_version = '5.4';
	$status = version_compare(PHP_VERSION,$base_version) < 0 ? "<div class='row_data error'>Problem</div>" : "<div class='row_data ideal'>Ideal</div>";
	echo "<div class='row'>";
		echo "<div class='row_data'>PHP Version</div>";
		echo "<div class='row_data'>Should be >= $base_version</div>";
		echo "<div class='row_data'>".PHP_VERSION."</div>";
		echo $status;
	echo "</div>";	
}

/*
 * Function to checkk MYSQL version
 */
function rci_snapshot_mysql(){
    global $wpdb;
    $version = explode('.', $wpdb->db_version());
    $base_version = '5.6';
	if($wpdb->db_version >= "5.7")
	{
		$status = "<div class='row_data ideal'>Ideal</div>";
	}
	else
	{
		$status = version_compare($wpdb->db_version(),$base_version) < 0 ? "<div class='row_data error'>Problem</div>" : "<div class='row_data upgrade'>Upgrade to 5.7</div>";			
	}
	echo "<div class='row'>";
		echo "<div class='row_data'>Mysql Version</div>";
		echo "<div class='row_data'>Should be >= $base_version</div>";
		echo "<div class='row_data'>".$wpdb->db_version()."</div>";
		echo $status;
	echo "</div>";	
}

?>
