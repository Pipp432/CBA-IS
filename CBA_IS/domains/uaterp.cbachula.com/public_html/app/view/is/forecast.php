<!DOCTYPE html>
<html>

	<br>
    <h1 class="text-center blue mt-2"><i class="fas fa-chart-line"></i> จะทำได้ไหม ทำได้รึเปล่า
	<br>สู้ ๆ น้าทุกคนนนนน \^o^/</h1>
	<!--<h3 style ="color:red"><?php echo print_r($this->yesterday[1]['sales'])?></h3>-->
    <br>
	
<head>
<script src="https://use.fontawesome.com/releases/v5.15.2/js/all.js"></script>
</head>	
<body>
	
	<style>
		body {
  			background: url('/public/img/gradient.png') no-repeat center center fixed;
			-webkit-background-size: cover;
            -moz-background-size: cover;
            background-size: cover;
            -o-background-size: cover;
		}
		
		h1{
			font-size: 50px;
		}
		
	</style>
	
	<?php $totalCS = $this->actualCS[0]['sales'] + $this->actualCS[1]['sales'] + $this->actualCS[2]['sales'] + $this->actualCS[3]['sales'] + $this->actualCS[4]['sales'] + $this->actualCS[5]['sales'] + $this->actualCS[6]['sales']?>
	<?php $totalYesterdayCS = $this->yesterdayCS[0]['sales'] + $this->yesterdayCS[1]['sales'] + $this->yesterdayCS[2]['sales'] + $this->yesterdayCS[3]['sales'] + $this->yesterdayCS[4]['sales'] + $this->yesterdayCS[5]['sales'] + $this->yesterdayCS[6]['sales']?>
	
	<div class="container p-2">
	<!--<div class="row justify-content-md-center">-->
		<div class = 'row'>
		<!--<div class="row row-cols-3 mt-2 p-0">-->
			<div class="col-sm-4 p-0 "><div class="m-2 card">				
				<div class="card-body">
					<h5>CBA</h5>
					<h4>Achieved <?php echo number_format((($this->actual_cba+$totalCS)/$this->target[10]['target'])*100,2,".",","); ?>%</h4>
					<h5 style ="color:green"><?php echo number_format((($this->actual_cba - $this->yesterdayCBA)/$this->yesterdayCBA)*100,2,".",","); ?>% <i class = "fas fa-caret-up"></i></h5>
					<div class="progress">
						<div class="progress-bar" role="progressbar" style="width: <?php echo number_format((($this->actual_cba+$totalCS)/$this->target[10]['target'])*100,2,".",","); ?>%" aria-valuenow="<?php echo number_format(($this->actual_cba/$this->target[10]['target'])*100,2,".",",") ?>" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
					<h5><?php echo number_format($this->target[10]['target'] - ($this->actual_cba+$totalCS),2,".",","); ?> to go!</h5>
				</div>
			</div></div>
			
			<div class="col-sm-4 p-0"><div class="m-2 card">
				<div class="card-body">
					<h5>GM & SMD</h5>
					<h4>Achieved <?php echo number_format(($this->actualGM_SMD/$this->target[11]['target'])*100,2,".",",")?>%</h4>
					<h5 style ="color:green"><?php echo number_format((($this->actualGM_SMD-$this->yesterdayGM_SMD)/$this->yesterdayGM_SMD)*100,2,".",",")?>% <i class = "fas fa-caret-up" style="color:green"></i></h5>
					<div class="progress">
						<div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo number_format(($this->actualGM_SMD/$this->target[11]['target'])*100,2,".",",")?>%" aria-valuenow="<?php echo number_format(($this->actualGM_SMD/$this->target[11]['target'])*100,2,".",",")?>" aria-valuemin="0" aria-valuemax="100" 
						></div>
					</div>
					<h5><?php echo number_format($this->target[11]['target']-$this->actualGM_SMD,2,".",",")?> to go!</h5>
				</div>
			</div></div>
			
			<div class="col-sm-4 p-0"><div class="m-2 card">
				<div class="card-body">
					<h5>SPJ</h5>
					<h4>Achieved <?php echo number_format(((311962.62+$this->actual[10]['sales']+$this->actualCS[6]['sales'])/$this->target[12]['target'])*100,2,".",",") ?>%</h4>
					<h5 style ="color:green"><?php echo number_format((((311962.62+$this->actual[10]['sales']+$this->actualCS[6]['sales'])-(311962.62+$this->yesterday[10]['sales']+$this->yesterdayCS[6]['sales']))/(311962.62+$this->yesterday[10]['sales']+$this->yesterdayCS[6]['sales']))*100,2,".",",")?>% <i class = "fas fa-caret-up" style="color:green"></i></h5>
					<div class="progress">
						<div class="progress-bar" role="progressbar" style="width: <?php echo number_format(((311962.62+$this->actual[10]['sales']+$this->actualCS[6]['sales'])/$this->target[12]['target'])*100,2,".",",") ?>%" 
						aria-valuenow="<?php echo number_format(((311962.62+$this->actual[10]['sales']+$this->actualCS[6]['sales'])/$this->target[12]['target'])*100,2,".",",") ?>" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
					<h5><?php echo number_format($this->target[12]['target'] - (311962.62+$this->actual[10]['sales']+$this->actualCS[6]['sales']),2,".",",")?>  to go!</h5>
				</div>
			</div></div>
		</div>
		<div class="row row-cols-5 mt-2 p-0">
			<div class="col p-0"><div class="m-2 card">
				<div class="card-body">
					<h5>LINE 1</h5>
					<h3>Achieved <?php echo number_format(($this->actual[1]['sales']/$this->target[1]['target'])*100,2,".",",")?>%</h3>
					<h5 style ="color:green"><?php echo number_format((($this->actual[1]['sales']-$this->yesterday[1]['sales'])/$this->yesterday[1]['sales'])*100,2,".",",")?>% <i class = "fas fa-caret-up" style="color:green;"></i></h5>
					<div class="progress">
						<div class="progress-bar <?php echo number_format($this->target[1]['target']-$this->actual[1]['sales'],2,".",",") < 0 ? 'bg-success' : ''; ?>" role="progressbar" style="width: <?php echo number_format(($this->actual[1]['sales']/$this->target[1]['target'])*100,2,".",",") ?>%" aria-valuenow="<?php echo number_format(($this->actual[1]['sales']/$this->target[1]['target'])*100,2,".",",") ?>" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
					<h5><?php echo number_format($this->target[1]['target']-$this->actual[1]['sales'],2,".",",") ?> to go!</h5>
				</div>
			</div></div>
			
			<div class="col p-0"><div class="m-2 card">
				<div class="card-body">
					<h5>LINE 2</h5>
					<h3>Achieved <?php echo number_format(($this->actual[2]['sales']/$this->target[2]['target'])*100,2,".",",")?>%</h3>
					<h5 style ="color:green"><?php echo number_format((($this->actual[2]['sales']-$this->yesterday[2]['sales'])/$this->yesterday[2]['sales'])*100,2,".",",")?>% <i class = "fas fa-caret-up" style="color:green;"></i></h5>
					<div class="progress">
						<div class="progress-bar <?php echo number_format($this->target[2]['target']-$this->actual[2]['sales'],2,".",",") < 0 ? 'bg-success' : ''; ?>" role="progressbar" style="width: <?php echo number_format(($this->actual[2]['sales']/$this->target[2]['target'])*100,2,".",",") ?>%" aria-valuenow="<?php echo number_format(($this->actual[2]['sales']/$this->target[2]['target'])*100,2,".",",") ?>" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
					<h5><?php echo number_format($this->target[2]['target']-$this->actual[2]['sales'],2,".",",") ?> to go!</h5>
				</div>
			</div></div>
			
			<div class="col p-0"><div class="m-2 card">
				<div class="card-body">
					<h5>LINE 3</h5>
					<h3>Achieved <?php echo number_format(($this->actual[3]['sales']/$this->target[3]['target'])*100,2,".",",")?>%</h3>
					<h5 style ="color:green"><?php echo number_format((($this->actual[3]['sales']-$this->yesterday[3]['sales'])/$this->yesterday[3]['sales'])*100,2,".",",")?>% <i class = "fas fa-caret-up" style="color:green;"></i></h5>
					<div class="progress">
						<div class="progress-bar <?php echo number_format($this->target[3]['target']-$this->actual[3]['sales'],2,".",",") < 0 ? 'bg-success' : ''; ?>" role="progressbar" style="width: <?php echo number_format(($this->actual[3]['sales']/$this->target[3]['target'])*100,2,".",",") ?>%" aria-valuenow="<?php echo number_format(($this->actual[3]['sales']/$this->target[3]['target'])*100,2,".",",") ?>" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
					<h5><?php echo number_format($this->target[3]['target']-$this->actual[3]['sales'],2,".",",") ?> to go!</h5>
				</div>
			</div></div>
			
			<div class="col p-0"><div class="m-2 card">
				<div class="card-body">
					<h5>LINE 4</h5>
					<h3>Achieved <?php echo number_format((($this->actual[4]['sales']+ actualCS[1]['sales'])/$this->target[4]['target'])*100,2,".",",")?>%</h3>
					<h5 style ="color:green"><?php echo number_format((($this->actual[4]['sales']+ actualCS[1]['sales'])-($this->yesterday[4]['sales']+ $this->yesterdayCS[1]['sales']))/($this->yesterday[4]['sales']+ $this->yesterdayCS[1]['sales'])*100,2,".",",")?>% <i class = "fas fa-caret-up" style="color:green;"></i></h5>
					<div class="progress">
						<div class="progress-bar <?php echo number_format($this->target[4]['target']-$this->actual[4]['sales'],2,".",",") < 0 ? 'bg-success' : ''; ?>" role="progressbar" style="width: <?php echo number_format((($this->actual[4]['sales']+ actualCS[1]['sales'])/$this->target[4]['target'])*100,2,".",",") ?>%" aria-valuenow="<?php echo number_format((($this->actual[4]['sales']+ actualCS[1]['sales'])/$this->target[4]['target'])*100,2,".",",") ?>" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
					<h5><?php echo number_format($this->target[4]['target']-($this->actual[4]['sales']+ actualCS[1]['sales']),2,".",",") ?> to go!</h5>
				</div>
			</div></div>
			
			<div class="col p-0"><div class="m-2 card">
				<div class="card-body">
					<h5>LINE 5 ข้างนอกเฮฮา ข้างในฮึบ ๆ</h5>
					<h3>Achieved <?php echo number_format(($this->actual[5]['sales']/$this->target[5]['target'])*100,2,".",",")?>%</h3>
					<h5 style ="color:green"><?php echo number_format((($this->actual[5]['sales']-$this->yesterday[5]['sales'])/$this->yesterday[5]['sales'])*100,2,".",",")?>% <i class = "fas fa-caret-up" style="color:green;"></i></h5>
					<div class="progress">
						<div class="progress-bar <?php echo number_format($this->target[5]['target']-$this->actual[5]['sales'],2,".",",") < 0 ? 'bg-success' : ''; ?>" role="progressbar" style="width: <?php echo number_format(($this->actual[5]['sales']/$this->target[5]['target'])*100,2,".",",") ?>%" aria-valuenow="<?php echo number_format(($this->actual[5]['sales']/$this->target[5]['target'])*100,2,".",",") ?>" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
					<h5><?php echo number_format($this->target[5]['target']-$this->actual[5]['sales'],2,".",",") ?> to go!</h5>
				</div>
			</div></div>
			
			<div class="col p-0"><div class="m-2 card">
				<div class="card-body">
					<h5>LINE 6</h5>
					<h3>Achieved <?php echo number_format((($this->actual[6]['sales']+ $this->actualCS[2]['sales'])/$this->target[6]['target'])*100,2,".",",")?>%</h3>
					<h5 style ="color:green"><?php echo number_format(((($this->actual[6]['sales']+ $this->actualCS[2]['sales'])-($this->yesterday[6]['sales'])+ $this->yesterdayCS[2]['sales'])/($this->yesterday[6]['sales']+ $this->yesterdayCS[2]['sales']))*100,2,".",",")?>% <i class = "fas fa-caret-up" style="color:green;"></i></h5>
					<div class="progress">
						<div class="progress-bar bg-success" role="progressbar" style="width: <?php echo number_format(($this->actual[6]['sales']/$this->target[6]['target'])*100,2,".",",") ?>%" aria-valuenow="<?php echo number_format((($this->actual[6]['sales']+ $this->actualCS[2]['sales'])/$this->target[6]['target'])*100,2,".",",") ?>" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
					<h5><?php echo number_format($this->target[6]['target']-($this->actual[6]['sales']+ $this->actualCS[2]['sales']),2,".",",") ?> to go!</h5>
				</div>
			</div></div>
			
			<div class="col p-0"><div class="m-2 card">
				<div class="card-body">
					<h5>LINE 7</h5>
					<h3>Achieved <?php echo number_format((($this->actual[7]['sales']+ $this->actualCS[3]['sales'])/$this->target[7]['target'])*100,2,".",",")?>%</h3>
					<h5 style ="color:green"><?php echo number_format(((($this->actual[7]['sales']+ $this->actualCS[3]['sales'])-($this->yesterday[7]['sales']+ $this->yesterdayCS[3]['sales']))/($this->yesterday[7]['sales']+$this->yesterdayCS[3]['sales']))*100,2,".",",")?>% <i class = "fas fa-caret-up" style="color:green;"></i></h5>
					<div class="progress">
						<div class="progress-bar <?php echo number_format($this->target[7]['target']-$this->actual[7]['sales'],2,".",",") < 0 ? 'bg-success' : ''; ?>" role="progressbar" style="width: <?php echo number_format((($this->actual[7]['sales']+ $this->actualCS[3]['sales'])/$this->target[7]['target'])*100,2,".",",") ?>%" aria-valuenow="<?php echo number_format((($this->actual[7]['sales']+ $this->actualCS[3]['sales'])/$this->target[7]['target'])*100,2,".",",") ?>" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
					<h5><?php echo number_format($this->target[7]['target']-($this->actual[7]['sales']+ $this->actualCS[3]['sales']),2,".",",") ?> to go!</h5>
				</div>
			</div></div>
			
			<div class="col p-0"><div class="m-2 card">
				<div class="card-body">
					<h5>LINE 8</h5>
					<h3>Achieved <?php echo number_format((($this->actual[8]['sales']+ $this->actualCS[4]['sales'])/$this->target[8]['target'])*100,2,".",",")?>%</h3>
					<h5 style ="color:green"><?php echo number_format(((($this->actual[8]['sales']+ $this->actualCS[4]['sales'])-($this->yesterday[8]['sales']+$this->yesterdayCS[4]['sales']))/($this->yesterday[8]['sales']+$this->yesterdayCS[4]['sales']))*100,2,".",",")?>% <i class = "fas fa-caret-up" style="color:green;"></i></h5>
					<div class="progress">
						<div class="progress-bar <?php echo number_format($this->target[8]['target']-$this->actual[8]['sales'],2,".",",") < 0 ? 'bg-success' : ''; ?>" role="progressbar" style="width: <?php echo number_format((($this->actual[8]['sales']+ $this->actualCS[4]['sales'])/$this->target[8]['target'])*100,2,".",",") ?>%" aria-valuenow="<?php echo number_format((($this->actual[8]['sales']+ $this->actualCS[4]['sales'])/$this->target[8]['target'])*100,2,".",",") ?>" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
					<h5><?php echo number_format($this->target[8]['target']-($this->actual[8]['sales']+ $this->actualCS[4]['sales']),2,".",",") ?> to go!</h5>
				</div>
			</div></div>
			
			<div class="col p-0"><div class="m-2 card">
				<div class="card-body">
					<h5>LINE 9</h5>
					<h3>Achieved <?php echo number_format((($this->actual[9]['sales']+ $this->actualCS[5]['sales'])/$this->target[9]['target'])*100,2,".",",")?>%</h3>
					<h5 style ="color:green"><?php echo number_format(((($this->actual[9]['sales']+ $this->actualCS[5]['sales'])-($this->yesterday[9]['sales']+$this->yesterdayCS[5]['sales']))/($this->yesterday[9]['sales']+$this->yesterdayCS[5]['sales']))*100,2,".",",")?>% <i class = "fas fa-caret-up" style="color:green;"></i></h5>
					<div class="progress">
						<div class="progress-bar <?php echo number_format($this->target[9]['target']-$this->actual[9]['sales'],2,".",",") < 0 ? 'bg-success' : ''; ?>" role="progressbar" style="width: <?php echo number_format(($this->actual[9]['sales']/$this->target[9]['target'])*100,2,".",",") ?>%" aria-valuenow="<?php echo number_format((($this->actual[9]['sales']+ $this->actualCS[5]['sales'])/$this->target[9]['target'])*100,2,".",",") ?>" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
					<h5><?php echo number_format($this->target[9]['target']-($this->actual[9]['sales']+ $this->actualCS[5]['sales']),2,".",",") ?> to go!</h5>
				</div>
			</div></div>
			
			<div class="col p-0"><div class="m-2 card">
				<div class="card-body">
					<h5>LINE 10 สู้ ๆ <3</h5>
					<h3>Achieved <?php echo number_format((($this->actual[0]['sales']+$this->actualCS[0]['sales']+9460.00)/$this->target[0]['target'])*100,2,".",",")?>%</h3>
					<h5 style ="color:green"><?php echo number_format(((($this->actual[0]['sales']+$this->actualCS[0]['sales']+9460.00)-($this->yesterday[0]['sales']+$this->yesterdayCS[0]['sales']))/($this->yesterday[0]['sales']+$this->yesterdayCS[0]['sales']))*100,2,".",",")?>% <i class = "fas fa-caret-up" style="color:green;"></i></h5>
					<div class="progress">
						<div class="progress-bar <?php echo number_format($this->target[0]['target']-($this->actual[0]['sales']+$this->actualCS[0]['sales']+9460.00),2,".",",") < 0 ? 'bg-success' : ''; ?>" role="progressbar" style="width: <?php echo number_format((($this->actual[0]['sales']+$this->actualCS[0]['sales']+9460.00)/$this->target[0]['target'])*100,2,".",",") ?>%" aria-valuenow="<?php echo number_format((($this->actual[0]['sales']+$this->actualCS[0]['sales'])/$this->target[0]['target'])*100,2,".",",") ?>" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
					<h5><?php echo number_format($this->target[0]['target']-($this->actual[0]['sales']+$this->actualCS[0]['sales']+9460.00),2,".",",") ?> to go!</h5>
				</div>
			</div></div>
		</div>
		
		
		
		
	<!--</div>-->
	</div>
	
	
</body>

<script>
	//setTimeOut  location.reload
</script>

</html>