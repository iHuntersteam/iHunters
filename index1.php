<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Пример</title>
</head>
<body>
  <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
  <select name="test[]" multiple="multiple">
          <option value="1">Медведев</option>
          <option value="2">Путин</option>
          <option value="3">Навальный</option>
  </select>
  <input type="submit" value="Send" />
  </form>

<?php

  
$my_user = 'root';
$my_password = '';
$my_db = 'ihunters';


$link = mysqli_connect('localhost', $my_user, $my_password, $my_db);

if (!$link) {
  die('Ошибка подключения (' . mysqli_connect_errno() . ') '
        . mysqli_connect_error());
}

echo 'Соединение установлено... ' . mysqli_get_host_info($link) . "\n";

 
  $test = $_POST['test'];
  if ($test)
  {
    foreach ($test as $t)
    {
      $query = "SELECT * FROM persons LEFT JOIN person_page_rank ON persons.id=person_page_rank.person_id WHERE id='$t' ";
      $result = mysqli_query($link, $query);

      if (!$result) 
      {
        $message  = 'Неверный запрос: ' . mysqli_error() . "\n";
        $message .= 'Запрос целиком: ' . $query;
        die($message);
      }

      while ($row = mysqli_fetch_assoc($result)) 
      {
        echo $row['name'];
        echo $row['rank'];
      }
    }
  }


?>
</body>
</html>