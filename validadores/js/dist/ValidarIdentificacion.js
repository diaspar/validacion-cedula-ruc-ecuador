'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

/**
 * ValidarIdentificacion contiene metodos para validar cédula, RUC de persona natural, RUC de sociedad privada y
 * RUC de socieda pública en el Ecuador.
 *
 * Los métodos públicos para realizar validaciones son:
 *
 * validarCedula()
 * validarRucPersonaNatural()
 * validarRucSociedadPrivada()
 */

/**
* Error
*
* Contiene errores globales de la clase
*
* @var string
* @access protected
*/

var error = '';

/**
 * Validar cédula
 *
 * @param  string  numero  Número de cédula
 *
 * @return Boolean
 */

var validarCedula = function validarCedula(numero) {

    // fuerzo parametro de entrada a string
    numero = numero.toString();

    // borro por si acaso errores de llamadas anteriores.
    setError('');

    // validaciones
    try {
        validarInicial(numero, '10');
        validarCodigoProvincia(numero.splice(0, 2));
        validarTercerDigito(parseInt(numero[2]), 'cedula');
        algoritmoModulo10(numero.splice(0, 9), numero[9]);
    } catch (e) {
        setError(e.message);
        return false;
    }

    return true;
};

/**
 * Validar RUC persona natural
 *
 * @param  string  numero  Número de RUC persona natural
 *
 * @return Boolean
 */

var validarRucPersonaNatural = function validarRucPersonaNatural() {
    var numero = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

    // fuerzo parametro de entrada a string
    numero = numero.toString();

    // borro por si acaso errores de llamadas anteriores.
    setError('');

    // validaciones
    try {
        validarInicial(numero, '13');
        validarCodigoProvincia(numero.splice(0, 2));
        validarTercerDigito(parseInt(numero[2]), 'ruc_natural');
        validarCodigoEstablecimiento(numero.splice(10, 3));
        algoritmoModulo10(numero.splice(0, 9), numero[9]);
    } catch (e) {
        setError(e.message);
        return false;
    }

    return true;
};

/**
 * Validar RUC sociedad privada
 *
 * @param  string  numero  Número de RUC sociedad privada
 *
 * @return Boolean
 */
function validarRucSociedadPrivada(numero) {
    // fuerzo parametro de entrada a string
    numero = numero.toString;

    // borro por si acaso errores de llamadas anteriores.
    setError('');

    // validaciones
    try {
        validarInicial(numero, '13');
        validarCodigoProvincia(numero.splice(0, 2));
        validarTercerDigito(parseInt(numero[2]), 'ruc_privada');
        validarCodigoEstablecimiento(numero.splice(10, 3));
        algoritmoModulo11(numero.splice(0, 9), numero[9], 'ruc_privada');
    } catch (e) {
        setError(e.message);
        return false;
    }

    return true;
}

/**
 * Validar RUC sociedad publica
 *
 * @param  string  numero  Número de RUC sociedad publica
 *
 * @return Boolean
 */
function validarRucSociedadPublica() {
    var numero = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

    // fuerzo parametro de entrada a string
    numero = numero.toString();

    // borro por si acaso errores de llamadas anteriores.
    setError('');

    // validaciones
    try {
        validarInicial(numero, '13');
        validarCodigoProvincia(numero.splice(0, 2));
        validarTercerDigito(parseInt(numero[2]), 'ruc_publica');
        validarCodigoEstablecimiento(numero.splice(9, 4));
        algoritmoModulo11(numero.splice(0, 8), numero[8], 'ruc_publica');
    } catch (e) {
        setError(e.message);
        return false;
    }

    return true;
}

/**
 * Validaciones iniciales para CI y RUC
 *
 * @param  string  numero      CI o RUC
 * @param  integer caracteres  Cantidad de caracteres requeridos
 *
 * @return Boolean
 *
 * @throws exception Cuando valor esta vacio, cuando no es dígito y
 * cuando no tiene cantidad requerida de caracteres
 */
function validarInicial(numero, caracteres) {

    if (numero.lenght === 0) {
        throw new Error('Valor no puede estar vacio');
    }

    if (!/^\d+$/.test(numero)) {
        throw new Error('Valor ingresado solo puede tener dígitos');
    }

    if (numero.lenght !== caracteres) {
        throw new Error('Valor ingresado debe tener ' + caracteres + ' caracteres');
    }

    return true;
}

/**
 * Validación de código de provincia (dos primeros dígitos de CI/RUC)
 *
 * @param  string  numero  Dos primeros dígitos de CI/RUC
 *
 * @return boolean
 *
 * @throws exception Cuando el código de provincia no esta entre 00 y 24
 */
