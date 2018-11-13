<?php 

namespace App;

/*
   $Id: class-eggTimer.php v 0.3.19 - RedondoWS $
   FSx-Connector
   (c) 2015-2016 RWS (www.redondows.com / www.factusol-woocommerce.es)
   -----------------------------------------------------------------------------------------

   WooCommerce
   http://www.woothemes.com/woocommerce/
   ---------------------------------------------------------------------------------------*/
   
   
/* *********************************************************************** 

Usage example:

			
		require_once (DIR_FS_INC.'bluegate_seo.inc.php');
		$bluegateSeo = new BluegateSeo();
		
        // Product
        $start_line=xtc_db_prepare_input($_REQUEST['start_line']); // Next line to be processed
		if (!($start_line>1)) $start_line=1+($columnheadings?1:0);
		else $start_line=(int) $start_line; 
		
		$resultList = array();
		
		// Language for SEO
		$languages = xtc_get_languages();
		$resultList['code'] = FSOL_LANGUAGE_ISO;
		foreach ($languages AS $lang) {
			if ($lang['code'] == $resultList['code'] ) $resultList['language_id'] = $lang['id'];
		}
		
		// Process file 
		$cPath=MODULE_IMPORT_ITDHIDDEN_DEST_CATEGORY;
		// $i_new = 0;
		// $i_act = 0;
		// $row = 0;
		// $process_all=true;
		$myTimer= new eggTimer();
		$rwsCat = new rws_categories();
		
		$fp = fopen ( DIR_FS_CATALOG.'import/'.$_SESSION['upload_file_name'] , "r" );
		
		// Skip lines
		for($j=1;$j<$start_line;$j++) {
				if ( ( $data = fgetcsv ( $fp , 200 , $delimiter, $enclosure) ) === FALSE ) break;
		}
		// $data_query = xtc_db_query( "SELECT * FROM " . TABLE_ITD . " WHERE itemID >= " . $start_prod . " ORDER BY itemID ASC" );
		
		$next_prod=$start_line;

		while (( $data = fgetcsv ( $fp , 200 , $delimiter, $enclosure) ) !== FALSE ) { 
			
			// Process Data!
				$myTimer->checkTimeout();
				if ($myTimer->timeout_passed) {
					// save $next_prod and do stuff
					// $process_all=false;
					// $messageStack->add_session('No se cargaron todos los Productos. Debe continuar el proceso ('.$row.')', 'warning');
					// xtc_redirect(xtc_href_link(FILENAME_MODULE_EXPORT, 'set=' . $_GET['set'] . '&module=import_itd' . '&action=edit&iStep=1'.'&start_line='.$next_prod));
					   xtc_redirect(xtc_href_link(FILENAME_MODULE_EXPORT, 'set=' . $_GET['set'] . '&module=import_itd' . '&action=save&iStep=1'.'&start_line='.$next_prod));
					break;
				}
			// $row++;

			
				// Here we go!
				$data[3] = str_replace(',', '.', $data[3]);
				$resultList['products_id'] = $rwsCat->insert_articulo_fsol($data, $cPath);
					// Index new Product
					// Insert into bluegate_seo_url Table
					$resultList['products_name'] = $data[0];
					$bluegateSeo->insertProductSeoDBTable( $resultList );
					
				$next_prod++;
			
		} // While ends
		
		fclose ( $fp ); 
		// $messageStack->add_session('Se han procesado '.$i_new.' productos nuevos', 'success');
		// $messageStack->add_session('Se han actualizado '.$i_act.' productos existentes', 'success');
		
		if ( !unlink(DIR_FS_CATALOG.'import/'.$_SESSION['upload_file_name']) ) $messageStack->add_session('No se pudo borrar el fichero: '.DIR_FS_CATALOG.'import/'.$_SESSION['upload_file_name'], 'error');
		else $messageStack->add_session('El fichero '.DIR_FS_CATALOG.'import/'.$_SESSION['upload_file_name'].' se ha borrado correctamente del servidor', 'success');
		
				$messageStack->add_session('Paso 1 procesado(Cargar Productos)', 'success');

 *********************************************************************** */

