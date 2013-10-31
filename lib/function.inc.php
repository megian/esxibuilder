<?php
//-----------------------------------------------------------------------------
// library        function.inc.php
// version        1.0
// date           26.10.2013
// update         26.10.2013
// authors        Gabriel Mainberger <gabisoft@freesurf.ch>
// licence        GPL
//-----------------------------------------------------------------------------
// Copyright (c) 2013 Gabriel Mainberger <gabisoft@freesurf.ch>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//------------------------------------------------------------------------------
//
// History:
//
// 26.10.2013 - Gabriel Mainberger <gabisoft@freesurf.ch>
//   - Initial Version
//
//------------------------------------------------------------------------------

//-----------------------------------------------------------------------------
// @function        errormsg()
// @paramter        Fehlermeldung
// @return        nichts
// @description        Gibt eine Fehlermeldung aus und bricht die Ausführung
//                der Script Ausführung ab.
//-----------------------------------------------------------------------------

function errormsg($msg)
{
  html_begin();
  echo "ERROR: $msg";
  html_end();
  exit;
}

//-----------------------------------------------------------------------------
// @function        warnmsg()
// @paramter        Warnung
// @return        nichts
// @description        Gibt eine Warnmeldung aus und führt das Script weiter aus.
//-----------------------------------------------------------------------------

function warnmsg($msg)
{
  echo $msg;
}

//-----------------------------------------------------------------------------
// function			html_begin()
// paramter			Tiel der Seite (optional)
// description		Erstellt den Grundaufbau der HTML Seite
//-----------------------------------------------------------------------------

function html_begin($title="Noname")
{
	global $page_head;

	echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"\n";
	echo "	\"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n";
	echo "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"de\">";
	echo "<head>";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
	echo "<title>$title</title>";
	echo "<meta name=\"author\" content=\"Gabriel Mainberger\" />";
	echo "<link rel=\"stylesheet\" title=\"Default Style\" type=\"text/css\" href=\"css/default.css\" />";
	echo "<script type=\"text/javascript\" src=\"js/jquery-1.10.1.min.js\"></script>";
	echo $page_head;
	echo "</head>\n";
	echo "<body>\n";
}

//-----------------------------------------------------------------------------
// @function        html_end()
// @paramter        nichts
// @return        nichts
// @description        Beendet die HTML Seite
//-----------------------------------------------------------------------------

function html_end()
{?>
</body>
</html>
<?php }

//-----------------------------------------------------------------------------
// @function		db_open()
// @paramter		-
// @return		-
// @description		Stellt die Verbindung zur Datenbank her und setzt die
//			Standard Datenbank. Die Paramter die benötigt werden, werden
//			in der config.inc.php konfiguriert.
//			Der Datenbank-Handle ist in der globalen Variable
//			$mongodb_handle vorhanden.
//-----------------------------------------------------------------------------

function db_open()
{
	global $config_mongodb_connection_string;
	global $config_mongodb_database;
	global $mongodb_handle;

	try
	{
		$connection_handle = new Mongo($config_mongodb_connection_string);
		$mongodb_handle = $connection_handle->selectDB($config_mongodb_database);
	}
	catch (MongoConnectionException $e) 
	{
		echo '<p>Couldn\'t connect to mongodb, is the "mongo" process running?</p>';
		exit();
	}
}

//-----------------------------------------------------------------------------
// @function        db_query()
// @paramter        SQL
// @return        Array mit allen Daten
// @description        Fragt mit einem SQL Befahl die Datenbank ab und gibt das
//                Resultat in einem Arry zurück.
//-----------------------------------------------------------------------------

function db_query($collection, $query=array(), $fields=array())
{
	global $mongodb_handle;

	$collection = $mongodb_handle->selectCollection($collection);
	return $collection->findOne($query, $fields);
}

function db_insert($collection, $fields)
{
	global $mongodb_handle;

	$collection = $mongodb_handle->selectCollection($collection);
	return $collection->insert($fields);
}

function db_update($collection, $criteria, $fields)
{
	global $mongodb_handle;

	$collection = $mongodb_handle->selectCollection($collection);
	return $collection->update($criteria, $fields);
}

function db_remove($collection, $criteria)
{
	global $mongodb_handle;

	$collection = $mongodb_handle->selectCollection($collection);
	return $collection->remove($criteria);
}

