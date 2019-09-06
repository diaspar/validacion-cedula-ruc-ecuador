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
 * Se realizan phpunit tests en format de assertions sobre los métodos
 * públicos de la clases ValidarIdentificacion().
 *
 * Los métodos públicos son: 
 *
 * validateId()
 * validateRucNaturalPerson()
 * validateRucPrivateSociety()
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
     * Tests sobre método público validateId()
     */
    public function testCedula()
    {
        // parametro vacio o sin parametro (numero ci) deben dar false
        $validateId = $this->validador->validateId('');
        $this->assertEquals($validateId, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');
        
        $validateId = $this->validador->validateId();
        $this->assertEquals($validateId, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');

        // parametro con 0 adelante pero como integer, debe dar false ya que php lo convierte a 0
        $validateId = $this->validador->validateId(0926687856);
        $this->assertEquals($validateId, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');

        // parametro debe tener solo digitos
        $validateId = $this->validador->validateId('-0926687856');
        $this->assertEquals($validateId, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado solo puede tener dígitos');

        $validateId = $this->validador->validateId('09.26687856');
        $this->assertEquals($validateId, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado solo puede tener dígitos');

        // cedula debe tener 10 caracteres exactos
        $validateId = $this->validador->validateId('0926687864777009');
        $this->assertEquals($validateId, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado debe tener 10 caracteres');

        // revisar codigo de provincia, debe estar entre 0 y 24
        $validateId = $this->validador->validateId('9926687856');
        $this->assertEquals($validateId, false);
        $this->assertEquals($this->validador->getError(), 'Codigo de Provincia (dos primeros dígitos) no deben ser mayor a 24 ni menores a 0');

        // revisar tercer digito, debe ser mayor/igual a 0 y menor a 6
        $validateId = $this->validador->validateId('0996687856');
        $this->assertEquals($validateId, false);
        $this->assertEquals($this->validador->getError(), 'Tercer dígito debe ser mayor o igual a 0 y menor a 6 para cédulas y RUC de persona natural');

        // cedula incorrecta de acuerdo a algoritmo modulo10
        $validateId = $this->validador->validateId('0926687858');
        $this->assertEquals($validateId, false);
        $this->assertEquals($this->validador->getError(), 'Dígitos iniciales no validan contra Dígito Idenficador');
    
        // revisar que cedulas correctas validen
        $validateId = $this->validador->validateId('0602910945');
        $this->assertEquals($validateId, true);
        
        $validateId = $this->validador->validateId('0926687856');
        $this->assertEquals($validateId, true);
        
        $validateId = $this->validador->validateId('0910005917');
        $this->assertEquals($validateId, true);
    }
 
    /**
     * Tests sobre método público validateRucNaturalPerson()
     */
    public function testRucPersonaNatural()
    {
        // parametro vacio o sin parametro (numero ci) deben dar false
        $validateRucNaturalPerson = $this->validador->validateRucNaturalPerson('');
        $this->assertEquals($validateRucNaturalPerson, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');
        
        $validateRucNaturalPerson = $this->validador->validateRucNaturalPerson();
        $this->assertEquals($validateRucNaturalPerson, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');

        // parametro con 0 adelante pero como integer, debe dar false ya que php lo convierte a 0
        $validateRucNaturalPerson = $this->validador->validateRucNaturalPerson(0926687856001);
        $this->assertEquals($validateRucNaturalPerson, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');

        // parametro debe tener solo digitos
        $validateRucNaturalPerson = $this->validador->validateRucNaturalPerson('-0926687856001');
        $this->assertEquals($validateRucNaturalPerson, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado solo puede tener dígitos');

        $validateRucNaturalPerson = $this->validador->validateRucNaturalPerson('09.26687856001');
        $this->assertEquals($validateRucNaturalPerson, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado solo puede tener dígitos');

        // ruc de persona natural debe tener 13 caracteres exactos
        $validateRucNaturalPerson = $this->validador->validateRucNaturalPerson('0926687864777009');
        $this->assertEquals($validateRucNaturalPerson, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado debe tener 13 caracteres');

        // revisar codigo de provincia, debe estar entre 0 y 24
        $validateRucNaturalPerson = $this->validador->validateRucNaturalPerson('9926687856001');
        $this->assertEquals($validateRucNaturalPerson, false);
        $this->assertEquals($this->validador->getError(), 'Codigo de Provincia (dos primeros dígitos) no deben ser mayor a 24 ni menores a 0');

        // revisar tercer digito, debe ser mayor/igual a 0 y menor a 6
        $validateRucNaturalPerson = $this->validador->validateRucNaturalPerson('0996687856001');
        $this->assertEquals($validateRucNaturalPerson, false);
        $this->assertEquals($this->validador->getError(), 'Tercer dígito debe ser mayor o igual a 0 y menor a 6 para cédulas y RUC de persona natural');

        // revisar que codigo de establecimiento (3 últimos dígitos) no sean menores a 1.
        $validateRucNaturalPerson = $this->validador->validateRucNaturalPerson('0926687856000');
        $this->assertEquals($validateRucNaturalPerson, false);
        $this->assertEquals($this->validador->getError(), 'Código de establecimiento no puede ser 0');

        // ruc persona natural incorrecto de acuerdo a algoritmo modulo10
        $validateRucNaturalPerson = $this->validador->validateRucNaturalPerson('0926687858001');
        $this->assertEquals($validateRucNaturalPerson, false);
        $this->assertEquals($this->validador->getError(), 'Dígitos iniciales no validan contra Dígito Idenficador');
    
        // revisar que cedulas correctas validen
        $validateRucNaturalPerson = $this->validador->validateRucNaturalPerson('0602910945001');
        $this->assertEquals($validateRucNaturalPerson, true);
        
        $validateRucNaturalPerson = $this->validador->validateRucNaturalPerson('0926687856001');
        $this->assertEquals($validateRucNaturalPerson, true);
        
        $validateRucNaturalPerson = $this->validador->validateRucNaturalPerson('0910005917001');
        $this->assertEquals($validateRucNaturalPerson, true);
    }

    /**
     * Tests sobre método público validateRucPrivateSociety()
     */
    public function testRucSociedadPrivada()
    {
        // parametro vacio o sin parametro (numero ci) deben dar false
        $validateRucPrivateSociety = $this->validador->validateRucPrivateSociety('');
        $this->assertEquals($validateRucPrivateSociety, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');
        
        $validateRucPrivateSociety = $this->validador->validateRucPrivateSociety();
        $this->assertEquals($validateRucPrivateSociety, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');

        // parametro con 0 adelante pero como integer, debe dar false ya que php lo convierte a 0
        $validateRucPrivateSociety = $this->validador->validateRucPrivateSociety(0992397535001);
        $this->assertEquals($validateRucPrivateSociety, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');

        // parametro debe tener solo digitos
        $validateRucPrivateSociety = $this->validador->validateRucPrivateSociety('-0992397535001');
        $this->assertEquals($validateRucPrivateSociety, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado solo puede tener dígitos');

        $validateRucPrivateSociety = $this->validador->validateRucPrivateSociety('099,2397535001');
        $this->assertEquals($validateRucPrivateSociety, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado solo puede tener dígitos');

        // ruc de sociedad privada debe tener 13 caracteres exactos
        $validateRucPrivateSociety = $this->validador->validateRucPrivateSociety('0992397535001998');
        $this->assertEquals($validateRucPrivateSociety, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado debe tener 13 caracteres');

        // revisar codigo de provincia, debe estar entre 0 y 24
        $validateRucPrivateSociety = $this->validador->validateRucPrivateSociety('9992397535001');
        $this->assertEquals($validateRucPrivateSociety, false);
        $this->assertEquals($this->validador->getError(), 'Codigo de Provincia (dos primeros dígitos) no deben ser mayor a 24 ni menores a 0');

        // revisar tercer digito, debe ser igual a 9
        $validateRucPrivateSociety = $this->validador->validateRucPrivateSociety('0982397535001');
        $this->assertEquals($validateRucPrivateSociety, false);
        $this->assertEquals($this->validador->getError(), 'Tercer dígito debe ser igual a 9 para sociedades privadas');

        // revisar que codigo de establecimiento (3 últimos dígitos) no sean menores a 1.
        $validateRucPrivateSociety = $this->validador->validateRucPrivateSociety('0992397535000');
        $this->assertEquals($validateRucPrivateSociety, false);
        $this->assertEquals($this->validador->getError(), 'Código de establecimiento no puede ser 0');

        // ruc sociedad privada incorrecto de acuerdo a algoritmo modulo11
        $validateRucPrivateSociety = $this->validador->validateRucPrivateSociety('0992397532001');
        $this->assertEquals($validateRucPrivateSociety, false);
        $this->assertEquals($this->validador->getError(), 'Dígitos iniciales no validan contra Dígito Idenficador');
    
        // revisar que ruc correcto valide
        $validateRucPrivateSociety = $this->validador->validateRucPrivateSociety('0992397535001');
        $this->assertEquals($validateRucPrivateSociety, true);
        
    }

    /**
     * Tests sobre método público validateRucPublicSociety()
     */
    public function testRucSociedadPublica()
    {
        // parametro vacio o sin parametro (numero ci) deben dar false
        $validateRucPublicSociety = $this->validador->validateRucPublicSociety('');
        $this->assertEquals($validateRucPublicSociety, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');
        
        $validateRucPublicSociety = $this->validador->validateRucPublicSociety();
        $this->assertEquals($validateRucPublicSociety, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');

        // parametro con 0 adelante pero como integer, debe dar false ya que php lo convierte a 0
        $validateRucPublicSociety = $this->validador->validateRucPublicSociety(0960001550001);
        $this->assertEquals($validateRucPublicSociety, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');

        // parametro debe tener solo digitos
        $validateRucPublicSociety = $this->validador->validateRucPublicSociety('-1760001550001');
        $this->assertEquals($validateRucPublicSociety, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado solo puede tener dígitos');

        $validateRucPublicSociety = $this->validador->validateRucPublicSociety('17600,01550001');
        $this->assertEquals($validateRucPublicSociety, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado solo puede tener dígitos');

        // ruc de sociedad pública debe tener 13 caracteres exactos
        $validateRucPublicSociety = $this->validador->validateRucPublicSociety('1760001550001990999');
        $this->assertEquals($validateRucPublicSociety, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado debe tener 13 caracteres');

        // revisar codigo de provincia, debe estar entre 0 y 24
        $validateRucPublicSociety = $this->validador->validateRucPublicSociety('2760001550001');
        $this->assertEquals($validateRucPublicSociety, false);
        $this->assertEquals($this->validador->getError(), 'Codigo de Provincia (dos primeros dígitos) no deben ser mayor a 24 ni menores a 0');

        // revisar tercer digito, debe ser igual a 6
        $validateRucPublicSociety = $this->validador->validateRucPublicSociety('1790001550001');
        $this->assertEquals($validateRucPublicSociety, false);
        $this->assertEquals($this->validador->getError(), 'Tercer dígito debe ser igual a 6 para sociedades públicas');

        // revisar que codigo de establecimiento (4 últimos dígitos) no sean menores a 1.
        $validateRucPublicSociety = $this->validador->validateRucPublicSociety('1760001550000');
        $this->assertEquals($validateRucPublicSociety, false);
        $this->assertEquals($this->validador->getError(), 'Código de establecimiento no puede ser 0');

        // ruc sociedad privada incorrecto de acuerdo a algoritmo modulo11
        $validateRucPublicSociety = $this->validador->validateRucPublicSociety('1760001520001');
        $this->assertEquals($validateRucPublicSociety, false);
        $this->assertEquals($this->validador->getError(), 'Dígitos iniciales no validan contra Dígito Idenficador');
    
        // revisar que ruc correcto valide
        $validateRucPublicSociety = $this->validador->validateRucPublicSociety('1760001550001');
        $this->assertEquals($validateRucPublicSociety, true);
        
    }
}
?>