//  define('ABI_ALLOWED_TIME', 0);	// 0 means 'all available'
//  define('ABI_TIME_OFFSET' , 3);

class eggTimer
{
	private $timestamp;
	private $maximum_time;
	private $time_offset;  // Seconds!
	private $timeout_passed;

    public  $milestones;
	
public function __construct($allowed_time = 0, $time_offset = 3)
{
        //get max time we have
        $max_execution_time = @ini_get ( 'max_execution_time' );
        if ( $max_execution_time == 0 ) {
            $max_execution_time = 30;
        }
        if ( $allowed_time > 0 && $allowed_time < $max_execution_time) {
            $max_execution_time = $allowed_time;
        }

		$this->timestamp = $this->_getTime();
		$this->maximum_time = $max_execution_time;
		$this->time_offset = (int) ($time_offset>0 ? $time_offset : 0);
		$this->timeout_passed = FALSE;
		
		$this->milestones = array();
}

private function _getTime() 
{
	/*
		return time();  // number of seconds since the Unix Epoch
	*/
		list($utime, $time) = explode(" ", microtime());
		return ((float)$utime + (float)$time);
}

public function getAllowedTime() 
{
	return $this->maximum_time - $this->time_offset;
}

public function setMaximumTime( $seconds = 0 ) 
{
	$secs = intval($seconds);
	if ( $secs <  0 ) return;
	if ( $secs == 0 ) $secs = 30;

	$this->maximum_time = $secs;
}
	
public function checkTimeout()
{
    if ($this->maximum_time == 0) {
        return FALSE;
    } elseif ($this->timeout_passed) {
        return TRUE;
    /* time_offset = 5 in next row might be too much */
    } elseif (($this->_getTime() - $this->timestamp) > ($this->maximum_time - $this->time_offset)) {
        $this->timeout_passed = TRUE;
        return TRUE;
    } else {
        return FALSE;
    }
}

public function timing_milestone($name) 
{
    $this->milestones[] = array($name, $this->_getTime());
}

public function save_profile(fsxLogger $rwsConnectorLog) 
{
	$output = 'Milestone -/- Segundos -/- Acumulado';
	$rwsConnectorLog->write($output, 'TIMER');
			  
	foreach ($this->milestones as $elem => $data) {
      
	  $output = $data[0].' -/- '.
				round(($elem ? $data[1] - $this->milestones[$elem - 1][1]: '0'), 5).' -/- '.
				round(($data[1] - $this->milestones[0][1]), 5);
				$rwsConnectorLog->write($output, 'TIMER');
    }
	
	// $rwsConnectorLog->write($output, 'TIMER');
}

public function dump_profile($return = true) 
{
    // self::$milestones[] = array('finish', $this->_getTime());
	if (!$this->time_offset) return;
	$this->timing_milestone('Control de tiempo Detenido');
    $output = '<table class="table" style="margin: 0 0 15px" cellspacing="0" cellpadding="0">'.
              '<tr><th style="width: 290px">Milestone</th><th style="width: 50px">Segundos</th><th style="width: 50px">Acumulado</th></tr>';
    foreach ($this->milestones as $elem => $data) {
      $output .= '<tr onmouseout="this.style.backgroundColor=\'\'" onmouseover="this.style.backgroundColor=\'#FFF6CF\'" style=""><td>'.$data[0].'</td>'.
        '<td>'.round(($elem ? $data[1] - $this->milestones[$elem - 1][1]: '0'), 5).'</td>'.
        '<td>'.round(($data[1] - $this->milestones[0][1]), 5).'</td></tr>';
    }
    $output .= '</table>'; 
    if ($return) return $output;
    echo $output;
}

}  // class eggTimer ends
