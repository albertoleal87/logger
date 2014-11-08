# Logger para debugear php de una manera mas eficiente

## Funcionalidades

- Metodos para imprimir mensajes de tipo ```DEBUG``` , ```INFO``` , ```ERROR``` y ```QUERY``` (configurables).
- Manejador de errores, cacha e imprimir los errores php y excepciones.
- Mecanismo de compresión ```GZIP``` para comprimir el archivo log si su ultima modificación es anterior a la actual.
 
## ¿Como se usa? 

### Cargamos la libreria  ```logger ```
```bash
require_once('./libraries/logger.php'); 
```

### Instanciamos la clase  
```bash
$logger = new Logger(); 
```

### Seteamos el  ```logFile ``` 
La carpeta deberá tener permisos de escritura
```bash
$logger->logFile = './logs/logFile.log'; 
```
### Seteamos  ```logLevels ``` 
Los valores en el array son los tipos de mensajes que se podrán imprimir en el log
```bash
$logger->logLevels = array('DEBUG','INFO','ERROR','QUERY'); 
```
### Aqui iniciamos el  ```Logger ```
A partir de este punto podremos imprimir los mensajes
```bash
$logger->setConfig(); 
```
### Algunos ejemplos de como imprimir mensajes 
```DEBUG``` , ```INFO``` , ```ERROR``` y ```QUERY```
```bash
$logger->debug('Esto es un mensaje debug');
$logger->info('Esto es un mensaje info');
$logger->error('Esto es un mensaje error');
$logger->query('Esto es un query');
$logger->debug('A continuación un print_r de logger');
$logger->print_r($logger);
```

### Manejador de errores 
Causamos errores ```php``` a proposito y se imprimen siempre y cuando tengamos  ```ERROR ``` en el array de  ```logLevels```
```bash
trigger_error ( 'Esto es un error de tipo E_USER_ERROR' , E_USER_ERROR );
trigger_error ( 'Esto es un error de tipo E_USER_WARNING' , E_USER_WARNING );
trigger_error ( 'Esto es un error de tipo E_USER_NOTICE' , E_USER_NOTICE );
$objeto->propiedad = $variableNoDefinida; 
```

### Manejador de excepciones
Causamos una excepcion a proposito y logger la imprime
```bash
throw new Exception('Esta es una Exception');
```

### Asi es como se verá nuestro archivo ```logFile.log```
```bash
[07-Nov-2014 15:46:01 America/Monterrey] [DEBUG] -> Esto es un mensaje debug 
[07-Nov-2014 15:46:01 America/Monterrey] [INFO] -> Esto es un mensaje info 
[07-Nov-2014 15:46:01 America/Monterrey] [ERROR] -> Esto es un mensaje error 
[07-Nov-2014 15:46:01 America/Monterrey] [QUERY] -> Esto es un query 
[07-Nov-2014 15:46:01 America/Monterrey] [DEBUG] -> A continuación un print_r de logger 
[07-Nov-2014 15:46:01 America/Monterrey] [DEBUG] -> Logger Object
(
    [logFile] => ./logs/logFile.log
    [logLevels] => Array
        (
            [0] => DEBUG
            [1] => INFO
            [2] => ERROR
            [3] => QUERY
        )

    [validConfig:Logger:private] => 1
    [lastMessage:Logger:private] => A continuación un print_r de logger
)
 
[07-Nov-2014 15:46:01 America/Monterrey] [E_USER_ERROR] -> Esto es un error de tipo E_USER_ERROR in /Applications/MAMP/htdocs/logger/example.php on line 27 
[07-Nov-2014 15:46:01 America/Monterrey] [E_USER_WARNING] -> Esto es un error de tipo E_USER_WARNING in /Applications/MAMP/htdocs/logger/example.php on line 28 
[07-Nov-2014 15:46:01 America/Monterrey] [E_USER_NOTICE] -> Esto es un error de tipo E_USER_NOTICE in /Applications/MAMP/htdocs/logger/example.php on line 29 
[07-Nov-2014 15:46:01 America/Monterrey] [E_NOTICE] -> Undefined variable: variableNoDefinida in /Applications/MAMP/htdocs/logger/example.php on line 32 
[07-Nov-2014 15:46:01 America/Monterrey] [E_WARNING] -> Creating default object from empty value in /Applications/MAMP/htdocs/logger/example.php on line 32 
[07-Nov-2014 15:46:01 America/Monterrey] [EXCEPTION] -> Esta es una Exception in /Applications/MAMP/htdocs/logger/example.php on line 35 
```

Contribuciones
------------
* [Diego de León](https://github.com/diegolg)
* [Luis de Haro](https://github.com/deharodk)
* [Alberto Leal](https://github.com/albertoleal87)