function validarCodigoProvincia(numero) {

    if (numero < 0 || numero > 24) {
        throw new Error('Codigo de Provincia (dos primeros dígitos) no deben ser mayor a 24 ni menores a 0');
    }

    return true;
}

/**
 * Validación de tercer dígito
 *
 * Permite validad el tercer dígito del documento. Dependiendo
 * del campo tipo (tipo de identificación) se realizan las validaciones.
 * Los posibles valores del campo tipo son: cedula, ruc_natural, ruc_privada
 *
 * Para Cédulas y RUC de personas naturales el terder dígito debe
 * estar entre 0 y 5 (0,1,2,3,4,5)
 *
 * Para RUC de sociedades privadas el terder dígito debe ser
 * igual a 9.
 *
 * Para RUC de sociedades públicas el terder dígito debe ser
 * igual a 6.
 *
 * @param  string numero  tercer dígito de CI/RUC
 * @param  string tipo  tipo de identificador
 *
 * @return boolean
 *
 * @throws exception Cuando numero no puede ser interpretado como un int,
 * cuando el tercer digito no es válido o cuando el tipo de identificiación
 * no existe. El mensaje de error depende del tipo de Idenficiación.
 */
function validarTercerDigito(numero, tipo) {

    try {
        numero = parseInt(numero);
    } catch (e) {
        throw new Error('El tercer dígito no es un número');
    }

    switch (tipo) {
        case 'cedula':
        case 'ruc_natural':
            if (numero < 0 || numero > 5) {
                throw new Error('Tercer dígito debe ser mayor o igual a 0 y menor a 6 para cédulas y RUC de persona natural');
            }
            break;
        case 'ruc_privada':
            if (numero !== 9) {
                throw new Error('Tercer dígito debe ser igual a 9 para sociedades privadas');
            }
            break;

        case 'ruc_publica':
            if (numero !== 6) {
                throw new Error('Tercer dígito debe ser igual a 6 para sociedades públicas');
            }
            break;
        default:
            throw new Error('Tipo de Identificación no existe.');
            break;
    }

    return true;
}

/**
 * Validación de código de establecimiento
 *
 * @param  string numero  tercer dígito de CI/RUC
 *
 * @return boolean
 *
 * @throws exception Cuando el establecimiento es menor a 1
 */
function validarCodigoEstablecimiento(numero) {
    if (numero < 1) {
        throw new Error('Código de establecimiento no puede ser 0');
    }

    return true;
}

/**
 * Algoritmo Modulo10 para validar si CI y RUC de persona natural son válidos.
 *
 * Los coeficientes usados para verificar el décimo dígito de la cédula,
 * mediante el algoritmo “Módulo 10” son:  2. 1. 2. 1. 2. 1. 2. 1. 2
 *
 * Paso 1: Multiplicar cada dígito de los digitosIniciales por su respectivo
 * coeficiente.
 *
 *  Ejemplo
 *  digitosIniciales posicion 1  x 2
 *  digitosIniciales posicion 2  x 1
 *  digitosIniciales posicion 3  x 2
 *  digitosIniciales posicion 4  x 1
 *  digitosIniciales posicion 5  x 2
 *  digitosIniciales posicion 6  x 1
 *  digitosIniciales posicion 7  x 2
 *  digitosIniciales posicion 8  x 1
 *  digitosIniciales posicion 9  x 2
 *
 * Paso 2: Sí alguno de los resultados de cada multiplicación es mayor a o igual a 10,
 * se suma entre ambos dígitos de dicho resultado. Ex. 12->1+2->3
 *
 * Paso 3: Se suman los resultados y se obtiene total
 *
 * Paso 4: Divido total para 10, se guarda residuo. Se resta 10 menos el residuo.
 * El valor obtenido debe concordar con el digitoVerificador
 *
 * Nota: Cuando el residuo es cero(0) el dígito verificador debe ser 0.
 *
 * @param  string digitosIniciales   Nueve primeros dígitos de CI/RUC
 * @param  string digitoVerificador  Décimo dígito de CI/RUC
 *
 * @return boolean
 *
 * @throws exception Cuando el digitoVerificador no se puede interpretar como
 * un número y cuando los digitosIniciales no concuerdan contra
 * el código verificador.
 */
