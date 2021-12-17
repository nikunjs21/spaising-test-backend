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
			justify-content: center;
			align-items: baseline;
		}

		h1 {
			text-align: center;
		}

		form {
			display: flex;
			width: 30rem;
			flex-direction: column;
			flex-wrap: wrap;
		}

		input,
		textarea {
			margin: 5px 0;
			padding: 7px 15px;
			border: 1px solid #ccc;
			outline: none;
			border-radius: 5px;
			box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.2);
			background-color: #fff;
		}

		label {
			margin-top: 5px;
			font-weight: 600;
		}

		button {
			width: 10rem;
			background-color: #77ff77;
			outline: none;
			border: none;
			padding: 5px 10px;
			margin-bottom: 30px;
			margin-top: 10px;
			font-size: 22px;
			border-radius: 5px;
			box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.2);
			cursor: pointer;
		}

		button:hover,
		button:active {
			background-color: #00ff00;
		}
	</style>
</head>

<body>
	<h1>Test Form</h1>
	<a href="<?=site_url('welcome/report')?>">See Report</a>
	<div>
		<form action="<?= site_url('welcome/insert') ?>" method="post" enctype="multipart/form-data">
			<label for="name">Enter Name</label>
			<input type="text" placeholder="Enter Name" name="name" id="name" required>
			<label for="email">Enter Email</label>
			<input type="email" placeholder="Enter Email" name="email" id="email" required>
			<label for="subject">Enter Subject</label>
			<input type="text" placeholder="Enter Subject" name="subject" id="subject" required>
			<label for="message">Enter Message</label>
			<textarea name="message" placeholder="Enter Message" id="message" cols="30" rows="10" required></textarea>
			<label for="date">Select Date</label>
			<input type="date" name="date" id="date" value="<?= date('Y-m-d') ?>" required>
			<label for="time">Select Time</label>
			<input type="time" name="time" id="time" required>
			<label for="file">Select File</label>
			<input type="file" name="file" id="file">
			<button type="submit">Submit</button>
		</form>
	</div>
</body>

</html>