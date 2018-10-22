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
 * @package     validateID
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
require('validadores/php/validateID.php');


/**
 * Clase para realizar phpunit tests en la clase validateID()
 *
 * Se realizan phpunit tests en format de assertions sobre los métodos
 * públicos de la clases validateID().
 *
 * Los métodos públicos son: 
 *
 * validateCedula()
 * validateNaturalPersonRuc()
 * validatePrivateCompanyRuc()
 */
class ValidarIdenfiticacionTest extends PHPUnit_Framework_TestCase
{
    /**
     * Validador
     * 
     * Guarda Instancia de clase validateID() disponible para todos los métodos
     *
     * @var string
     * @access protected
     */
    protected $validador;
 
    /**
     * Inicio objecto validateID() 
     */
    protected function setUp()
    {
        $this->validador = new validateID();
    }

    /**
     * Tests sobre método público validateCedula()
     */
    public function testCedula()
    {
        // parametro vacio o sin parametro (number ci) deben dar false
        $validateCedula = $this->validador->validateCedula('');
        $this->assertEquals($validateCedula, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');
        
        $validateCedula = $this->validador->validateCedula();
        $this->assertEquals($validateCedula, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');

        // parametro con 0 adelante pero como integer, debe dar false ya que php lo convierte a 0
        $validateCedula = $this->validador->validateCedula(0926687856);
        $this->assertEquals($validateCedula, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');

        // parametro debe tener solo digitos
        $validateCedula = $this->validador->validateCedula('-0926687856');
        $this->assertEquals($validateCedula, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado solo puede tener dígitos');

        $validateCedula = $this->validador->validateCedula('09.26687856');
        $this->assertEquals($validateCedula, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado solo puede tener dígitos');

        // cedula debe tener 10 characters exactos
        $validateCedula = $this->validador->validateCedula('0926687864777009');
        $this->assertEquals($validateCedula, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado debe tener 10 characters');

        // revisar codigo de provincia, debe estar entre 0 y 24
        $validateCedula = $this->validador->validateCedula('9926687856');
        $this->assertEquals($validateCedula, false);
        $this->assertEquals($this->validador->getError(), 'Codigo de Provincia (dos primeros dígitos) no deben ser mayor a 24 ni menores a 0');

        // revisar tercer digito, debe ser mayor/igual a 0 y menor a 6
        $validateCedula = $this->validador->validateCedula('0996687856');
        $this->assertEquals($validateCedula, false);
        $this->assertEquals($this->validador->getError(), 'Tercer dígito debe ser mayor o igual a 0 y menor a 6 para cédulas y RUC de persona natural');

        // cedula incorrecta de acuerdo a algoritmo modulo10
        $validateCedula = $this->validador->validateCedula('0926687858');
        $this->assertEquals($validateCedula, false);
        $this->assertEquals($this->validador->getError(), 'Dígitos iniciales no validan contra Dígito Idenficador');
    
        // revisar que cedulas correctas validen
        $validateCedula = $this->validador->validateCedula('0602910945');
        $this->assertEquals($validateCedula, true);
        
        $validateCedula = $this->validador->validateCedula('0926687856');
        $this->assertEquals($validateCedula, true);
        
        $validateCedula = $this->validador->validateCedula('0910005917');
        $this->assertEquals($validateCedula, true);
    }
 
    /**
     * Tests sobre método público validateNaturalPersonRuc()
     */
    public function testRucPersonaNatural()
    {
        // parametro vacio o sin parametro (number ci) deben dar false
        $validateNaturalPersonRuc = $this->validador->validateNaturalPersonRuc('');
        $this->assertEquals($validateNaturalPersonRuc, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');
        
        $validateNaturalPersonRuc = $this->validador->validateNaturalPersonRuc();
        $this->assertEquals($validateNaturalPersonRuc, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');

        // parametro con 0 adelante pero como integer, debe dar false ya que php lo convierte a 0
        $validateNaturalPersonRuc = $this->validador->validateNaturalPersonRuc(0926687856001);
        $this->assertEquals($validateNaturalPersonRuc, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');

        // parametro debe tener solo digitos
        $validateNaturalPersonRuc = $this->validador->validateNaturalPersonRuc('-0926687856001');
        $this->assertEquals($validateNaturalPersonRuc, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado solo puede tener dígitos');

        $validateNaturalPersonRuc = $this->validador->validateNaturalPersonRuc('09.26687856001');
        $this->assertEquals($validateNaturalPersonRuc, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado solo puede tener dígitos');

        // ruc de persona natural debe tener 13 characters exactos
        $validateNaturalPersonRuc = $this->validador->validateNaturalPersonRuc('0926687864777009');
        $this->assertEquals($validateNaturalPersonRuc, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado debe tener 13 characters');

        // revisar codigo de provincia, debe estar entre 0 y 24
        $validateNaturalPersonRuc = $this->validador->validateNaturalPersonRuc('9926687856001');
        $this->assertEquals($validateNaturalPersonRuc, false);
        $this->assertEquals($this->validador->getError(), 'Codigo de Provincia (dos primeros dígitos) no deben ser mayor a 24 ni menores a 0');

        // revisar tercer digito, debe ser mayor/igual a 0 y menor a 6
        $validateNaturalPersonRuc = $this->validador->validateNaturalPersonRuc('0996687856001');
        $this->assertEquals($validateNaturalPersonRuc, false);
        $this->assertEquals($this->validador->getError(), 'Tercer dígito debe ser mayor o igual a 0 y menor a 6 para cédulas y RUC de persona natural');

        // revisar que codigo de establecimiento (3 últimos dígitos) no sean menores a 1.
        $validateNaturalPersonRuc = $this->validador->validateNaturalPersonRuc('0926687856000');
        $this->assertEquals($validateNaturalPersonRuc, false);
        $this->assertEquals($this->validador->getError(), 'Código de establecimiento no puede ser 0');

        // ruc persona natural incorrecto de acuerdo a algoritmo modulo10
        $validateNaturalPersonRuc = $this->validador->validateNaturalPersonRuc('0926687858001');
        $this->assertEquals($validateNaturalPersonRuc, false);
        $this->assertEquals($this->validador->getError(), 'Dígitos iniciales no validan contra Dígito Idenficador');
    
        // revisar que cedulas correctas validen
        $validateNaturalPersonRuc = $this->validador->validateNaturalPersonRuc('0602910945001');
        $this->assertEquals($validateNaturalPersonRuc, true);
        
        $validateNaturalPersonRuc = $this->validador->validateNaturalPersonRuc('0926687856001');
        $this->assertEquals($validateNaturalPersonRuc, true);
        
        $validateNaturalPersonRuc = $this->validador->validateNaturalPersonRuc('0910005917001');
        $this->assertEquals($validateNaturalPersonRuc, true);
    }

    /**
     * Tests sobre método público validatePrivateCompanyRuc()
     */
    public function testRucSociedadPrivada()
    {
        // parametro vacio o sin parametro (number ci) deben dar false
        $validatePrivateCompanyRuc = $this->validador->validatePrivateCompanyRuc('');
        $this->assertEquals($validatePrivateCompanyRuc, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');
        
        $validatePrivateCompanyRuc = $this->validador->validatePrivateCompanyRuc();
        $this->assertEquals($validatePrivateCompanyRuc, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');

        // parametro con 0 adelante pero como integer, debe dar false ya que php lo convierte a 0
        $validatePrivateCompanyRuc = $this->validador->validatePrivateCompanyRuc(0992397535001);
        $this->assertEquals($validatePrivateCompanyRuc, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');

        // parametro debe tener solo digitos
        $validatePrivateCompanyRuc = $this->validador->validatePrivateCompanyRuc('-0992397535001');
        $this->assertEquals($validatePrivateCompanyRuc, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado solo puede tener dígitos');

        $validatePrivateCompanyRuc = $this->validador->validatePrivateCompanyRuc('099,2397535001');
        $this->assertEquals($validatePrivateCompanyRuc, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado solo puede tener dígitos');

        // ruc de sociedad privada debe tener 13 characters exactos
        $validatePrivateCompanyRuc = $this->validador->validatePrivateCompanyRuc('0992397535001998');
        $this->assertEquals($validatePrivateCompanyRuc, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado debe tener 13 characters');

        // revisar codigo de provincia, debe estar entre 0 y 24
        $validatePrivateCompanyRuc = $this->validador->validatePrivateCompanyRuc('9992397535001');
        $this->assertEquals($validatePrivateCompanyRuc, false);
        $this->assertEquals($this->validador->getError(), 'Codigo de Provincia (dos primeros dígitos) no deben ser mayor a 24 ni menores a 0');

        // revisar tercer digito, debe ser igual a 9
        $validatePrivateCompanyRuc = $this->validador->validatePrivateCompanyRuc('0982397535001');
        $this->assertEquals($validatePrivateCompanyRuc, false);
        $this->assertEquals($this->validador->getError(), 'Tercer dígito debe ser igual a 9 para sociedades privadas');

        // revisar que codigo de establecimiento (3 últimos dígitos) no sean menores a 1.
        $validatePrivateCompanyRuc = $this->validador->validatePrivateCompanyRuc('0992397535000');
        $this->assertEquals($validatePrivateCompanyRuc, false);
        $this->assertEquals($this->validador->getError(), 'Código de establecimiento no puede ser 0');

        // ruc sociedad privada incorrecto de acuerdo a algoritmo modulo11
        $validatePrivateCompanyRuc = $this->validador->validatePrivateCompanyRuc('0992397532001');
        $this->assertEquals($validatePrivateCompanyRuc, false);
        $this->assertEquals($this->validador->getError(), 'Dígitos iniciales no validan contra Dígito Idenficador');
    
        // revisar que ruc correcto valide
        $validatePrivateCompanyRuc = $this->validador->validatePrivateCompanyRuc('0992397535001');
        $this->assertEquals($validatePrivateCompanyRuc, true);
        
    }

    /**
     * Tests sobre método público validatePublicCompanyRuc()
     */
    public function testRucSociedadPublica()
    {
        // parametro vacio o sin parametro (number ci) deben dar false
        $validatePublicCompanyRuc = $this->validador->validatePublicCompanyRuc('');
        $this->assertEquals($validatePublicCompanyRuc, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');
        
        $validatePublicCompanyRuc = $this->validador->validatePublicCompanyRuc();
        $this->assertEquals($validatePublicCompanyRuc, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');

        // parametro con 0 adelante pero como integer, debe dar false ya que php lo convierte a 0
        $validatePublicCompanyRuc = $this->validador->validatePublicCompanyRuc(0960001550001);
        $this->assertEquals($validatePublicCompanyRuc, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');

        // parametro debe tener solo digitos
        $validatePublicCompanyRuc = $this->validador->validatePublicCompanyRuc('-1760001550001');
        $this->assertEquals($validatePublicCompanyRuc, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado solo puede tener dígitos');

        $validatePublicCompanyRuc = $this->validador->validatePublicCompanyRuc('17600,01550001');
        $this->assertEquals($validatePublicCompanyRuc, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado solo puede tener dígitos');

        // ruc de sociedad pública debe tener 13 characters exactos
        $validatePublicCompanyRuc = $this->validador->validatePublicCompanyRuc('1760001550001990999');
        $this->assertEquals($validatePublicCompanyRuc, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado debe tener 13 characters');

        // revisar codigo de provincia, debe estar entre 0 y 24
        $validatePublicCompanyRuc = $this->validador->validatePublicCompanyRuc('2760001550001');
        $this->assertEquals($validatePublicCompanyRuc, false);
        $this->assertEquals($this->validador->getError(), 'Codigo de Provincia (dos primeros dígitos) no deben ser mayor a 24 ni menores a 0');

        // revisar tercer digito, debe ser igual a 6
        $validatePublicCompanyRuc = $this->validador->validatePublicCompanyRuc('1790001550001');
        $this->assertEquals($validatePublicCompanyRuc, false);
        $this->assertEquals($this->validador->getError(), 'Tercer dígito debe ser igual a 6 para sociedades públicas');

        // revisar que codigo de establecimiento (4 últimos dígitos) no sean menores a 1.
        $validatePublicCompanyRuc = $this->validador->validatePublicCompanyRuc('1760001550000');
        $this->assertEquals($validatePublicCompanyRuc, false);
        $this->assertEquals($this->validador->getError(), 'Código de establecimiento no puede ser 0');

        // ruc sociedad privada incorrecto de acuerdo a algoritmo modulo11
        $validatePublicCompanyRuc = $this->validador->validatePublicCompanyRuc('1760001520001');
        $this->assertEquals($validatePublicCompanyRuc, false);
        $this->assertEquals($this->validador->getError(), 'Dígitos iniciales no validan contra Dígito Idenficador');
    
        // revisar que ruc correcto valide
        $validatePublicCompanyRuc = $this->validador->validatePublicCompanyRuc('1760001550001');
        $this->assertEquals($validatePublicCompanyRuc, true);
        
    }
}
?>