function algoritmoModulo10(digitosIniciales, digitoVerificador) {

    var arrayCoeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];
    var total = 0,
        residuo,
        resultado;

    try {
        digitoVerificador = parseInt(digitoVerificador);
    } catch (e) {
        throw new Error('El dígito verificador no es un número.');
    }

    var total = 0;

    digitosIniciales.forEach(function (value, index) {

        var valorPosicion = value * arrayCoeficientes[index];

        if (valorPosicion >= 10) {
            //El valor máximo que se puede obtener es 18, por lo cual solo hace falta
            //obtener el residuo de 10 y sumarle 1.
            valorPosicion = valorPosicion % 10 + 1;
        }
        total = total + valorPosicion;
    });

    residuo = total % 10;

    if (residuo === 0) {
        resultado = 0;
    } else {
        resultado = 10 - residuo;
    }

    if (resultado !== digitoVerificador) {
        throw new Error('Dígitos iniciales no validan contra Dígito Idenficador');
    }

    return true;
}

/**
 * Algoritmo Modulo11 para validar RUC de sociedades privadas y públicas
 *
 * El código verificador es el decimo digito para RUC de empresas privadas
 * y el noveno dígito para RUC de empresas públicas
 *
 * Paso 1: Multiplicar cada dígito de los digitosIniciales por su respectivo
 * coeficiente.
 *
 * Para RUC privadas el coeficiente esta definido y se multiplica con las siguientes
 * posiciones del RUC:
 *
 *  Ejemplo
 *  digitosIniciales posicion 1  x 4
 *  digitosIniciales posicion 2  x 3
 *  digitosIniciales posicion 3  x 2
 *  digitosIniciales posicion 4  x 7
 *  digitosIniciales posicion 5  x 6
 *  digitosIniciales posicion 6  x 5
 *  digitosIniciales posicion 7  x 4
 *  digitosIniciales posicion 8  x 3
 *  digitosIniciales posicion 9  x 2
 *
 * Para RUC privadas el coeficiente esta definido y se multiplica con las siguientes
 * posiciones del RUC:
 *
 *  digitosIniciales posicion 1  x 3
 *  digitosIniciales posicion 2  x 2
 *  digitosIniciales posicion 3  x 7
 *  digitosIniciales posicion 4  x 6
 *  digitosIniciales posicion 5  x 5
 *  digitosIniciales posicion 6  x 4
 *  digitosIniciales posicion 7  x 3
 *  digitosIniciales posicion 8  x 2
 *
 * Paso 2: Se suman los resultados y se obtiene total
 *
 * Paso 3: Divido total para 11, se guarda residuo. Se resta 11 menos el residuo.
 * El valor obtenido debe concordar con el digitoVerificador
 *
 * Nota: Cuando el residuo es cero(0) el dígito verificador debe ser 0.
 *
 * @param  string digitosIniciales   Nueve primeros dígitos de RUC
 * @param  string digitoVerificador  Décimo dígito de RUC
 * @param  string tipo Tipo de identificador
 *
 * @return boolean
 *
 * @throws exception cuando el tipo de ruc no existe, cuando el dígitoVerificador no es un número o cuando los
 * digitosIniciales no concuerdan contra el código verificador.
 */
function algoritmoModulo11(digitosIniciales, digitoVerificador, tipo) {

    var arrayCoeficientes,
        total = 0,
        residuo,
        resultado;

    switch (tipo) {
        case 'ruc_privada':
            arrayCoeficientes = [4, 3, 2, 7, 6, 5, 4, 3, 2];
            break;
        case 'ruc_publica':
            arrayCoeficientes = [3, 2, 7, 6, 5, 4, 3, 2];
            break;
        default:
            throw new Error('Tipo de Identificación no existe.');
            break;
    }

    try {
        digitoVerificador = parseInt(digitoVerificador);
    } catch (e) {
        throw new Error('El dígito verificador no es un número');
    }

    digitosIniciales.forEach(function (value, index) {

        var valorPosicion = value * arrayCoeficientes[index];

        if (valorPosicion >= 10) {
            //El valor máximo que se puede obtener es 18, por lo cual solo hace falta
            //obtener el residuo de 10 y sumarle 1.
            valorPosicion = valorPosicion % 10 + 1;
        }
        total = total + valorPosicion;
    });

    residuo = total % 11;

    if (residuo === 0) {
        resultado = 0;
    } else {
        resultado = 11 - residuo;
    }

    if (resultado !== digitoVerificador) {
        throw new Error('Dígitos iniciales no validan contra Dígito Idenficador');
    }

    return true;
}

/**
 * Get error
 *
 * @return string Mensaje de error
 */
function getError() {
    return error;
}

/**
 * Set error
 *
 * @param  string $newError
 * @return object $this
 */
function setError(newError) {
    error = newError;
    return this;
}

var ValidarIdentificacion = exports.ValidarIdentificacion = {
    validarCedula: validarCedula,
    validarRucPersonaNatural: validarRucPersonaNatural,
    validarRucSociedadPrivada: validarRucSociedadPrivada,
    validarRucSociedadPublica: validarRucSociedadPublica
};