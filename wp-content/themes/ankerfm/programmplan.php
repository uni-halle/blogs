<?php
/*
Template Name: Programmplan
*/
?>

<?php get_header(); ?>
       
    <div id="content" class="col-full">
		<div id="main" class="col-left">
            
            
			<script>    
			    $(document).ready(function() {
			        $("#scheduleTabs").airtimeWeekSchedule({
			            sourceDomain:"http://airtime.medienkomm.uni-halle.de",
			            dowText:{monday:"Montag", tuesday:"Dienstag", wednesday:"Mittwoch", thursday:"Donnerstag", friday:"Freitag", saturday:"Samstag", sunday:"Sonntag"},
			            miscText:{time:"Zeit", programName:"Programm", details:"Details", readMore:"Mehr"},
			            updatePeriod: 600 //seconds
				        });
				    });
			</script>
<div id="scheduleTabs"></div>
            
		</div><!-- /#main -->
		
		<?php get_sidebar(); ?>
		
    </div><!-- /#content -->
		
<?php get_footer(); ?>