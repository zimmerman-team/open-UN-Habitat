<?php
/*
Template Name: summary
*/
?>
<?php get_header(); ?>
<?php
 
$details=$_REQUEST['details'];
if(isset($details)){
	$details=$_REQUEST['details'];
}
else{
	$details=array("all");
}
$type=$_REQUEST['type'];
?>
<script type="text/javascript">
  google.load('visualization', '1', {packages: ['corechart']});
</script>
<script type="text/javascript">
  function drawVisualization() {
  <?php 
	switch($_REQUEST['type']){
		case 'cn': ?>
			// Create and populate the data table.
			var data = google.visualization.arrayToDataTable([
			  ['Region', 'Funding Percentage'],
			  ['East asia and pacific', 1100],
			  ['Eastern africa and indian ocean', 2000],
			  ['Latin america and Caribbean', 2000],
			  ['Others', 700]
			]);
		<?php
		break;
		default:
		?>
			// Create and populate the data table.
			var data = google.visualization.arrayToDataTable([
			  ['Sector', 'Funding Percentage'],
			  ['Urban development and management', 46],
			  ['Higher education', 19],
			  ['Advanced managerial and technical training', 15],
			  ['Others', 20]
			]);
		<?php 
		break;
		} ?>
	// Create and draw the visualization.
	new google.visualization.PieChart(document.getElementById('visualization')).
		draw(data, {title:"",tooltip:{text:"percentage"},colors:['#42604f','#f68a39','#008499','#ee343f'],chartArea:{left:0,top:10,width:"80%",height:"80%"}});
  }
  google.setOnLoadCallback(drawVisualization);
</script>

