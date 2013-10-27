<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta id="viewport" name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
    <title>Galaxy Empire - база данных сервера (ALL35)</title>
    <link rel="stylesheet" type="text/css" href="css/table.css" />
  </head>

  <body>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
      <table class='mystyle' bgcolor='#ffcc00' align='center'>
        <tr>
          <th>Поиск по координатам</th>
        </tr>
        <tr>
          <th>Координаты</th>
        </tr>
        <tr>
          <td align='center'>
            [<input type="number" min="1" max="9" size="1" maxlength="1" name="fgalaxy" />:
            <input type="number" min="1" max="499" size="3" maxlength="3" name="fsystem" />:
            <input type="number" min="0" max="21" size="2" maxlength="2" name="fplanet" />]
          </td>
        </tr>
        <tr>
          <td align='center'><input type="submit" name="submit" value="Поиск" /></td>
        </tr>
        <tr>
          <td align='center'><input type=button onClick="location.href='index.php'" value='На главную'></td>
        </tr>
      </table>
    </form>
  </body>

</html>

<?php
  if (isset($_POST['submit'])) {
    $fgalaxy = $_POST['fgalaxy'];
    $fsystem = $_POST['fsystem'];
    $fplanet = $_POST['fplanet'];

    if ($fplanet ==0) {
      $SQL = "coordinates.galaxy = '$fgalaxy'
              AND coordinates.system = '$fsystem'
              AND coordinates.planet <= '15'";
    } else {
      $SQL = "coordinates.galaxy = '$fgalaxy'
              AND coordinates.system = '$fsystem'
              AND coordinates.planet = '$fplanet'";
    }

    $db = new SQLite3('Galaxy_Empire_DB.sqlite') or
    die("failed to open/create the database");

    $results = $db->query("
      SELECT
        coordinates.galaxy AS 'Галактика',
        coordinates.system AS 'Система',
        coordinates.planet AS 'Планета',
        planet.player AS 'Игрок',
        planet.alliance AS 'Альянс',
        planet.size AS 'Размер',
        planet.temperature AS 'Температура',
        planet.moon AS 'Луна',
        planet.slot1 AS 'Слот №1',
        planet.slot2 AS 'Слот №2',
        planet.slot3 AS 'Слот №3'
      FROM
        planet
      INNER JOIN coordinates ON planet.ID = coordinates.ID
      WHERE
        $SQL
      LIMIT 25");

    while ($row = $results->fetchArray()) {
?>
      <br/>
      <table class='mystyle' bgcolor='#ffcc00' align='center'>
        <tr>
          <th colspan='2'>Результат</th>
        </tr>
        <tr>
          <td align='center'>Координаты</td>
          <td align='center'>[<?php echo $row['Галактика'];?>:<?php echo $row['Система'];?>:<?php echo $row['Планета'];?>]</td>
        </tr>
        <tr>
          <td align='center'>Игрок</td>
          <td align='center'><?php echo $row['Игрок'];?></td>
        </tr>
        <tr>
          <td align='center'>Альянс</td>
          <td align='center'><?php echo $row['Альянс'];?></td>
        </tr>
        <tr>
          <td align='center'>Размер</td>
          <td align='center'><?php echo $row['Размер'];?></td>
        </tr>
        <tr>
          <td align='center'>Температура</td>
          <td align='center'><?php echo $row['Температура'];?></td>
        </tr>
        <tr>
          <td align='center'>Луна</td>
          <td align='center'><?php echo $row['Луна'];?></td>
        </tr>
        <tr>
          <td align='center'>Слот №1</td>
          <td align='center'><?php echo $row['Слот №1'];?></td>
        </tr>
        <tr>
          <td align='center'>Слот №2</td>
          <td align='center'><?php echo $row['Слот №2'];?></td>
        </tr>
        <tr>
          <td align='center'>Слот №3</td>
          <td align='center'><?php echo $row['Слот №3'];?></td>
        </tr>
        <tr>
          <td align='center' colspan='2'>
          <?php
            $file = './images/'.$row['Галактика'].'/'.$row['Система'].'/'.$row['Планета'].'.PNG'; // 'images/'.$file (physical path)

            if (file_exists($file)) {
                echo "<img src='$file' alt='Что то там' width='100%'>";
            } else {
                echo "Нет картинки :(";
            }
          ?>
          </td>
        </tr>
      </table>
      <br/>
<?php
    }
  }
?>
