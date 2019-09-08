<?php

/**
 * MIT License
 * ===========
 *
 * Copyright (c) 2012 Ing. Mauricio Lopez <mlopez@dixian.info>
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @package     ValidarIdentificacion
 * @subpackage
 * @author      Ing. Mauricio Lopez <mlopez@dixian.info>
 * @copyright   2012 Ing. Mauricio Lopez (diaspar)
 * @license     http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link        http://www.dixian.info
 * @version     @@0.8@@
 */

/**
 * ValidarIdentificacion contiene métodos para validar cédula, RUC de persona natural, RUC de sociedad privada y
 * RUC de sociedad pública en el Ecuador.
 *
 * Los métodos públicos para realizar validaciones son:
 *
 * validarCedula()
 * validarRucPersonaNatural()
 * validarRucSociedadPrivada()
 */
class ValidarIdentificacion
{

    /**
     * Error
     *
     * Contiene errores globales de la clase
     *
     * @var string
     * @access protected
     */
    protected $error = '';

    /**
     * Validar cédula
     *
     * @param  string  $numero  Número de cédula.
     *
     * @return Boolean
     */
    public function validarCedula($numero = '')
    {
        // Se fuerza que el parametro de entrada sea string.
        $numero = (string)$numero;

        // Se borran, por si acaso, errores de llamadas anteriores.
        $this->setError('');

        // validaciones
        try {
            $this->validarInicial($numero, '10');
            $this->validarCodigoProvincia(substr($numero, 0, 2));
            $this->validarTercerDigito($numero[2], 'cedula');
            $this->algoritmoModulo10(substr($numero, 0, 9), $numero[9]);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * Validar RUC persona natural
     *
     * @param  string  $numero  Número de RUC persona natural.
     *
     * @return Boolean
     */
    public function validarRucPersonaNatural($numero = '')
    {
        // Se fuerza que el parametro de entrada sea string.
        $numero = (string)$numero;

        // Se borran, por si acaso, errores de llamadas anteriores.
        $this->setError('');

        // validaciones
        try {
            $this->validarInicial($numero, '13');
            $this->validarCodigoProvincia(substr($numero, 0, 2));
            $this->validarTercerDigito($numero[2], 'ruc_natural');
            $this->validarCodigoEstablecimiento(substr($numero, 10, 3));
            $this->algoritmoModulo10(substr($numero, 0, 9), $numero[9]);
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }

        return true;
    }


    /**
     * Validar RUC sociedad privada
     *
     * @param  string  $numero  Número de RUC sociedad privada
     *
     * @return Boolean
     */
    public function validarRucSociedadPrivada($numero = '')
    {
        // Se fuerza que el parametro de entrada sea string.
        $numero = (string)$numero;

        // Se borran, por si acaso, errores de llamadas anteriores.
        $this->setError('');

        // validaciones
        try {
            $this->validarInicial($numero, '13');
            $this->validarCodigoProvincia(substr($numero, 0, 2));
            $this->validarTercerDigito($numero[2], 'ruc_privada');
            $this->validarCodigoEstablecimiento(substr($numero, 10, 3));
            $this->algoritmoModulo11(substr($numero, 0, 9), $numero[9], 'ruc_privada');
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * Validar RUC sociedad publica
     *
     * @param  string  $numero  Número de RUC sociedad publica.
     *
     * @return Boolean
     */
    public function validarRucSociedadPublica($numero = '')
    {
        // Se fuerza que el parametro de entrada sea string.
        $numero = (string)$numero;

        // Se borran, por si acaso, errores de llamadas anteriores.
        $this->setError('');

        // validaciones
        try {
            $this->validarInicial($numero, '13');
            $this->validarCodigoProvincia(substr($numero, 0, 2));
            $this->validarTercerDigito($numero[2], 'ruc_publica');
            $this->validarCodigoEstablecimiento(substr($numero, 9, 4));
            $this->algoritmoModulo11(substr($numero, 0, 8), $numero[8], 'ruc_publica');
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * Validaciones iniciales para CI y RUC
     *
     * @param  string  $numero      CI o RUC
     * @param  integer $caracteres  Cantidad de caracteres requeridos
     *
     * @return Boolean
     *
     * @throws exception Cuando valor está vacío, cuando no es dígito y
     * cuando no tiene cantidad requerida de caracteres
     */
    protected function validarInicial($numero, $caracteres)
    {
        if (empty($numero)) {
            throw new Exception('Valor no puede estar vacío');
        }

        if (!ctype_digit($numero)) {
            throw new Exception('Valor ingresado solo puede tener dígitos');
        }

        if (strlen($numero) != $caracteres) {
            throw new Exception('Valor ingresado debe tener '.$caracteres.' caracteres');
        }

        return true;
    }

    /**
     * Validación de código de provincia (dos primeros dígitos de CI/RUC)
     *
     * @param  string  $numero  Dos primeros dígitos de CI/RUC
     *
     * @return boolean
     *
     * @throws exception Cuando el código de provincia no está entre 00 y 24
     */
    protected function validarCodigoProvincia($numero)
    {
        if ($numero < 0 OR $numero > 24) {
            throw new Exception('Código de Provincia (dos primeros dígitos) no debe ser mayor a 24 ni menor a 0');
        }

        return true;
    }

 /**
     * Validación de tercer dígito
     *
     * Permite validar el tercer dígito del documento. Dependiendo
     * del campo tipo (tipo de identificación) se realizan las validaciones.
     * Los posibles valores del campo tipo son: cédula, ruc_natural, ruc_privada.
     *
     * Para Cédulas y RUC de personas naturales el tercer dígito debe
     * estar entre 0 y 5 (0,1,2,3,4,5)
     *
     * Para RUC de sociedades privadas el tercer dígito debe ser
     * igual a 9.
     *
     * Para RUC de sociedades públicas el tercer dígito debe ser 
     * igual a 6.
     *
     * @param  string $numero  tercer dígito de CI/RUC
     * @param  string $tipo  tipo de identificación
     *
     * @return boolean
     *
     * @throws exception Cuando el tercer dígito no es válido. El mensaje
     * de error depende del tipo de Identificación.
     */
    protected function validarTercerDigito($numero, $tipo)
    {
        switch ($tipo) {
            case 'cedula':
            case 'ruc_natural':
                if ($numero < 0 OR $numero > 5) {
                    throw new Exception('Tercer dígito debe ser mayor o igual a 0 y menor a 6 para cédulas y RUC de persona natural');
                }
                break;
            case 'ruc_privada':
                if ($numero != 9) {
                    throw new Exception('Tercer dígito debe ser igual a 9 para sociedades privadas');
                }
                break;

            case 'ruc_publica':
                if ($numero != 6) {
                    throw new Exception('Tercer dígito debe ser igual a 6 para sociedades públicas');
                }
                break;
            default:
                throw new Exception('Tipo de Identificación no existe.');
                break;
        }

        return true;
    }

    /**
     * Validación de código de establecimiento
     *
     * @param  string $numero  tercer dígito de CI/RUC
     *
     * @return boolean
     *
     * @throws exception Cuando el código de establecimiento es menor a 1
     */
    protected function validarCodigoEstablecimiento($numero)
    {
        if ($numero < 1) {
            throw new Exception('Código de establecimiento no puede ser 0');
        }

        return true;
    }

   /**
     * Algoritmo Modulo10 para validar si CI y RUC de persona natural son válidos.
     *
     * Los coeficientes usados para verificar el décimo dígito de la cédula,
     * mediante el algoritmo “Módulo 10” son:  2. 1. 2. 1. 2. 1. 2. 1. 2
     *
     * Pasos del algoritmo:
     * 
     * Paso 1: Multiplicar cada dígito de los digitosIniciales por su respectivo
     * coeficiente. Tal como se observa a continuación:
     *
     *  digitosIniciales posición 1  x 2
     *  digitosIniciales posición 2  x 1
     *  digitosIniciales posición 3  x 2
     *  digitosIniciales posición 4  x 1
     *  digitosIniciales posición 5  x 2
     *  digitosIniciales posición 6  x 1
     *  digitosIniciales posición 7  x 2
     *  digitosIniciales posición 8  x 1
     *  digitosIniciales posición 9  x 2
     *
     * Paso 2: Si alguno de los resultados de cada multiplicación es mayor o igual a 10,
     * los dígitos de dicho resultado se suman. Ejemplo: 12->1+2->3
     *
     * Paso 3: Se suman los resultados y se obtiene el total.
     *
     * Paso 4: Se divide el total para 10 y se guarda el residuo ($residuo = $total%10). Luego se resta
     * de 10 el residuo (10 - $residuo). El valor obtenido debe concordar con el digitoVerificador.
     *
     * Nota: Cuando el residuo es cero(0) el dígito verificador debe ser 0.
     *
     * @param  string $digitosIniciales   Nueve primeros dígitos de CI/RUC
     * @param  string $digitoVerificador  Décimo dígito de CI/RUC
     *
     * @return boolean
     *
     * @throws exception Cuando los digitosIniciales no concuerdan con
     * el dígito verificador.
     */
    protected function algoritmoModulo10($digitosIniciales, $digitoVerificador)
    {
        $arrayCoeficientes = array(2,1,2,1,2,1,2,1,2);

        $digitoVerificador = (int)$digitoVerificador;
        $digitosIniciales = str_split($digitosIniciales);

        $total = 0;
        foreach ($digitosIniciales as $key => $value) {

            $valorPosicion = ( (int)$value * $arrayCoeficientes[$key] );

            if ($valorPosicion >= 10) {
                $valorPosicion = str_split($valorPosicion);
                $valorPosicion = array_sum($valorPosicion);
                $valorPosicion = (int)$valorPosicion;
            }

            $total = $total + $valorPosicion;
        }

        $residuo =  $total % 10;

        if ($residuo == 0) {
            $resultado = 0;
        } else {
            $resultado = 10 - $residuo;
        }

        if ($resultado != $digitoVerificador) {
            throw new Exception('Dígitos iniciales no concuerdan con el Dígito Verificador');
        }

        return true;
    }

    /**
     * Algoritmo Modulo11 para validar RUC de sociedades privadas y públicas
     *
     * El código verificador es el decimo dígito para RUC de empresas privadas
     * y el noveno dígito para RUC de empresas públicas
     * 
     * Paso 1: Multiplicar cada dígito de los digitosIniciales por su respectivo
     * coeficiente.
     * 
     * RUC SOCIEDADES PRIVADAS:
     * 
     * Los coeficientes usados para verificar el décimo dígito del RUC de sociedades privadas,
     * mediante el algoritmo “Módulo 11”, son: 4. 3. 2. 7. 6. 5. 4. 3. 2.
     *
     * Para RUC privadas, la multiplicación entre posiciones y coeficientes se realiza tal
     * como se observa a continuación:
     *
     *  digitosIniciales posición 1  x 4
     *  digitosIniciales posición 2  x 3
     *  digitosIniciales posición 3  x 2
     *  digitosIniciales posición 4  x 7
     *  digitosIniciales posición 5  x 6
     *  digitosIniciales posición 6  x 5
     *  digitosIniciales posición 7  x 4
     *  digitosIniciales posición 8  x 3
     *  digitosIniciales posición 9  x 2
     * 
     * RUC SOCIEDADES PÚBLICAS:
     *
     * Los coeficientes usados para verificar el décimo dígito del RUC de sociedades públicas,
     * mediante el algoritmo “Módulo 11”, son: 3. 2. 7. 6. 5. 4. 3. 2.
     *
     * Para RUC públicas, la multiplicación entre posiciones y coeficientes se realiza tal
     * como se observa a continuación:
     *
     *  digitosIniciales posición 1  x 3
     *  digitosIniciales posición 2  x 2
     *  digitosIniciales posición 3  x 7
     *  digitosIniciales posición 4  x 6
     *  digitosIniciales posición 5  x 5
     *  digitosIniciales posición 6  x 4
     *  digitosIniciales posición 7  x 3
     *  digitosIniciales posición 8  x 2
     *
     * Paso 2: Se suman los resultados de las multiplicaciones y se obtiene el total.
     *
     * Paso 3: Se divide el total para 11 y se guarda el residuo. Luego se resta
     *  de 11 el residuo (11 - residuo). El valor obtenido debe concordar con el digitoVerificador.
     *
     * Nota: Cuando el residuo es cero(0) el dígito verificador debe ser 0.
     *
     * @param  string $digitosIniciales   Nueve primeros dígitos de RUC
     * @param  string $digitoVerificador  Décimo dígito de RUC
     * @param  string $tipo Tipo de identificador
     *
     * @return boolean
     *
     * @throws exception Cuando los digitosIniciales no concuerdan con
     * el dígito verificador.
     */
    protected function algoritmoModulo11($digitosIniciales, $digitoVerificador, $tipo)
    {
        switch ($tipo) {
            case 'ruc_privada':
                $arrayCoeficientes = array(4, 3, 2, 7, 6, 5, 4, 3, 2);
                break;
            case 'ruc_publica':
                $arrayCoeficientes = array(3, 2, 7, 6, 5, 4, 3, 2);
                break;
            default:
                throw new Exception('Tipo de Identificación no existe.');
                break;
        }

        $digitoVerificador = (int)$digitoVerificador;
        $digitosIniciales = str_split($digitosIniciales);

        $total = 0;
        foreach ($digitosIniciales as $key => $value) {
            $valorPosicion = ( (int)$value * $arrayCoeficientes[$key] );
            $total = $total + $valorPosicion;
        }

        $residuo =  $total % 11;

        if ($residuo == 0) {
            $resultado = 0;
        } else {
            $resultado = 11 - $residuo;
        }

        if ($resultado != $digitoVerificador) {
            throw new Exception('Dígitos iniciales no concuerdan con el Dígito Verificador');
        }

        return true;
    }

    /**
     * Get error
     *
     * @return string Mensaje de error
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Set error
     *
     * @param  string $newError
     * @return object $this
     */
    public function setError($newError)
    {
        $this->error = $newError;
        return $this;
    }
}
?>
