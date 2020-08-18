<?php
$host = 'localhost';
$user = 'root';
$password = '';
$db_name = 'guestbook';

$link = mysqli_connect($host, $user, $password, $db_name);
mysqli_query($link, "SET NAMES 'utf8'");




?>


<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<title>Гостевая книга</title>
		<link rel="stylesheet" href="css/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" href="css/styles.css">
	</head>
	<body>
		<div id="wrapper">
			<h1>Гостевая книга</h1>
			<div>
				<nav>
				  <ul class="pagination">
					<li class="disabled">
						<a href="?page=1"  aria-label="Previous">
							<span aria-hidden="true">&laquo;</span>
						</a>
					</li>
					<?php
					if (isset($_GET['page'])){
						$page = $_GET['page'];
					}
					else {
						$page = 1;
					}
					$notes = 3;
					$from = ($page-1) * $notes;

					$query = "SELECT COUNT(*) as count FROM guests2";
					$result = mysqli_query($link, $query) or die(mysqli_error($link));
					$count = mysqli_fetch_assoc($result)['count'];
					$pagesCount = ceil($count/$notes);


					for ($i = 1; $i <=$pagesCount; $i++){
						echo "<li><a href =\"?page=$i\">$i</a></li>";
					}

					/*

										<li class="active"><a href="?page=1">1</a></li>
										<li><a href="?page=2">2</a></li>
										<li><a href="?page=3">3</a></li>
										<li><a href="?page=4">4</a></li>
										<li><a href="?page=5">5</a></li>
							*/

					?>
					<li>
						<a href="?page=5" aria-label="Next">
							<span aria-hidden="true">&raquo;</span>
						</a>
					</li>
				  </ul>
				</nav>
			</div>
			<?php

			if (!empty($_POST)){
				$time =  date("Y.m.d H:i:s ");

				$name = mysqli_real_escape_string($link, $_POST['name']);
				$text = mysqli_real_escape_string($link, $_POST['text']);

				$query = "INSERT INTO guests2 (date, name, text) VALUES ('$time', '$name', '$text')";
				mysqli_query($link, $query) or die(mysqli_error($link));
			}

			$query = "SELECT * FROM guests2 ORDER BY id DESC LIMIT $from,$notes";
			$result = mysqli_query($link, $query) or die(mysqli_error($link));

			for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
			$result = '';
			foreach ($data as $elem){

				$result .= "<div class=\"note\">";
				$result .= '<p>';
				$result .= "<span class = \"date\">" . $elem['date'] . '</span>';
				$result .= "<span class = \"name\">" . $elem['name'] . '</span>';
				$result .= '</p>';
				$result .= '<p>' . $elem['text']. '</p>';
				$result .= '</div>';
			}
			echo $result;
			?>
		<?php	/*<div class="note">
				<p>
					<span class="date">16.04.2014 14:59:59</span>
					<span class="name">Николай</span>
				</p>
				<p>
					Ut varius commodo fringilla. Nullam id pulvinar odio. Pellentesque gravida aliquam ipsum, et malesuada neque molestie eget. Vestibulum sagittis finibus efficitur. Donec sit amet aliquet dolor, vitae ornare tortor. Etiam eget augue nec diam vehicula bibendum. Nulla quis erat lacus. Vestibulum quis mattis augue. Praesent dignissim, justo non aliquam feugiat, lorem metus egestas leo, quis eleifend odio quam in ex. Aenean diam est, scelerisque ac ultricies sit amet, vulputate in tortor. Etiam ac mi enim. Sed pellentesque elementum erat eu eleifend. Integer imperdiet sem eu magna feugiat, sed efficitur velit convallis.
				</p>
			</div>
			<div class="note">
				<p>
					<span class="date">15.04.2014 12:59:59</span>
					<span class="name">Петр</span>
				</p>
				<p>
					Phasellus gravida fermentum pellentesque. Aenean non neque mollis nisl dapibus eleifend. Sed interdum dui nec dictum elementum. Proin eget semper dolor, ut commodo nibh.
					Quisque vitae pharetra ligula. Sed dictum, sem sed pellentesque aliquam, tellus sapien dapibus magna, eu suscipit lacus augue sed velit. Ut vehicula sagittis nulla, et aliquet elit. Quisque tincidunt sem nibh, finibus dictum nisl vulputate quis. In vitae nisl et lacus pulvinar ornare id ac libero. Morbi pharetra fringilla erat ut lacinia.
				</p>
			</div>
			*/
			?>
			<?php
					if (!empty($_POST) and $name != '' and $text != ''){
						echo "<div class=\"info alert alert-info\">
							Запись успешно сохранена!
						</div>";
					}

			?>
			<div id="form">
				<form action="#form" method="POST">
					<p><input name = "name" class="form-control" placeholder="Ваше имя"></p>
					<p><textarea name = "text" class="form-control" placeholder="Ваш отзыв"></textarea></p>
					<p><input type="submit" class="btn btn-info btn-block" value="Сохранить"></p>
				</form>
			</div>
		</div>
	</body>
</html>
