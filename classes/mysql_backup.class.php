<?php
// +-------------------------------------------------+
// © 2002-2005 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: mysql_backup.class.php,v 1.8 2008-11-22 06:50:04 touraine37 Exp $

if (stristr($_SERVER['REQUEST_URI'], ".class.php")) die("no access");

// définition classe de gestion des sauvegardes

if ( ! defined( 'BACKUP_CLASS' ) ) {
  define( 'BACKUP_CLASS', 1 );

class mysql_backup {

// ---------------------------------------------------------------
//		propriétés de la classe
// ---------------------------------------------------------------

	var $host;					//---- host name e.g. localhost
	var $db;					//---- db name
	var $user;					//---- db username
	var $pass;					//---- db password
	var $fptr;					//---- file pointer
	var $filename;				//---- backup file concerned
	var $extension = '.bz2';	// extension for backup files
	var $buffer = '';			// a buffer
	var $dump = '';
// ---------------------------------------------------------------
//		mysql_backup() : constructeur
// ---------------------------------------------------------------

	function mysql_backup() {
		global $pmb_set_time_limit;
		@set_time_limit ($pmb_set_time_limit);
	}

//---- backup
//------------------------------------------

	function backup() {
		if($this->open_backup_stream()) {
			$this->fetch_data();
			$this->close_backup_stream;
		} else {
			die("can't open file for backup");
		}
	}

//---- restore
//------------------------------------------

	function restore($src) {
		global $dbh;
		$SQL='';
		if($src) {
			$this->filename=$src;
			if($this->open_restore_stream() && $this->buffer) {

				// open source file
				$SQL = preg_split('/;\s*\n|;\n/m', bzdecompress($this->buffer));
				for($i=0; $i < sizeof($SQL); $i++) {
					if($SQL[$i])
						$result = mysql_query($SQL[$i], $dbh);
				}
			} else {
				die("can't open file to restore");
				return FALSE;
			}
		}
		return TRUE;
	}


//---- This will fetch data in the whole database
//------------------------------------------

	function fetch_data() {

		global $dbh;

		//enumerate tables

		$res=mysql_list_tables(DATA_BASE);
		$i = 0;

		while($i < mysql_num_rows($res)) {
			$update_a_faire=0; /* permet de gérer les id auto_increment qui auraient pour valeur 0 */
			$table_name = mysql_tablename($res, $i);
			bzwrite ($this->fptr, "delete from $table_name;\n");
			$this->dump.="delete from $table_name;\n";
			//parse the field info first
			$res2=mysql_query("select * from ${table_name} order by 1 ",$dbh);
			$nf=mysql_num_fields($res2);
			$nr=mysql_num_rows($res2);

			$fields = '';
			$values = '';
			for ($b=0;$b<$nf;$b++) {

				$fn=mysql_field_name($res2,$b);
				$ft=mysql_fieldtype($res2,$b);
				$fs=mysql_field_len($res2,$b);
				$ff=mysql_field_flags($res2,$b);

				$is_numeric=false;

				switch(strtolower($ft))
					{
					case "int":
						$is_numeric=true;
						break;

					case "blob":
						$is_numeric=false;
						break;

					case "real":
						$is_numeric=true;
						break;

					case "string":
						$is_numeric=false;
						break;

					case "unknown":
						switch(intval($fs))
							{
							case 4:
								// little weakness here...
								// there is no way (thru the PHP/MySQL interface)
								// to tell the difference between a tinyint and a year field type
								$is_numeric=true;
								break;

							default:
								$is_numeric=true;
								break;
							}
						break;

					case "timestamp":
						$is_numeric=true;
						break;

					case "date":
						$is_numeric=false;
						break;

					case "datetime":
						$is_numeric=false;
						break;

					case "time":
						$is_numeric=false;
						break;

					default:
						//future support for field types that are not recognized
						//(hopefully this will work without need for future modification)
						$is_numeric=true;
						//I'm assuming new field types will follow SQL numeric syntax..
						// this is where this support will breakdown
						break;
					}

				$fields ? $fields .= ', '.$fn : $fields .= $fn;

				$fna[$b] = $fn;
				$ina[$b] = $is_numeric;
			}

			//parse out the table's data and generate the SQL INSERT statements in order to replicate the data itself...

			for ($c=0;$c<$nr;$c++) {
				$row=mysql_fetch_row($res2);
				$values = '';
				for ($d=0;$d<$nf;$d++) {
					$data=strval($row[$d]);
					if (($d==0) && (strval($row[$d])==0)) {
						/* traiter ici l'insertion avec valeur 1 pour id autoincrement et update à suivre */
						$values ? $values.= ', '.'1' : $values.= '1';
						$cle_update=mysql_field_name($res2, 0);
						$update_a_faire=1;
						} else {
							if ($ina[$d]==true)
								$values ? $values.= ', '.intval($data) : $values.= intval($data);
								else
									$values ? $values.=", \"".mysql_escape_string($data)."\"" : $values.="\"".mysql_escape_string($data)."\"";
							}

				}
				bzwrite ($this->fptr, "insert into $table_name ($fields) values ($values);\n");
				$this->dump.="insert into $table_name ($fields) values ($values);\n";
				if ($update_a_faire==1) {
					$update_a_faire=0;
					bzwrite ($this->fptr, "update $table_name set ".$cle_update."='0' where ".$cle_update."='1';\n");
					$this->dump.="update $table_name set ".$cle_update."='0' where ".$cle_update."='1';\n";
					}
			}

			mysql_free_result($res2);
			$i++;
		}

	}

//---- open out stream for backup
//------------------------------------------

	function open_backup_stream() {
		global $backup_dir;
		$dir = pmb_preg_replace('/\/\s$|\/$/', '', $backup_dir);
		if(!$dir) $dir = '.';
		$this->filename = time();
		$out_file = $dir.'/'.$this->filename.$this->extension;
		$tmp = @touch($out_file);
		$this->fptr = @bzopen($out_file, 'w');
		if($this->fptr)
			return TRUE;
		else
			return FALSE;
	}

//---- open stream to restore
//------------------------------------------

	function open_restore_stream() {
		global $backup_dir;
		$dir = pmb_preg_replace('/\/\s$|\/$/', '', $backup_dir);
		if(!$dir) $dir = '.';
		$in_file = $dir.'/'.$this->filename.$this->extension;
		$this->fptr = @fopen($in_file, 'rb');
		if($this->fptr) {
			$this->buffer = fread($this->fptr, filesize($in_file));
			fclose($this->fptr);
			return TRUE;
		} else {
			$this->buffer = '';
			return FALSE;
		}
	}


//---- closes backup stream
//------------------------------------------

	function close_backup_stream() {
		if($this->fptr)
			fclose($this->fptr);
	}


} # fin de définition de la classe mysql_backup

} # fin de délaration

