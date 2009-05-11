<h2>Server variables</h2>
<?php foreach($variables AS $name=>$variable) { ?>
	<table class="list">
		<thead>
			<tr>
				<th colspan="2"><?php echo $name; ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($variable AS $key=>$value) { ?>
				<tr>
					<td><?php echo $key; ?></td>
					<td><?php echo $value; ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<br/>
<?php } ?>

