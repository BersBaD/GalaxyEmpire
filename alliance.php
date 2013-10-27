<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <title>Galaxy Empire - база данных сервера (ALL35)</title>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Language" content="ru" />
    <!-- Meta -->
    <meta name="description" content="Galaxy Empire - база данных сервера (ALL35)" />
    <meta name="keywords" content="IOS Game, Galaxy Empire, DB" />
    <meta name="author" content="BersBaD" />
    <!-- Apple -->
    <meta name="viewport" content="user-scalable=no, width=device-width" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <!-- Favicon -->
    <link rel="icon" href="favicon.ico" sizes="16x16 32x32 48x48 64x64" />
    <link rel="shortcut icon" href="favicon.ico" sizes="16x16 32x32 48x48 64x64" />
    <!-- Apple -->
    <link rel="apple-touch-icon" href="apple-touch-icon.png" />
    <link rel="apple-touch-icon-precomposed" href="apple-touch-icon-precomposed.png" />
    <link rel="apple-touch-icon" href="apple-touch-icon-60x60.png" sizes="60x60" />
    <link rel="apple-touch-icon-precomposed" href="apple-touch-icon-60x60-precomposed.png" sizes="60x60" />
    <link rel="apple-touch-icon" href="apple-touch-icon-76x76.png" sizes="76x76" />
    <link rel="apple-touch-icon-precomposed" href="apple-touch-icon-76x76-precomposed.png" sizes="76x76" />
    <link rel="apple-touch-icon" href="apple-touch-icon-120x120.png" sizes="120x120" />
    <link rel="apple-touch-icon-precomposed" href="apple-touch-icon-120x120-precomposed.png" sizes="120x120" />
    <link rel="apple-touch-icon" href="apple-touch-icon-152x152.png" sizes="152x152" />
    <link rel="apple-touch-icon-precomposed" href="apple-touch-icon-152x152-precomposed.png" sizes="152x152" />
    <!-- Apple -->
    <link rel="apple-touch-startup-image" href="startup.png" />
    <!-- CSS -->
    <!-- <link rel=”stylesheet” media=”all and (orientation:portrait)” href=”portrait.css”> -->
    <!-- <link rel=”stylesheet” media=”all and (orientation:landscape)” href=”landscape.css”>  -->
    <link rel="stylesheet" href="/css/table.css" type="text/css" />
  </head>

  <body>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
      <table class='mystyle' bgcolor='#ffcc00' align='center'>
        <tr>
          <th>Поиск по названию альянса</th>
        </tr>
        <tr>
          <th>Альянс</th>
        </tr>
        <tr>
          <td align='center'><input type="text" name="falliance" /></td>
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
    $falliance = $_POST['falliance'];

    $webhost = getenv("SERVER_NAME");
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
        planet.alliance = '$falliance'
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

            if (file_exists($webroot.$pngfile)) {
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
