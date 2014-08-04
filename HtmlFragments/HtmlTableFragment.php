<?php 


class HtmlTableFragment
{
	private $_body= array();
	private $_bodyClasses = array();
	

	private $_head = array();
	private $_class;

	function __construct($class)
	{
		$this->_class = $class;
	}

	public function AddBodyRow($row,$class = "")
	{

		array_push($this->_bodyClasses,$class);
		array_push($this->_body,$row);
	}


	public function AddHeadRow($row)
	{
		array_push($this->_head,$row);
	}

	public function Output()
	{
		?>
		<table class="<?php echo $this->_class; ?>">
			<thead>
				<?php for($x = 0; $x < count($this->_head );$x++): ?>
				<tr>
					<?php for($y = 0; $y < count($this->_head[$x] );$y++): ?>
						<th>

							<?php echo $this->_head[$x][$y]; ?>
							
						</th>
					<?php endfor; ?>
				</tr>
					<?php endfor; ?>
			</thead>
			<tbody>
				
				<?php for($x = 0; $x < count($this->_body );$x++): ?>
					<tr class="<?php echo $this->_bodyClasses[$x] ?>">
						<?php for($y = 0; $y < count($this->_body[$x] );$y++): ?>
							<td>
								<?php echo  $this->_body[$x][$y]; ?>
					
							</td>
						<?php endfor; ?>
					</tr>
				<?php endfor; ?> 
			</tbody>
		</table>

		<?php
	}
}

?>