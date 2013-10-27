<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <meta name="viewport" content="user-scalable=no, width=device-width" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="default" />

    <link rel="apple-touch-startup-image" href="./images/icon/img/startup.png" />

    <link rel="shortcut icon" href="./images/icon/favicon.ico" />

    <link rel="apple-touch-icon" sizes="57x57" href="./images/icon/apple-touch-icon-114.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="./images/icon/apple-touch-icon-144.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="./images/icon/apple-touch-icon-144.png" />

    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="./images/icon/apple-touch-icon-114-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="./images/icon/apple-touch-icon-144-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="./images/icon/apple-touch-icon-144-precomposed.png" />

    <!-- <link rel=”stylesheet” media=”all and (orientation:portrait)” href=”portrait.css”> -->
    <!-- <link rel=”stylesheet” media=”all and (orientation:landscape)” href=”landscape.css”>  -->
    <link rel="stylesheet" type="text/css" href="css/table.css" />

    <title>Galaxy Empire - база данных сервера (ALL35)</title>
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

    $webroot = getenv("DOCUMENT_ROOT");
    $dbfile = $webroot."db/Galaxy_Empire_DB.sqlite";

    $db = new SQLite3($dbfile) or
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
            $pngfile = '/images/'.$row['Галактика'].'/'.$row['Система'].'/'.$row['Планета'].'.PNG';

            if (file_exists($pngfile)) {
                echo "<img src='$pngfile' alt='Что то там' width='100%'>";
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
