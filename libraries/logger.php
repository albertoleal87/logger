<?php

/**
* Logger para debugear PHP de una manera mas eficiente
* @author Diego de León, Luis de Haro, Alberto Leal
*/

class Logger{

  # nuestro archivo de log
  public $logFile = 'logger.log';

  # niveles de logs para pintar puede ser array o string valores aceptados 'DEBUG','INFO','ERROR','QUERY'
  public $logLevels = array('DEBUG','INFO','ERROR','QUERY');

  # variable para comprobar si la configuración del logger es valida
  private $validConfig = false;

  # variable para almacenar el ultimo mensaje
  private $lastMessage = '';

  # metodo para verificar si la configuracion es valida y si se cuenta con los permisos adecuados, iniciamos el logger
  public function setConfig(){

    $logDir = pathinfo($this->logFile, PATHINFO_DIRNAME);

    if(is_dir($logDir) && is_writable($logDir) && !is_dir($this->logFile)){

      if(file_exists($this->logFile)){
        $this->compressFile($this->logFile);
      }

      if(!is_array($this->logLevels)){
        $this->logLevels = array($this->logLevels);
      }

      $this->validConfig = true;
      $this->startLogger();
    }
  }

  /**
  * metodo para mandar imprimir mensaje de tipo DEBUG siempre y cuando esté en los logLevels
  * @param $message: variable de tipo string que indica el mensaje a imprimir
  */
  public function debug($message){
    if(in_array('DEBUG', $this->logLevels)){
      $this->printMessage('DEBUG',$message);
    }
  }

  /**
  * metodo para mandar imprimir mensaje de tipo INFO siempre y cuando esté en los logLevels
  * @param $message: variable de tipo string que indica el mensaje a imprimir
  */
  public function info($message){
    if(in_array('INFO', $this->logLevels)){
      $this->printMessage('INFO',$message);
    }
  }

  /**
  * metodo para mandar imprimir mensaje de tipo INFO siempre y cuando esté en los logLevels
  * @param $message: variable de tipo string que indica el mensaje a imprimir
  * @param $type: variable de tipo string que indica el tipo de error
  */
  public function error($message, $type='ERROR'){
    if(in_array('ERROR', $this->logLevels)){
      $this->printMessage($type,$message);
    }
  }

  /**
  * metodo para mandar imprimir mensaje de tipo QUERY siempre y cuando esté en los logLevels
  * @param $message: variable de tipo string que indica el mensaje a imprimir
  */
  public function query($message){
    if(in_array('QUERY', $this->logLevels)){
      $this->printMessage('QUERY',$message);
    }
  }

  /**
  * metodo para mandar imprimir alguna estructura como tipo DEBUG siempre y cuando esté en los logLevels
  * @param $message: variable de tipo string que indica el mensaje a imprimir
  */
  public function print_r($message){
    if(in_array('DEBUG', $this->logLevels)){
      $this->printMessage('DEBUG',print_r($message,true));
    }
  }

  /**
  * metodo para obtener cadena con el formato de log
  * @param $type: variable de tipo string que indica el tipo de log
  * @param $message: variable de tipo string que indica el mensaje a imprimir
  * @return cadena con el formato de log
  */
  public function getLogFormat($type, $message){
    return "[$type] -> $message ";
  }

  /**
  * metodo para imprimir mensaje en log
  * @param $type: variable de tipo string que indica el tipo de log
  * @param $message: variable de tipo string que indica el mensaje a imprimir
  */
  public function printMessage($type, $message){
    if($message != $this->lastMessage && $this->validConfig === true){
      $this->lastMessage = $message;
      $logFormat = $this->getLogFormat($type, $message);
      error_log($logFormat);
    }
  }

  /**
  * metodo para obtener cadena con el formato de error
  * @param $message: variable de tipo string que indica el mensaje de error
  * @param $file: variable de tipo string que indica la ruta del archivo con error
  * @param $line: variable de tipo int que indica la linea del archivo con error
  * @return cadena con el formato para error
  */
  public function getErrorMessage($message, $file, $line){
    return "$message in $file on line $line";
  }

  /**
  * nuestro manejador de errores, cacha los errores y los manda imprimir
  * @param $type: tipo de error
  * @param $message: mensaje de error
  * @param $file: archivo de error
  * @param $line: linea del archivo de error
  */
  public function error_handler($type, $message, $file, $line){

    if(!$type){
      return false;
    }

    switch($type){
      case E_ERROR:              $type = 'E_ERROR';             break;
      case E_WARNING:            $type = 'E_WARNING';           break;
      case E_PARSE:              $type = 'E_PARSE';             break;
      case E_NOTICE:             $type = 'E_NOTICE';            break;
      case E_CORE_ERROR:         $type = 'E_CORE_ERROR';        break;
      case E_CORE_WARNING:       $type = 'E_CORE_WARNING';      break;
      case E_COMPILE_ERROR:      $type = 'E_COMPILE_ERROR';     break;
      case E_COMPILE_WARNING:    $type = 'E_COMPILE_WARNING';   break;
      case E_USER_ERROR:         $type = 'E_USER_ERROR';        break;
      case E_USER_WARNING:       $type = 'E_USER_WARNING';      break;
      case E_USER_NOTICE:        $type = 'E_USER_NOTICE';       break;
      case E_STRICT:             $type = 'E_STRICT';            break;
      case E_RECOVERABLE_ERROR:  $type = 'E_RECOVERABLE_ERROR'; break;
      case E_DEPRECATED:         $type = 'E_DEPRECATED';        break;
      case E_USER_DEPRECATED:    $type = 'E_USER_DEPRECATED';   break;
      case 'EXCEPTION':          $type = 'EXCEPTION';           break;
      default:                   $type = 'UNKNOWN';
    }
    $message = $this->getErrorMessage($message, $file, $line);
    $this->error($message, $type);
  }

  /**
  * nuestro manejador de excepciones, cacha excepciones y las manda al manejador de errores
  */
  public function exception_handler(Exception $e){
    $this->error_handler('EXCEPTION', $e->getMessage(), $e->getFile(), $e->getLine());
  }

  /**
  * nuestro manejador de apagado, cacha el error que causa el cierre de la aplicación y lo manda al manejador de errores
  */
  public function shutdown_handler(){
    $last_error = error_get_last();
    $this->error_handler( $last_error['type'], $last_error['message'], $last_error['file'], $last_error['line'] );
  }

  /**
  * aqui iniciamos el logger y los manejadores
  */
  public function startLogger(){
    ini_set('error_log', $this->logFile);
    set_error_handler(array($this, "error_handler"));
    set_exception_handler(array($this, 'exception_handler'));
    register_shutdown_function(array($this, 'shutdown_handler'));
  }

  /**
  * metodo para comprimir logs del dia anterior
  */
  public function compressFile($file){
    $date = date('Ymd');
    $fileDate = date ("Ymd", filemtime($file));
    if($fileDate < $date){
      $fileDir = pathinfo($file, PATHINFO_DIRNAME);
      $fileName = pathinfo($file, PATHINFO_FILENAME);
      $fileExt = pathinfo($file, PATHINFO_EXTENSION);
      $newFile = "{$fileDir}/{$fileName}_{$fileDate}.{$fileExt}";
      rename($file,$newFile);
      shell_exec("gzip -q $newFile");
      $this->info("Log compressed: $newFile");
    }
  }
}

?>
