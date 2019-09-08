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
 * @include ValidarIdenfiticacion() file.
 */
require('validadores/php/validaridentificacion.php');


/**
 * Clase para realizar phpunit tests en la clase ValidarIdentificacion()
 *
 * Se realizan phpunit tests en forma de assertions sobre los métodos
 * públicos de la clases ValidarIdentificacion().
 *
 * Los métodos públicos son: 
 *
 * validarCedula()
 * validarRucPersonaNatural()
 * validarRucSociedadPrivada()
 */
class ValidarIdenfiticacionTest extends PHPUnit_Framework_TestCase
{
    /**
     * Validador
     * 
     * Guarda Instancia de clase ValidarIdentificacion() disponible para todos los métodos
     *
     * @var string
     * @access protected
     */
    protected $validador;
 
    /**
     * Inicio objecto ValidarIdentificacion() 
     */
    protected function setUp()
    {
        $this->validador = new ValidarIdentificacion();
    }

    /**
     * Tests sobre método público validarCedula()
     */
    public function testCedula()
    {
        // si el método recibe un parámetro vacío (numero ci) debe arrojar false
        $validarCedula = $this->validador->validarCedula('');
        $this->assertEquals($validarCedula, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacío');
        
        // si el método no recibe un parámetro(numero ci) debe arrojar false
        $validarCedula = $this->validador->validarCedula();
        $this->assertEquals($validarCedula, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacío');

        // parámetro con 0 adelante pero como integer, debe dar false ya que php lo convierte a 0
        $validarCedula = $this->validador->validarCedula(0926687856);
        $this->assertEquals($validarCedula, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacío');

        // parámetro debe tener solo dígitos
        $validarCedula = $this->validador->validarCedula('-0926687856');
        $this->assertEquals($validarCedula, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado solo puede tener dígitos');

        $validarCedula = $this->validador->validarCedula('09.26687856');
        $this->assertEquals($validarCedula, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado solo puede tener dígitos');

        // cédula debe tener 10 caracteres exactos
        $validarCedula = $this->validador->validarCedula('0926687864777009');
        $this->assertEquals($validarCedula, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado debe tener 10 caracteres');

        // revisar código de provincia, debe estar entre 0 y 24
        $validarCedula = $this->validador->validarCedula('9926687856');
        $this->assertEquals($validarCedula, false);
        $this->assertEquals($this->validador->getError(), 'Código de Provincia (dos primeros dígitos) no debe ser mayor a 24 ni menor a 0');

        // revisar tercer dígito, debe ser mayor/igual a 0 y menor a 6
        $validarCedula = $this->validador->validarCedula('0996687856');
        $this->assertEquals($validarCedula, false);
        $this->assertEquals($this->validador->getError(), 'Tercer dígito debe ser mayor o igual a 0 y menor a 6 para cédulas y RUC de persona natural');

        // cédula incorrecta de acuerdo a algoritmo modulo10
        $validarCedula = $this->validador->validarCedula('0926687858');
        $this->assertEquals($validarCedula, false);
        $this->assertEquals($this->validador->getError(), 'Dígitos iniciales no concuerdan con el Dígito Verificador');
    
        // revisar que cédulas correctas validen
        $validarCedula = $this->validador->validarCedula('0602910945');
        $this->assertEquals($validarCedula, true);
        
        $validarCedula = $this->validador->validarCedula('0926687856');
        $this->assertEquals($validarCedula, true);
        
        $validarCedula = $this->validador->validarCedula('0910005917');
        $this->assertEquals($validarCedula, true);
    }
 
    /**
     * Tests sobre método público validarRucPersonaNatural()
     */
    public function testRucPersonaNatural()
    {
        // parametro vacío o sin parámetro (numero ci) deben dar false
        $validarRucPersonaNatural = $this->validador->validarRucPersonaNatural('');
        $this->assertEquals($validarRucPersonaNatural, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacío');
        
        $validarRucPersonaNatural = $this->validador->validarRucPersonaNatural();
        $this->assertEquals($validarRucPersonaNatural, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacío');

        // parámetro con 0 adelante pero como integer, debe dar false ya que php lo convierte a 0
        $validarRucPersonaNatural = $this->validador->validarRucPersonaNatural(0926687856001);
        $this->assertEquals($validarRucPersonaNatural, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacío');

        // parámetro debe tener solo dígitos
        $validarRucPersonaNatural = $this->validador->validarRucPersonaNatural('-0926687856001');
        $this->assertEquals($validarRucPersonaNatural, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado solo puede tener dígitos');

        $validarRucPersonaNatural = $this->validador->validarRucPersonaNatural('09.26687856001');
        $this->assertEquals($validarRucPersonaNatural, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado solo puede tener dígitos');

        // ruc de persona natural debe tener 13 caracteres exactos
        $validarRucPersonaNatural = $this->validador->validarRucPersonaNatural('0926687864777009');
        $this->assertEquals($validarRucPersonaNatural, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado debe tener 13 caracteres');

        // revisar código de provincia, debe estar entre 0 y 24
        $validarRucPersonaNatural = $this->validador->validarRucPersonaNatural('9926687856001');
        $this->assertEquals($validarRucPersonaNatural, false);
        $this->assertEquals($this->validador->getError(), 'Código de Provincia (dos primeros dígitos) no debe ser mayor a 24 ni menor a 0');

        // revisar tercer dígito, debe ser mayor/igual a 0 y menor a 6
        $validarRucPersonaNatural = $this->validador->validarRucPersonaNatural('0996687856001');
        $this->assertEquals($validarRucPersonaNatural, false);
        $this->assertEquals($this->validador->getError(), 'Tercer dígito debe ser mayor o igual a 0 y menor a 6 para cédulas y RUC de persona natural');

        // revisar que código de establecimiento (3 últimos dígitos) no sean menores a 1.
        $validarRucPersonaNatural = $this->validador->validarRucPersonaNatural('0926687856000');
        $this->assertEquals($validarRucPersonaNatural, false);
        $this->assertEquals($this->validador->getError(), 'Código de establecimiento no puede ser 0');

        // ruc persona natural incorrecto de acuerdo a algoritmo modulo10
        $validarRucPersonaNatural = $this->validador->validarRucPersonaNatural('0926687858001');
        $this->assertEquals($validarRucPersonaNatural, false);
        $this->assertEquals($this->validador->getError(), 'Dígitos iniciales no concuerdan con el Dígito Verificador');
    
        // revisar que cédulas correctas validen
        $validarRucPersonaNatural = $this->validador->validarRucPersonaNatural('0602910945001');
        $this->assertEquals($validarRucPersonaNatural, true);
        
        $validarRucPersonaNatural = $this->validador->validarRucPersonaNatural('0926687856001');
        $this->assertEquals($validarRucPersonaNatural, true);
        
        $validarRucPersonaNatural = $this->validador->validarRucPersonaNatural('0910005917001');
        $this->assertEquals($validarRucPersonaNatural, true);
    }

    /**
     * Tests sobre método público validarRucSociedadPrivada()
     */
    public function testRucSociedadPrivada()
    {
        // parametro vacío o sin parametro (numero ci) deben dar false
        $validarRucSociedadPrivada = $this->validador->validarRucSociedadPrivada('');
        $this->assertEquals($validarRucSociedadPrivada, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacío');
        
        $validarRucSociedadPrivada = $this->validador->validarRucSociedadPrivada();
        $this->assertEquals($validarRucSociedadPrivada, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacío');

        // parámetro con 0 adelante pero como integer, debe dar false ya que php lo convierte a 0
        $validarRucSociedadPrivada = $this->validador->validarRucSociedadPrivada(0992397535001);
        $this->assertEquals($validarRucSociedadPrivada, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacío');

        // parámetro debe tener solo dígitos
        $validarRucSociedadPrivada = $this->validador->validarRucSociedadPrivada('-0992397535001');
        $this->assertEquals($validarRucSociedadPrivada, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado solo puede tener dígitos');

        $validarRucSociedadPrivada = $this->validador->validarRucSociedadPrivada('099,2397535001');
        $this->assertEquals($validarRucSociedadPrivada, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado solo puede tener dígitos');

        // ruc de sociedad privada debe tener 13 caracteres exactos
        $validarRucSociedadPrivada = $this->validador->validarRucSociedadPrivada('0992397535001998');
        $this->assertEquals($validarRucSociedadPrivada, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado debe tener 13 caracteres');

        // revisar código de provincia, debe estar entre 0 y 24
        $validarRucSociedadPrivada = $this->validador->validarRucSociedadPrivada('9992397535001');
        $this->assertEquals($validarRucSociedadPrivada, false);
        $this->assertEquals($this->validador->getError(), 'Código de Provincia (dos primeros dígitos) no debe ser mayor a 24 ni menor a 0');

        // revisar tercer dígito, debe ser igual a 9
        $validarRucSociedadPrivada = $this->validador->validarRucSociedadPrivada('0982397535001');
        $this->assertEquals($validarRucSociedadPrivada, false);
        $this->assertEquals($this->validador->getError(), 'Tercer dígito debe ser igual a 9 para sociedades privadas');

        // revisar que código de establecimiento (3 últimos dígitos) no sean menores a 1.
        $validarRucSociedadPrivada = $this->validador->validarRucSociedadPrivada('0992397535000');
        $this->assertEquals($validarRucSociedadPrivada, false);
        $this->assertEquals($this->validador->getError(), 'Código de establecimiento no puede ser 0');

        // ruc sociedad privada incorrecto de acuerdo a algoritmo modulo11
        $validarRucSociedadPrivada = $this->validador->validarRucSociedadPrivada('0992397532001');
        $this->assertEquals($validarRucSociedadPrivada, false);
        $this->assertEquals($this->validador->getError(), 'Dígitos iniciales no concuerdan con el Dígito Verificador');
    
        // revisar que ruc correcto valide
        $validarRucSociedadPrivada = $this->validador->validarRucSociedadPrivada('0992397535001');
        $this->assertEquals($validarRucSociedadPrivada, true);
        
    }

    /**
     * Tests sobre método público validarRucSociedadPublica()
     */
    public function testRucSociedadPublica()
    {
        // parámetro vacío o sin parámetro (numero ci) deben dar false
        $validarRucSociedadPublica = $this->validador->validarRucSociedadPublica('');
        $this->assertEquals($validarRucSociedadPublica, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacío');
        
        $validarRucSociedadPublica = $this->validador->validarRucSociedadPublica();
        $this->assertEquals($validarRucSociedadPublica, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacío');

        // parámetro con 0 adelante pero como integer, debe dar false ya que php lo convierte a 0
        $validarRucSociedadPublica = $this->validador->validarRucSociedadPublica(0960001550001);
        $this->assertEquals($validarRucSociedadPublica, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacío');

        // parámetro debe tener solo dígitos
        $validarRucSociedadPublica = $this->validador->validarRucSociedadPublica('-1760001550001');
        $this->assertEquals($validarRucSociedadPublica, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado solo puede tener dígitos');

        $validarRucSociedadPublica = $this->validador->validarRucSociedadPublica('17600,01550001');
        $this->assertEquals($validarRucSociedadPublica, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado solo puede tener dígitos');

        // ruc de sociedad pública debe tener 13 caracteres exactos
        $validarRucSociedadPublica = $this->validador->validarRucSociedadPublica('1760001550001990999');
        $this->assertEquals($validarRucSociedadPublica, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado debe tener 13 caracteres');

        // revisar código de provincia, debe estar entre 0 y 24
        $validarRucSociedadPublica = $this->validador->validarRucSociedadPublica('2760001550001');
        $this->assertEquals($validarRucSociedadPublica, false);
        $this->assertEquals($this->validador->getError(), 'Código de Provincia (dos primeros dígitos) no debe ser mayor a 24 ni menor a 0');

        // revisar tercer dígito, debe ser igual a 6
        $validarRucSociedadPublica = $this->validador->validarRucSociedadPublica('1790001550001');
        $this->assertEquals($validarRucSociedadPublica, false);
        $this->assertEquals($this->validador->getError(), 'Tercer dígito debe ser igual a 6 para sociedades públicas');

        // revisar que codigo de establecimiento (4 últimos dígitos) no sean menores a 1.
        $validarRucSociedadPublica = $this->validador->validarRucSociedadPublica('1760001550000');
        $this->assertEquals($validarRucSociedadPublica, false);
        $this->assertEquals($this->validador->getError(), 'Código de establecimiento no puede ser 0');

        // ruc sociedad privada incorrecto de acuerdo a algoritmo modulo11
        $validarRucSociedadPublica = $this->validador->validarRucSociedadPublica('1760001520001');
        $this->assertEquals($validarRucSociedadPublica, false);
        $this->assertEquals($this->validador->getError(), 'Dígitos iniciales no concuerdan con el Dígito Verificador');
    
        // revisar que ruc correcto valide
        $validarRucSociedadPublica = $this->validador->validarRucSociedadPublica('1760001550001');
        $this->assertEquals($validarRucSociedadPublica, true);
        
    }
}
?>