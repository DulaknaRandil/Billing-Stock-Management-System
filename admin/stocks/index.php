<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">List of Stocks</h3>
	</div>
	<div class="card-body">
		<div class="container-fluid">
			<table class="table table-bordered table-stripped">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="15%">
					<col width="30%">
					<col width="15%">
					<col width="20%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Category</th>
						<th>Item Name</th>
						<th>Supplier</th>
						<th>Description</th>
						
						<th>Available Stocks</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
					$qry = $conn->query("SELECT i.*, s.name as supplier, c.category_name 
                                         FROM `item_list` i 
                                         INNER JOIN supplier_list s ON i.supplier_id = s.id 
                                         INNER JOIN category_list c ON i.category_id = c.id 
                                         ORDER BY `name` DESC");
					while($row = $qry->fetch_assoc()):
						$in = $conn->query("SELECT SUM(quantity) as total FROM stock_list WHERE item_id = '{$row['id']}' AND type = 1")->fetch_array()['total'];
						$out = $conn->query("SELECT SUM(quantity) as total FROM stock_list WHERE item_id = '{$row['id']}' AND type = 2")->fetch_array()['total'];
						$row['available'] = $in - $out;
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo $row['category_name'] ?></td>
							<td><?php echo $row['name'] ?></td>
							<td><?php echo $row['supplier'] ?></td>
							<td><?php echo $row['description'] ?></td>
							
							<td class="text-right"><?php echo number_format($row['available']) ?></td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

</div>
<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this Received Orders permanently?","delete_receiving",[$(this).attr('data-id')])
		})
		$('.view_details').click(function(){
			uni_modal("Receiving Details","receiving/view_receiving.php?id="+$(this).attr('data-id'),'mid-large')
		})
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable();
	})
	function delete_receiving($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_receiving",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>