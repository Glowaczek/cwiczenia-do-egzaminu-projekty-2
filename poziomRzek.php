<!doctype html>
<html lang="pl">
<head>
<meta charset="utf-8">
<title>Poziomy rzek</title>
<link rel="stylesheet" href="styl.css">
</head>
<body>
<section id='baner1'>
<img src='obraz1.png' alt='Mapa polski'>
</section>

<section id='baner2'>
<h1>Rzeki w województwie dolnośląskim</h1>
</section>

<nav>
<form action='poziomRzek.php' method='post'>
<label for='wszystkie'>
<input type='radio' id='wszystkie' name='stan' value="wszystkie" >
wszystkie</label>

<label for='ponad'>
<input type='radio' id='ponad' name='stan' value="ponad">
Ponad stan ostrzegawczy</label>

<label for='alarmowy'>
<input type='radio' id='alarmowy' name='stan' value="alarmowy">
Ponad stan alarmowy</label>
<input type='submit' value='Pokaż'>
</form>
</nav>

<section id='lewy'>
<h3>Stany na dzień 2022-05-05</h3>
<table>
<tr>
<td>Wodomierz</td>
<td>Rzeka</td>
<td>Ostrzegawczy</td>
<td>Alarmowy</td>
<td>Aktualny</td>
</tr>
<?php
$conn = mysqli_connect('localhost', 'root', '', 'rzeki');


if (isset($_POST['stan'])) {
    $stan = $_POST['stan'];

    if ($stan == 'wszystkie') {
        $sql = "SELECT nazwa, rzeka, stanOstrzegawczy, stanAlarmowy, stanWody 
                FROM wodowskazy 
                JOIN pomiary ON wodowskazy.id = wodowskazy_id 
                WHERE dataPomiaru='2022-05-05';";
    } elseif ($stan == 'ponad') {
        $sql = "SELECT nazwa, rzeka, stanOstrzegawczy, stanAlarmowy, stanWody 
                FROM wodowskazy 
                JOIN pomiary ON wodowskazy.id = wodowskazy_id 
                WHERE dataPomiaru='2022-05-05' AND stanWody > stanOstrzegawczy;";
    } elseif ($stan == 'alarmowy') {
        $sql = "SELECT nazwa, rzeka, stanOstrzegawczy, stanAlarmowy, stanWody 
                FROM wodowskazy 
                JOIN pomiary ON wodowskazy.id = wodowskazy_id 
                WHERE dataPomiaru='2022-05-05' AND stanWody > stanAlarmowy;";
    } else {
        $sql = ""; // Domyślna wartość, aby zapobiec błędowi
    }

    if (!empty($sql)) {
        $zapytanie = mysqli_query($conn, $sql);

        while ($wynik = mysqli_fetch_array($zapytanie)) {
            echo "<tr>";
            echo "<td>{$wynik[0]}</td>";
            echo "<td>{$wynik[1]}</td>";
            echo "<td>{$wynik[2]}</td>";
            echo "<td>{$wynik[3]}</td>";
            echo "<td>{$wynik[4]}</td>";
            echo "</tr>";
        }
    }
}
mysqli_close($conn);
?>

</table>
</section>

<section id='prawy'>
<h2>Informacje</h3>
<ul>
<li>Brak ostrzeżeń o burzach z gradem</li>
<li>Smog w mieście Wrocław</li>
<li>Silny wiatr w Karkonoszach</li>
</ul>

<h3>Średnie stany wód</h3>
<?php
$conn = mysqli_connect('localhost', 'root', '', 'rzeki');   

$sql = mysqli_query($conn,'SELECT dataPomiaru, AVG(stanWody) FROM pomiary GROUP BY dataPomiaru; ');

while ($wynik = mysqli_fetch_array($sql)) {
	echo "<p>$wynik[0]: $wynik[1]</p>";
}

mysqli_close($conn);
?>

<a href='https://komunikaty.pl'>Dowiedz się więcej</a>

<img src='obraz2.jpg' alt='rzeka'>
</section>

<footer>
<p>Strone wykonał: 052814</p>
</footer>
</body>
</html>