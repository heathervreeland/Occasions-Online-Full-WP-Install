<?php

/* Template Name: Events */
include_once('guide-functions.php');
ao_set_title('Atlanta Bridal Shows, Atlanta Wedding Event, Atlanta Bridal Expos, Atlanta Wedding Trunk Shows');
get_header();
?>
			<div class="post">
				<h2>Atlanta Bridal Shows and Upcoming Events</h2>
				<p>Browse upcoming Bridal Shows and Atlanta Area events happening soon.</p>
<?php 
	// get the event list
	$e = new Pod('events');
	$sqlwhere = "( (approved = 1) AND (date_start >= '". date("Y-m-d") . "  00:00:00') ) ";
	$e->findRecords( 't.date_start ASC', -1, $sqlwhere);
	
	if( $e->getTotalRows() > 0 ) {
		while ( $e->fetchRecord() ) {
			// get our fields from the POD
			$ae = get_eventfields($e);
			
			$addr = "{$ae['addr']}{$ae['city']}, {$ae['stat']} {$ae['zipc']}";
			$nicedate = '<b>' . date("D M j, Y", strtotime( $ae['dsta'] )) . '</b>';
			if ( ($ae['dend'] != '0000-00-00 00:00:00') && ($ae['dsta'] != $ae['dend']) ) {
				$nicedate .= ' thru <b>' . date("D M j, Y", strtotime( $ae['dend'] )) . '</b>';
			}
			echo <<<HEREDOC
			<div id="event_{$ae['id']}" class="eventlist_wrap">
				<div class="eventlist_content">
					<h2 class="eventlist_name">{$ae["name"]}</h2>
					<p class="eventlist_desc">{$ae["desc"]}</p>
					<div class="eventlist_detail">
						<div class="eventlist_detailrow">
							<div class="eventlist_detaillabel">Date(s):</div>
							<div class="eventlist_detailtext">$nicedate</div>
						</div>
						<div class="eventlist_detailrow">
							<div class="eventlist_detaillabel">Address:</div>
							<div class="eventlist_detailtext">$addr</div>
						</div>
						<div class="eventlist_detailrow">
							<div class="eventlist_detaillabel">Hours:</div>
							<div class="eventlist_detailtext">{$ae["hour"]}</div>
						</div>
						<div class="eventlist_detailrow">
							<div class="eventlist_detaillabel">Location:</div>
							<div class="eventlist_detailtext">{$ae["loca"]}</div>
						</div>
						<div class="eventlist_detailrow">
							<div class="eventlist_detaillabel">Cost:</div>
							<div class="eventlist_detailtext">{$ae["cost"]}</div>
						</div>
						<div class="eventlist_detailrow">
							<div class="eventlist_detaillabel">More Info</div>
							<div class="eventlist_detailtext"><a href="{$ae["wurl"]}">{$ae["wurl"]}</a></div>
						</div>
					</div>
				</div>
			</div>
HEREDOC;
		}
	}

?>
				</div>
<?php get_footer(); ?>
