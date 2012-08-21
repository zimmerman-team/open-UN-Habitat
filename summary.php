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
			  ['Agriculture', 1200],
			  ['Poverty', 135],
			  ['Sanitation', 120],
			  ['Others', 85]
			]);
		<?php 
		break;
		} ?>
	// Create and draw the visualization.
	new google.visualization.PieChart(document.getElementById('visualization')).
		draw(data, {title:"",colors:['#42604f','#f68a39','#008499','#ee343f'],chartArea:{left:0,top:10,width:"80%",height:"80%"}});
  }
  google.setOnLoadCallback(drawVisualization);
</script>

<!--START BODY-->
<div id="opunh-mainbody">
          <!--COL-LEFT-->
          <div class="colLeft sm">
          	<div class="filter">
            	<div class="filterbox">
                	<h4>By Country</h4>
                    <div class="filtercontent">
                        <ul>
                            <li>
                                <label for="id_countries_1"><input checked="checked" name="countries" value="GH" id="id_countries_1" type="checkbox">All</label>
                            </li>
                            <li>
                                <label for="id_countries_2"><input name="countries" value="GH" id="id_countries_1" type="checkbox">Afghanistan</label>
                            </li>
                            <li>
                                <label for="id_countries_3"><input name="countries" value="GH" id="id_countries_1" type="checkbox">Albania</label>
                            </li>
                            <li>
                                <label for="id_countries_4"><input name="countries" value="GH" id="id_countries_1" type="checkbox">Algeria</label>
                            </li>
                             <li>
                                <label for="id_countries_4"><input name="countries" value="GH" id="id_countries_1" type="checkbox">Angola</label>
                            </li>
                        </ul>
                    <a href="#">+ SEE ALL</a>                    </div>
                </div>
                <div class="filterbox">
                	<h4>By Region</h4>
                    <div class="filtercontent">
                        <ul>
                            <li>
                                <label for="id_countries_1"><input checked="checked" name="countries" value="GH" id="id_countries_1" type="checkbox">All</label>
                            </li>
                            <li>
                                <label for="id_countries_1"><input name="countries" value="GH" id="id_countries_1" type="checkbox">Caribbean</label>
                            </li>
                            <li>
                                <label for="id_countries_2"><input name="countries" value="GH" id="id_countries_1" type="checkbox">Central America</label>
                            </li>
                            <li>
                                <label for="id_countries_3"><input name="countries" value="GH" id="id_countries_1" type="checkbox">Central Asia</label>
                            </li>
                            <li>
                                <label for="id_countries_4"><input name="countries" value="GH" id="id_countries_1" type="checkbox">Eastern Africa</label>
                            </li>
                        </ul>
                    <a href="#">+ SEE ALL</a>                    </div>
                </div>
                <div class="filterbox">
                	<h4>By Subject</h4>
                    <div class="filtercontent">
                        <ul>
                            <li>
                                <label for="id_countries_1"><input checked="checked" name="countries" value="GH" id="id_countries_1" type="checkbox">All</label>
                            </li>
                            <li>
                                <label for="id_countries_2"><input name="countries" value="GH" id="id_countries_1" type="checkbox">Administrative costs</label>
                            </li>
                            <li>
                                <label for="id_countries_3"><input name="countries" value="GH" id="id_countries_1" type="checkbox">Advanced technical training</label>
                            </li>
                            <li>
                                <label for="id_countries_4"><input name="countries" value="GH" id="id_countries_1" type="checkbox">Agrarian reform</label>
                            </li>
                             <li>
                                <label for="id_countries_4"><input name="countries" value="GH" id="id_countries_1" type="checkbox">Agricultural alternative Dvpt</label>
                            </li>
                        </ul>
                    <a href="#">+ SEE ALL</a>                    </div>
                </div>
                <div class="filterbox">
                	<h4>By Sector</h4>
                    <div class="filtercontent">
                        <ul>
                            <li>
                                <label for="id_countries_1"><input checked="checked" name="countries" value="GH" id="id_countries_1" type="checkbox">All</label>
                            </li>
                            <li>
                                <label for="id_countries_2"><input name="countries" value="GH" id="id_countries_1" type="checkbox">Administrative costs</label>
                            </li>
                            <li>
                                <label for="id_countries_3"><input name="countries" value="GH" id="id_countries_1" type="checkbox">Advanced technical training</label>
                            </li>
                            <li>
                                <label for="id_countries_4"><input name="countries" value="GH" id="id_countries_1" type="checkbox">Agrarian reform</label>
                            </li>
                             <li>
                                <label for="id_countries_4"><input name="countries" value="GH" id="id_countries_1" type="checkbox">Agricultural alternative Dvpt</label>
                            </li>
                        </ul>
                    <a href="#">+ SEE ALL</a>                    </div>
                </div>
                <div class="filterbox">
                	<h4>By Budget</h4>
                    <div class="filtercontent">
                        <ul>
                            <li>
                                <label for="id_countries_1"><input name="countries" value="GH" id="id_countries_1" type="checkbox">&gt; &euro; 0</label>
                            </li>
                            <li>
                                <label for="id_countries_2"><input name="countries" value="GH" id="id_countries_1" type="checkbox">&gt; &euro; 10.000</label>
                            </li>
                            <li>
                                <label for="id_countries_2"><input name="countries" value="GH" id="id_countries_1" type="checkbox">&gt; &euro; 50.000</label>
                            </li>
                            <li>
                                <label for="id_countries_3"><input name="countries" value="GH" id="id_countries_1" type="checkbox">&gt; &euro; 100.000</label>
                            </li>
                            <li>
                                <label for="id_countries_4"><input name="countries" value="GH" id="id_countries_1" type="checkbox">&gt; &euro; 500.000</label>
                            </li>
                            <li>
                                <label for="id_countries_4"><input name="countries" value="GH" id="id_countries_1" type="checkbox">&gt; &euro; 1.000.000</label>
                            </li>
                        </ul>

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
                            	<p>&euro; 25,0000</p>
                            <h4>Total Number of projects</h4>
                            	<p>5</p>
                           </div>
                           <div class="clr"></div>
                        </div>
                    </div>
                <!--END SEARCH TITLE BAR-->
                <?php if($type=="sc"){?>
                <!--SUBJECTS LIST-->
                <div class="summarywrap">
                <h2>All Sectors and Subjects</h2>
                	<h3><span>SECTORS</span></h3>
                	<ul>
                        <li><a href="<?php echo get_option('home')."/?s=".str_replace(" ","+","Administrative costs"); ?>">Administrative costs</a></li>
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
                              <div class="clr"></div>
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
                <div class="clr"></div>
                </div>
                <!--END SUBJECT LIST-->
                <?php } else{?>
                <!--COUNTRY LIST-->
                <div class="summarywrap">
                <h2>All Regions and Countries</h2>
                	<h3><span>EAST ASIA AND PACIFIC</span></h3>
                	<ul>
                        <li><a href="<?php echo get_option('home')."/?s=".str_replace(" ","+","Cambodia"); ?>">Cambodia</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">China</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Fiji</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Indonesia</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Korea (Democratic Peoples Republic)</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Lao (Peoples Democratic Republic)</a></li>
                    </ul>        
                    <ul>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Malaysia</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Mongolia</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Multicountry East Asia And Pacific (APN+)</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Multicountry East Asia And Pacific (ISEAN-HIVOS)</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Multicountry Western Pacific</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Myanmar</a></li>
                        </ul>
                    <ul>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Papua New Guinea</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Philippines</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Solomon Islands</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Thailand</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Timor-Leste</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Viet Nam</a></li>
                              </ul>
                              <div class="clr"></div>
                       <h3><span>EASTERN AFRICA AND INDIAN OCEAN</span></h3>
                	<ul>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Cambodia</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">China</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Fiji</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Indonesia</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Korea (Democratic Peoples Republic)</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Lao (Peoples Democratic Republic)</a></li>
                    </ul>        
                    <ul>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Malaysia</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Mongolia</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Multicountry East Asia And Pacific (APN+)</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Multicountry East Asia And Pacific (ISEAN-HIVOS)</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Multicountry Western Pacific</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Myanmar</a></li>
                        </ul>
                    <ul>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Papua New Guinea</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Philippines</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Solomon Islands</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Thailand</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Timor-Leste</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Viet Nam</a></li>
                              </ul>
                              <div class="clr"></div>
                        <h3><span>EASTERN EUROPE AND CENTRAL ASIA</span></h3>
                	<ul>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Cambodia</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">China</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Fiji</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Indonesia</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Korea (Democratic Peoples Republic)</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Lao (Peoples Democratic Republic)</a></li>
                    </ul>        
                    <ul>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Malaysia</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Mongolia</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Multicountry East Asia And Pacific (APN+)</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Multicountry East Asia And Pacific (ISEAN-HIVOS)</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Multicountry Western Pacific</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Myanmar</a></li>
                        </ul>
                    <ul>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Papua New Guinea</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Philippines</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Solomon Islands</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Thailand</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Timor-Leste</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Viet Nam</a></li>
                              </ul>
                              <div class="clr"></div>
                        <h3><span>LATIN AMERICA AND CARIBBEAN</span></h3>
                	<ul>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Cambodia</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">China</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Fiji</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Indonesia</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Korea (Democratic Peoples Republic)</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Lao (Peoples Democratic Republic)</a></li>
                    </ul>        
                    <ul>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Malaysia</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Mongolia</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Multicountry East Asia And Pacific (APN+)</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Multicountry East Asia And Pacific (ISEAN-HIVOS)</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Multicountry Western Pacific</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Myanmar</a></li>
                        </ul>
                    <ul>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Papua New Guinea</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Philippines</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Solomon Islands</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Thailand</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Timor-Leste</a></li>
                        <li><a href="<?php echo get_option('home')."/?s=country"; ?>">Viet Nam</a></li>
                              </ul>
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