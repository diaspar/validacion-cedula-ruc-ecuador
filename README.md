ValidadorIdentificacion
=============================


Introducción
-------------

Básicamente la Clase ValidadorIdentificacion permitirá tener una clase para validar Cédula y RUC del Ecuador. Se busca llegar a 
los lenguajes más populares: php, js, java, .net (vb, c-sharp), ruby.

Ya la clase inicial creada en php permite validar cédula, RUC de persona natural y RUC de sociedad privada y públicas

El siguiente link permitira conocer un poco más de la matematica asociada:

http://www.icaiza.com/blog/programacion/como-validar-el-digito-verificador-de-la-cedula-ecuatoriana

Adjuntamos también un documento teórico ([instructivo.pdf](https://github.com/diaspar/validacion-cedula-ruc-ecuador/blob/master/instructivo.pdf) - página 36 a la 40)

El proyecto cuenta con documentación automática creada por phpdocumentor en el folder docs/


Uso
----

- Incluir la clase en el proyecto PHP deseado.
- Instanciar la clase y llamar al metodo para validar la identificación

```
require('validadores/php/validaridentificacion.php');

// Crear nuevo objecto
$validador = new ValidarIdentificacion();

// validar CI
if ($validador->validarCedula('0926687856')) {
    echo 'Cédula válida';
} else {
    echo 'Cédula incorrecta: '.$validador->getMessage();
}

// validar RUC persona natural
if ($validador->validarRucPersonaNatural('0926687856001')) {
    echo 'RUC válido';
} else {
    echo 'RUC incorrecto: '.$validador->getMessage();
}

// validar RUC sociedad privada
if ($validador->validarRucSociedadPrivada('0992397535001')) {
    echo 'RUC válido';
} else {
    echo 'RUC incorrecto: '.$validador->getMessage();
}

// validar RUC sociedad ublica
if ($validador->validarRucSociedadPublica('1760001550001')) {
    echo 'RUC válido';
} else {
    echo 'RUC incorrecto: '.$validador->getMessage();
}
```


Tests
-------

Para ver todos los mensajes de error que provee la clase se adjunta archivo de tests.

Para poder correr los tests, instalar y usar phpunit de la siguiente forma:

phpunit --verbose  --colors ValidarIdentificacionTest.php 


Como ayudar
------------

Me gustaría contar con la ayuda de la Comunidad. Si desean pueden:

- Ver el código e indicar cualquier corrección.
- Ver y probar los tests con phpunit e indicar si pueden agregarse mas tests
- Usar la clase de php como base y crear Una clase en otro lenguaje.
- Crear tests para clases en otro lenguaje.
- Mejorar la documentacion.

Si desean pueden hacer un pull request y yo acepto sus cambios a medida que los hagan.


Contactarme
------------

Twitter: @diaspar3

Github: https://github.com/diaspar/validacion-cedula-ruc-ecuador

E-mail: mauriciolopeztam@gmail.com