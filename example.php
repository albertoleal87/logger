<?php

// Primero cargamos la libreria logger
require_once('./libraries/logger.php'); 

// Instanciamos la clase Logger
$logger = new Logger(); 

// Seteamos el logFile (la carpeta logs debe tener permisos de escritura)
$logger->logFile = './logs/logFile.log'; 

// Seteamos logLevels (los valores en el array son los tipos de log que se podrán imprimir)
$logger->logLevels = array('DEBUG','INFO','ERROR','QUERY'); 

// Aqui arrancamos el logger
$logger->setConfig(); 

// Algunos ejemplos de como usar logger
$logger->debug('Esto es un mensaje debug');
$logger->info('Esto es un mensaje info');
$logger->error('Esto es un mensaje error');
$logger->query('Esto es un query');
$logger->debug('A continuación un print_r de logger');
$logger->print_r($logger);

// Logger tiene un manejador de errores 
trigger_error ( 'Esto es un error de tipo E_USER_ERROR' , E_USER_ERROR );
trigger_error ( 'Esto es un error de tipo E_USER_WARNING' , E_USER_WARNING );
trigger_error ( 'Esto es un error de tipo E_USER_NOTICE' , E_USER_NOTICE );

// Esto es a proposito
$objeto->propiedad = $variableNoDefinida; 

// Tambien cacha excepciones
throw new Exception('Esta es una Exception');

?>
