<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="UTF-8"/>
	<title>Dutch development aid</title>
	<script type="text/javascript" src="./js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="./js/bubbletree/jquery.history.js"></script>
	<script type="text/javascript" src="./js/bubbletree/jquery.tooltip.min.js"></script>
	<script type="text/javascript" src="./js/bubbletree/raphael.js"></script>
	<script type="text/javascript" src="./js/bubbletree/vis4.js"></script>
	<script type="text/javascript" src="./js/bubbletree/Tween.js"></script>
	<script type="text/javascript" src="./js/bubbletree.js"></script>

	<link rel="stylesheet" type="text/css" href="./css/bubbletree.css" />
	<script type="text/javascript">
       
		$(function() {		
			var config = {
				data: './vis_search.php',
				container: '.bubbletree',
				bubbleType: 'icon',
				maxNodesPerLevel:25
			};

			var $tooltip = $('<div class="tooltip">Tooltip</div>');
			$('.bubble-chart').append($tooltip);
			
			var tooltip = function(event) {
				if (event.type == 'SHOW') {
					// show tooltip
					$tooltip.css({ 
						left: event.mousePos.x + 4, 
						top: event.mousePos.y + 4 
					});
					$tooltip.html(event.node.label+' <b>'+event.node.famount+'</b>');
					$tooltip.show();
				} else {
					// hide tooltip
					$tooltip.hide();
				}
			};

		config['tooltipCallback']=tooltip

					new BubbleTree.Loader(config);
				});
			
			                  
	</script>
</head>
<body>
<div class="bubbletree-wrapper">
	<div class="bubbletree"></div>
</div>
</body>
</html>