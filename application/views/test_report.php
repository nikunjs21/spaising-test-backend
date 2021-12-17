<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Form</title>
	<style>
		html {
			height: 100%;
		}

		body {
			height: 100%;
			font-family: sans-serif;
			background-color: #f5f5f5;
		}

		div {
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: baseline;
		}

		h1 {
			text-align: center;
		}

		table {
			width: 100%;
		}

		table,
		tr,
		th,
		td {
			border: 1px solid black;
			border-collapse: collapse;
			padding: 5px 10px;
		}

		tr:nth-child(even) {
			background-color: #c2c2c2;
		}
	</style>
</head>

<body>
	<h1>Test Report</h1>
	<a href="<?= site_url('/') ?>">Add new Data</a>
	<div>
		<table>
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th>Email</th>
					<th>Subject</th>
					<th>Message</th>
					<th>DateTime</th>
					<th>File</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $key => $value) : ?>
					<tr>
						<td><?= $key + 1 ?></td>
						<td><?= $value['name'] ?></td>
						<td><?= $value['email'] ?></td>
						<td><?= $value['subject'] ?></td>
						<td><?= $value['message'] ?></td>
						<td><?= $value['datetime'] ?></td>
						<td><a href="<?= site_url('uploads/' . $value['file']) ?>" target="_blank">show</a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</body>

</html>