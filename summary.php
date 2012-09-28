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
<?php
	$data = wp_get_summary_data($type);
?>
<script type="text/javascript">
  function drawVisualization() {
  <?php 
	switch($_REQUEST['type']){
		case 'cn': ?>
			// Create and populate the data table.			
			var data = google.visualization.arrayToDataTable(<?php echo json_encode($data['data']);?>);
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
<input type="hidden" id="filter_type" value="static">
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
                            	<p>US$ <?php echo format_custom_number($data['total_budget']); ?></p>
                            <h4>Total Number of projects</h4>
                            	<p><?php echo $data['total_projects']; ?></p>
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
                        <li><a href="<?php echo get_option('home')."/?s=&sector=11420"; ?>">Higher education</a></li>

                  </ul>
                    <ul>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=11430"; ?>">Advanced technical and managerial training</a></li>

                    </ul>        
                    <ul>
                    	
                        <li><a href="<?php echo get_option('home')."/?s=&sector=43030"; ?>">Urban development and management</a></li>
 
                        
                  </ul>
                  
                    <ul>
                    	
                        <li><a href="<?php echo get_option('home')."/?s=&sector=13010"; ?>">Population policy and administrative management</a></li>
 
                        
                  </ul>
                  
                    <ul>
                    	
                        <li><a href="<?php echo get_option('home')."/?s=&sector=16030"; ?>">Housing policy and administrative management,</a></li>
 
                        
                  </ul>
                  
                    <ul>
                    	
                        <li><a href="<?php echo get_option('home')."/?s=&sector=15163"; ?>">Free flow of information</a></li>
 
                        
                  </ul>
                  
                    <ul>
                    	
                        <li><a href="<?php echo get_option('home')."/?s=&sector=11330"; ?>">Vocational training</a></li>
 
                        
                  </ul>
                              <div class="clr"></div>
                              
                              
                              <!--
                        <h3><span>SUBJECTS</span></h3>
                	<ul>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Administrative costs</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Advanced technical and managerial training</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Agrarian reform</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Agricultural alternative development</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Agricultural co-operatives</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Agricultural development</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Agricultural education/training</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Agricultural extension</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Agricultural financial services</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Agricultural inputs</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Agricultural land resources</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Agricultural policy and administrative management</a></li>
                  </ul>
                    <ul>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Energy research</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Environmental education/ training</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Environmental policy and administrative management</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Environmental research</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Family planning</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Financial policy and administrative management</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Fishery development</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Flood prevention/control</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Food aid/Food security programmes</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Food crop production</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Forestry development</a></li>
                    </ul>        
                    <ul>
                    	
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Personnel development for population and reproductive health</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Population policy and administrative management</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Post-conflict peace-building (UN)</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Power generation/renewable sources</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Primary education</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Privatisation</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Promotion of development awareness</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Public finance management</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Public sector financial management</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Radio/television/print media</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&sector=XX"; ?>">Reconstruction relief and rehabilitation</a></li>
                        
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
                        <li><a href="<?php echo get_option('home')."/?s=&countries=KH"; ?>">Cambodia</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&countries=ID"; ?>">Indonesia</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&countries=KR"; ?>">Korea (Democratic Republic)</a></li>
                           
                    </ul>        
                    <ul>
                        <li><a href="<?php echo get_option('home')."/?s=&countries=MN"; ?>">Mongolia</a></li>
                         <li><a href="<?php echo get_option('home')."/?s=&countries=NP"; ?>">Nepal</a></li> 
                         <li><a href="<?php echo get_option('home')."/?s=&countries=PH"; ?>">Phillipines</a></li>
                        </ul>
                    
                    <ul>
                        <li><a href="<?php echo get_option('home')."/?s=&countries=LK"; ?>">Sri Lanka</a></li>     
               
                    </ul>
                    
                              <div class="clr"></div>
                       <h3><span>AFRICA</span></h3>
                    <ul>
                	<li><a href="<?php echo get_option('home')."/?s=&countries=BF"; ?>">Burkina Faso</a></li>
                	<li><a href="<?php echo get_option('home')."/?s=&countries=KE"; ?>">Kenya</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&countries=MZ"; ?>">Mozambique</a></li>
                     </ul>
                     <ul> 
                        <li><a href="<?php echo get_option('home')."/?s=&countries=BF"; ?>">Namibia</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&countries=NG"; ?>">Nigeria</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=&countries=RW"; ?>">Rwanda</a></li>
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