//-----------------------------------------------------------------------------
// @function		db_sql_multi()
// @paramter		SQL, Aufzurufende-Funktion
// @return		nichts
// @description		Fragt mit einem SQL Befahl die Datenbank ab und ruft für jeden
//			Datensatz die angegebene Funktion auf.
//-----------------------------------------------------------------------------

function db_query_multi($func, $collection, $query=array(), $fields=array())
{
	global $mongodb_handle;

	$collection = $mongodb_handle->selectCollection($collection);
	$documents = $collection->find($query, $fields);

	foreach ($documents as $document)
		$func ($document);
}

function db_query_array($collection, $query=array(), $fields=array())
{
	global $mongodb_handle;

	$collection = $mongodb_handle->selectCollection($collection);
	$documents = $collection->find($query, $fields);

	$return = array();	
	foreach ($documents as $document)
		array_push($return, $document);

	return $return;
}

//-----------------------------------------------------------------------------
// @function        db_sql_table()
// @paramter        SQL, Titel der Tabelle
// @return        nichts
// @description        Fragt mit einem SQL Befahl die Datenbank ab und erstellt eine
//                Tabelle. Die Titel und die Spaltenbreiten, werden durch
//                $titles definiert.
//                titles: Anzeige Name:Spaltenbreite:PHP-Datei
//-----------------------------------------------------------------------------

/*function db_sql_table($sql, $titles)
{
  global $mysql_connect_handle;
?>
<table width="100%">
<tr>
<?php
  $t = split(":", $titles);
  $ti = count($t);
  for($i=0;$i<$ti;$i+=3)
    echo "  <th width=\"".$t[$i+1]."\">".$t[$i]."</th>\n";
  echo "</tr>\n";

  $ti = (int)($ti/3);

  if(!$result = @mysql_query($sql, $mysql_connect_handle))
    warnmsg("db_sql_table(): Konnte Query nicht ausführen: $sql");

  while($row = @mysql_fetch_row($result))
  {
    for($i=1;$i<=$ti;$i++)
    {
      if($t[$i*3-1]=="")
        echo "  <td>".$row[$i]."</td>\n";
      else
        echo "  <td><a href=\"".$t[$i*3-1].$row[0]."\">".$row[$i]."</td>\n";
    }
    echo "</tr>\n";
    $ti2--;
  }
  @mysql_free_result($result);
  echo "</table>\n";
}*/

//-----------------------------------------------------------------------------
// @function        db_sql_table_orderby()
// @paramter        SQL-Syntax, Titel der Tabelle, Sortiertung
// @return        nichts
// @description        Fragt mit einem SQL Befahl die Datenbank ab und erstellt eine
//                Tabelle. Die Titel und die Spaltenbreiten, werden durch
//                $titles definiert.
//                titles: Anzeige Name:Datenbank Feld:Spaltenbreite:PHP-Datei
//-----------------------------------------------------------------------------

/*function db_sql_table_orderby($sql, $titles, $orderby)
{
  global $mysql_connect_handle;
?>
<table width="100%">
<tr>
<?php
  $t = split(":", $titles);
  $ti = count($t);
  for($i=0;$i<$ti;$i+=4)
    echo "  <th width=\"".$t[$i+2]."\"><a href=\"$SELF_PHP?o=".($i/4)."\">".$t[$i]."</a></th>\n";
  echo "</tr>\n";

  $ti = (int)($ti/4);

  if($orderby!="")
    $sql .= " ORDER BY ".$t[$orderby*4+1];

  $result = @mysql_query($sql, $mysql_connect_handle);

  while($row = @mysql_fetch_row($result))
  {
    echo "<tr>\n";
    for($i=1;$i<=$ti;$i++)
    {
      if($t[$i*4-1]=="")
        echo "  <td>".$row[$i]."</td>\n";
      else
        echo "  <td><a href=\"".$t[$i*4-1]."?id=".$row[0]."\">".$row[$i]."</td>\n";
    }
    echo "</tr>\n";
    $ti2--;
  }
  @mysql_free_result($result);
  echo "</table>\n";
}*/

//-----------------------------------------------------------------------------
// @function        db_navigation()
// @paramter        Datenbank Tabellenname, Limit
// @return        nichts
// @description        Erstellt eine Navigationsleiste
//-----------------------------------------------------------------------------

function db_navigation($collection, $limit)
{
	global $mongodb_handle;

	$collection = $mongodb_handle->selectCollection($collection);
	$count = $collection->count();

	$count = (int)($count / $limit);

	for ($i = 0;$i < $count;$i++)
		echo "<a href=\"?o=$i\">[".($i+1)."]</a> ";
}

?>