<!--START BODY-->
<div id="opunh-mainbody">
<!--COL-LEFT-->
		<div class="colLeft sm">
			<div class="filter" id="filterContainer">
				<div class="filterbox">
					<h4>By Country</h4>
					<div class="filtercontent">
						<?php echo wp_generate_filter_html('country'); ?>
					</div>
				</div>
				<!--COUNTRIES-->
				<?php echo  wp_generate_filter_popup('country'); ?>
				<!--END COUNTRIES-->
				<div class="filterbox">
					<h4>By Region</h4>
					<div class="filtercontent">
						<?php echo wp_generate_filter_html('region'); ?>
					</div>
				</div>
				<!--REGIONS-->
				<?php echo  wp_generate_filter_popup('region'); ?>
				<div class="filterbox">
					<h4>By Sector</h4>
					<div class="filtercontent">
						<?php echo wp_generate_filter_html('sector'); ?>
					</div>
				</div>
				<!--SECTORS-->
				<?php echo  wp_generate_filter_popup('sector'); ?>
				<div class="filterbox">
					<h4>By Budget</h4>
					<div class="filtercontent">
						<?php echo wp_generate_filter_html('budget'); ?>
					</div>
				</div>
			</div>
		</div>
	  <!--COL-RIGHT-->
          <div class="colRight">
          	<div class="searchresultslist">
            	<!--SEARCH TITLE BAR-->
                    <div class="summarytitle">
                        <h4><?php 
							switch($type){
								case 'cn':
									echo "COUNTRY : ";
									break;
								default:
									echo "SUBJECT : ";
									break;
							}
						if(isset($keyval))
							echo $keyval;
						else
							echo "SUMMARY";
						?>
                        </h4>
                        <div class="summarydetails">
                        	<div class="summarychart">
                            <div id="visualization"></div>
                            </div>
                           <div class="summary">
                           	<h4>Total Budget</h4>
                            	<p>US$ 9,786,593</p>
                            <h4>Total Number of projects</h4>
                            	<p>6</p>
                           </div>
                           <div class="clr"></div>
                        </div>
                    </div>
                <!--END SEARCH TITLE BAR-->
                <?php if($type=="sc"){?>
                <!--SUBJECTS LIST-->
                <div class="summarywrap">
                <h2>All Sectors</h2>
                	<h3><span>SECTORS</span></h3>
                	<ul>
                        <li><a href="<?php echo get_option('home')."/?s=".str_replace(" ","+","Administrative costs"); ?>">Higher education</a></li>

                  </ul>
                    <ul>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Advanced technical and managerial training</a></li>

                    </ul>        
                    <ul>
                    	
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Urban development and management</a></li>
 
                        
                  </ul>
                  
                    <ul>
                    	
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Population policy and administrative management</a></li>
 
                        
                  </ul>
                  
                    <ul>
                    	
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Housing policy and administrative management,</a></li>
 
                        
                  </ul>
                  
                    <ul>
                    	
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Free flow of information</a></li>
 
                        
                  </ul>
                  
                    <ul>
                    	
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Vocational training</a></li>
 
                        
                  </ul>
                              <div class="clr"></div>
                              
                              
                              <!--
                        <h3><span>SUBJECTS</span></h3>
                	<ul>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Administrative costs</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Advanced technical and managerial training</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Agrarian reform</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Agricultural alternative development</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Agricultural co-operatives</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Agricultural development</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Agricultural education/training</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Agricultural extension</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Agricultural financial services</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Agricultural inputs</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Agricultural land resources</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Agricultural policy and administrative management</a></li>
                  </ul>
                    <ul>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Energy research</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Environmental education/ training</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Environmental policy and administrative management</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Environmental research</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Family planning</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Financial policy and administrative management</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Fishery development</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Flood prevention/control</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Food aid/Food security programmes</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Food crop production</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Forestry development</a></li>
                    </ul>        
                    <ul>
                    	
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Personnel development for population and reproductive health</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Population policy and administrative management</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Post-conflict peace-building (UN)</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Power generation/renewable sources</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Primary education</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Privatisation</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Promotion of development awareness</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Public finance management</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Public sector financial management</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Radio/television/print media</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=subject"; ?>">Reconstruction relief and rehabilitation</a></li>
                        
                  </ul>
                <div class="clr"></div> -->
                </div>
                <!--END SUBJECT LIST-->
                <?php } else{?>
                <!--COUNTRY LIST-->
                <div class="summarywrap">
                <h2>All Regions and Countries</h2>
                	<h3><span>ASIA</span></h3>
                	<ul>
                        <li><a href="<?php echo get_option('home')."/?s=&countries=KH; ?>">Cambodia</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&countries=ID"; ?>">Indonesia</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&countries=KR"; ?>">Korea (Democratic Republic)</a></li>
                           
                    </ul>        
                    <ul>
                        <li><a href="<?php echo get_option('home')."/?s=&countries=MN"; ?>">Mongolia</a></li>
                         <li><a href="<?php echo get_option('home')."/?s=&countries=NE"; ?>">Nepal</a></li> 
                         <li><a href="<?php echo get_option('home')."/?s=&countries=PH"; ?>">Phillipines</a></li>
                        </ul>
                    
                    <ul>
                        <li><a href="<?php echo get_option('home')."/?s=&countries=LK; ?>">Sri Lanka</a></li>     
                       
                    </ul>
                    
                              <div class="clr"></div>
                       <h3><span>AFRICA</span></h3>
                    <ul>
                	<li><a href="<?php echo get_option('home')."/?s=&countries=BF; ?>">Burkina Faso</a></li>
                	<li><a href="<?php echo get_option('home')."/?s=&countries=KE"; ?>">Kenya</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&countries=MZ"; ?>">Mozambique</a></li>
                     </ul>
                     <ul> 
                        <li><a href="<?php echo get_option('home')."/?s=&countries=NA"; ?>">Namibia</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&countries=NG"; ?>">Nigeria</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&countries=RW; ?>">Rwanda</a></li>
                     </ul>   
                     <ul>   
                        <li><a href="<?php echo get_option('home')."/?s=&countries=SN"; ?>">Senegal</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&countries=UG"; ?>">Uganda</a></li>
                    </ul>        
                   
                              <div class="clr"></div>
                        <h3><span>SOUTH AMERICA</span></h3>
                	<ul>
                        <li><a href="<?php echo get_option('home')."/?s=&countries=EC"; ?>">Ecuador</a></li>
                   
                    </ul>        
                  
                              <div class="clr"></div>
                       
                <div class="clr"></div>
                </div>
                <!--END COUNTRY LIST-->
                <?php }	 ?>
            </div>
          </div>
        <div class="clr"></div>
  	</div>
<!--END BODY-->
<?php get_footer(); ?>