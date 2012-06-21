<?php
require('ValidarIdentificacion.php');

class ValidarIdenfiticacionTest extends PHPUnit_Framework_TestCase
{
    protected $validador;
 
    protected function setUp()
    {
        $this->validador = new ValidarIdentificacion();
    }

    public function testCedula()
    {
        // parametro vacio o sin parametro (numero ci) deben dar false
        $validarCedula = $this->validador->validarCedula('');
        $this->assertEquals($validarCedula, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');
        
        $validarCedula = $this->validador->validarCedula();
        $this->assertEquals($validarCedula, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');

        // parametro con 0 adelante pero como integer, debe dar false ya que php lo convierte a 0
        $validarCedula = $this->validador->validarCedula(0926687856);
        $this->assertEquals($validarCedula, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');

        // parametro debe tener solo digitos
        $validarCedula = $this->validador->validarCedula('-0926687856');
        $this->assertEquals($validarCedula, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado solo puede tener dígitos');

        $validarCedula = $this->validador->validarCedula('09.26687856');
        $this->assertEquals($validarCedula, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado solo puede tener dígitos');

        // cedula debe tener 10 caracteres exactos
        $validarCedula = $this->validador->validarCedula('0926687864777009');
        $this->assertEquals($validarCedula, false);
        $this->assertEquals($this->validador->getError(), 'Valor ingresado debe tener 10 caracteres');

        // revisar codigo de provincia, debe estar entre 0 y 24
        $validarCedula = $this->validador->validarCedula('9926687856');
        $this->assertEquals($validarCedula, false);
        $this->assertEquals($this->validador->getError(), 'Codigo de Provincia (dos primeros dígitos) no deben ser mayor a 24 ni menores a 0');

        // revisar tercer digito, debe ser mayor/igual a 0 y menor a 6
        $validarCedula = $this->validador->validarCedula('0996687856');
        $this->assertEquals($validarCedula, false);
        $this->assertEquals($this->validador->getError(), 'Tercer dígito debe ser mayor o igual a 0 y menor a 6 para cédulas y RUC de persona natural');

        // cedula incorrecta de acuerdo a algoritmo modulo10
        $validarCedula = $this->validador->validarCedula('0926687858');
        $this->assertEquals($validarCedula, false);
        $this->assertEquals($this->validador->getError(), 'Dígitos iniciales no validan contra Dígito Idenficador');
    
        // revisar que cedulas correctas validen
        $validarCedula = $this->validador->validarCedula('0602910945');
        $this->assertEquals($validarCedula, true);
        
        $validarCedula = $this->validador->validarCedula('0926687856');
        $this->assertEquals($validarCedula, true);
        
        $validarCedula = $this->validador->validarCedula('0910005917');
        $this->assertEquals($validarCedula, true);
    }
 
    public function testRucPersonaNatural()
    {
        // parametro vacio o sin parametro (numero ci) deben dar false
        $validarRucPersonaNatural = $this->validador->validarRucPersonaNatural('');
        $this->assertEquals($validarRucPersonaNatural, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');
        
        $validarRucPersonaNatural = $this->validador->validarRucPersonaNatural();
        $this->assertEquals($validarRucPersonaNatural, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');

        // parametro con 0 adelante pero como integer, debe dar false ya que php lo convierte a 0
        $validarRucPersonaNatural = $this->validador->validarRucPersonaNatural(0926687856001);
        $this->assertEquals($validarRucPersonaNatural, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');

        // parametro debe tener solo digitos
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

        // revisar codigo de provincia, debe estar entre 0 y 24
        $validarRucPersonaNatural = $this->validador->validarRucPersonaNatural('9926687856001');
        $this->assertEquals($validarRucPersonaNatural, false);
        $this->assertEquals($this->validador->getError(), 'Codigo de Provincia (dos primeros dígitos) no deben ser mayor a 24 ni menores a 0');

        // revisar tercer digito, debe ser mayor/igual a 0 y menor a 6
        $validarRucPersonaNatural = $this->validador->validarRucPersonaNatural('0996687856001');
        $this->assertEquals($validarRucPersonaNatural, false);
        $this->assertEquals($this->validador->getError(), 'Tercer dígito debe ser mayor o igual a 0 y menor a 6 para cédulas y RUC de persona natural');

        // revisar que codigo de establecimiento (3 últimos dígitos) no sean menores a 1.
        $validarRucPersonaNatural = $this->validador->validarRucPersonaNatural('0926687856000');
        $this->assertEquals($validarRucPersonaNatural, false);
        $this->assertEquals($this->validador->getError(), 'Código de establecimiento no puede ser 0');

        // ruc persona natural incorrecto de acuerdo a algoritmo modulo10
        $validarRucPersonaNatural = $this->validador->validarRucPersonaNatural('0926687858001');
        $this->assertEquals($validarRucPersonaNatural, false);
        $this->assertEquals($this->validador->getError(), 'Dígitos iniciales no validan contra Dígito Idenficador');
    
        // revisar que cedulas correctas validen
        $validarRucPersonaNatural = $this->validador->validarRucPersonaNatural('0602910945001');
        $this->assertEquals($validarRucPersonaNatural, true);
        
        $validarRucPersonaNatural = $this->validador->validarRucPersonaNatural('0926687856001');
        $this->assertEquals($validarRucPersonaNatural, true);
        
        $validarRucPersonaNatural = $this->validador->validarRucPersonaNatural('0910005917001');
        $this->assertEquals($validarRucPersonaNatural, true);
    }


    public function testRucSociedadPrivada()
    {
        // parametro vacio o sin parametro (numero ci) deben dar false
        $validarRucSociedadPrivada = $this->validador->validarRucSociedadPrivada('');
        $this->assertEquals($validarRucSociedadPrivada, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');
        
        $validarRucSociedadPrivada = $this->validador->validarRucSociedadPrivada();
        $this->assertEquals($validarRucSociedadPrivada, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');

        // parametro con 0 adelante pero como integer, debe dar false ya que php lo convierte a 0
        $validarRucSociedadPrivada = $this->validador->validarRucSociedadPrivada(0992397535001);
        $this->assertEquals($validarRucSociedadPrivada, false);
        $this->assertEquals($this->validador->getError(), 'Valor no puede estar vacio');

        // parametro debe tener solo digitos
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

        // revisar codigo de provincia, debe estar entre 0 y 24
        $validarRucSociedadPrivada = $this->validador->validarRucSociedadPrivada('9992397535001');
        $this->assertEquals($validarRucSociedadPrivada, false);
        $this->assertEquals($this->validador->getError(), 'Codigo de Provincia (dos primeros dígitos) no deben ser mayor a 24 ni menores a 0');

        // revisar tercer digito, debe ser igual a 9
        $validarRucSociedadPrivada = $this->validador->validarRucSociedadPrivada('0982397535001');
        $this->assertEquals($validarRucSociedadPrivada, false);
        $this->assertEquals($this->validador->getError(), 'Tercer dígito debe ser igual a 9 para sociedades privadas');

        // revisar que codigo de establecimiento (3 últimos dígitos) no sean menores a 1.
        $validarRucSociedadPrivada = $this->validador->validarRucSociedadPrivada('0992397535000');
        $this->assertEquals($validarRucSociedadPrivada, false);
        $this->assertEquals($this->validador->getError(), 'Código de establecimiento no puede ser 0');

        // ruc sociedad privada incorrecto de acuerdo a algoritmo modulo11
        $validarRucSociedadPrivada = $this->validador->validarRucSociedadPrivada('0992397532001');
        $this->assertEquals($validarRucSociedadPrivada, false);
        $this->assertEquals($this->validador->getError(), 'Dígitos iniciales no validan contra Dígito Idenficador');
    
        // revisar que ruc correcto valide
        $validarRucSociedadPrivada = $this->validador->validarRucSociedadPrivada('0992397535001');
        $this->assertEquals($validarRucSociedadPrivada, true);
        
    }
}
?>