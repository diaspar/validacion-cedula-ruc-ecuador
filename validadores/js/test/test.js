import assert from 'assert';
import {ValidarIdentificacion as validador} from '../ValidarIdentificacion.js';

describe('ValidarIdentificacion tests',()=>{

  describe('validarCedula',()=>{

    it('should set errors and return true when id is valid',()=>{
      var validarCedula;

      // parametro vacio o sin parametro (numero ci) deben dar false
      validarCedula = validador.validarCedula('');
      assert.equal(validarCedula, false);
      assert.equal(validador.getError(), 'Valor no puede estar vacio');

      validarCedula = validador.validarCedula();
      assert.equal(validarCedula, false);
      assert.equal(validador.getError(), 'Valor no puede estar vacio');

      // parametro debe tener solo digitos
      validarCedula = validador.validarCedula('-0926687856');
      assert.equal(validarCedula, false);
      assert.equal(validador.getError(), 'Valor ingresado solo puede tener dígitos');

      validarCedula = validador.validarCedula('09.26687856');
      assert.equal(validarCedula, false);
      assert.equal(validador.getError(), 'Valor ingresado solo puede tener dígitos');

      // cedula debe tener 10 caracteres exactos
      validarCedula = validador.validarCedula('0926687864777009');
      assert.equal(validarCedula, false);
      assert.equal(validador.getError(), 'Valor ingresado debe tener 10 caracteres');

      // revisar codigo de provincia, debe estar entre 0 y 24
      validarCedula = validador.validarCedula('9926687856');
      assert.equal(validarCedula, false);
      assert.equal(validador.getError(), 'Codigo de Provincia (dos primeros dígitos) no deben ser mayor a 24 ni menores a 0');

      // revisar tercer digito, debe ser mayor/igual a 0 y menor a 6
      validarCedula = validador.validarCedula('0996687856');
      assert.equal(validarCedula, false);
      assert.equal(validador.getError(), 'Tercer dígito debe ser mayor o igual a 0 y menor a 6 para cédulas y RUC de persona natural');

      // cedula incorrecta de acuerdo a algoritmo modulo10
      validarCedula = validador.validarCedula('0926687858');
      assert.equal(validarCedula, false);
      assert.equal(validador.getError(), 'Dígitos iniciales no validan contra Dígito Idenficador');

      // revisar que cedulas correctas validen
      validarCedula = validador.validarCedula('0602910945');
      assert.equal(validarCedula, true);

      validarCedula = validador.validarCedula('0926687856');
      assert.equal(validarCedula, true);

      validarCedula = validador.validarCedula('0910005917');
      assert.equal(validarCedula, true);
    });

  });

  describe('validarRucPersonaNatural',()=>{

    it('should set errors and return true when id is valid',()=>{
      var validarRucPersonaNatural;

      // parametro vacio o sin parametro (numero ci) deben dar false
      validarRucPersonaNatural = validador.validarRucPersonaNatural('');
      assert.equal(validarRucPersonaNatural, false);
      assert.equal(validador.getError(), 'Valor no puede estar vacio');

      validarRucPersonaNatural = validador.validarRucPersonaNatural();
      assert.equal(validarRucPersonaNatural, false);
      assert.equal(validador.getError(), 'Valor no puede estar vacio');

      // parametro debe tener solo digitos
      validarRucPersonaNatural = validador.validarRucPersonaNatural('-0926687856001');
      assert.equal(validarRucPersonaNatural, false);
      assert.equal(validador.getError(), 'Valor ingresado solo puede tener dígitos');

      validarRucPersonaNatural = validador.validarRucPersonaNatural('09.26687856001');
      assert.equal(validarRucPersonaNatural, false);
      assert.equal(validador.getError(), 'Valor ingresado solo puede tener dígitos');

      // ruc de persona natural debe tener 13 caracteres exactos
      validarRucPersonaNatural = validador.validarRucPersonaNatural('0926687864777009');
      assert.equal(validarRucPersonaNatural, false);
      assert.equal(validador.getError(), 'Valor ingresado debe tener 13 caracteres');

      // revisar codigo de provincia, debe estar entre 0 y 24
      validarRucPersonaNatural = validador.validarRucPersonaNatural('9926687856001');
      assert.equal(validarRucPersonaNatural, false);
      assert.equal(validador.getError(), 'Codigo de Provincia (dos primeros dígitos) no deben ser mayor a 24 ni menores a 0');

      // revisar tercer digito, debe ser mayor/igual a 0 y menor a 6
      validarRucPersonaNatural = validador.validarRucPersonaNatural('0996687856001');
      assert.equal(validarRucPersonaNatural, false);
      assert.equal(validador.getError(), 'Tercer dígito debe ser mayor o igual a 0 y menor a 6 para cédulas y RUC de persona natural');

      // revisar que codigo de establecimiento (3 últimos dígitos) no sean menores a 1.
      validarRucPersonaNatural = validador.validarRucPersonaNatural('0926687856000');
      assert.equal(validarRucPersonaNatural, false);
      assert.equal(validador.getError(), 'Código de establecimiento no puede ser 0');

      // ruc persona natural incorrecto de acuerdo a algoritmo modulo10
      validarRucPersonaNatural = validador.validarRucPersonaNatural('0926687858001');
      assert.equal(validarRucPersonaNatural, false);
      assert.equal(validador.getError(), 'Dígitos iniciales no validan contra Dígito Idenficador');

      // revisar que cedulas correctas validen
      validarRucPersonaNatural = validador.validarRucPersonaNatural('0602910945001');
      assert.equal(validarRucPersonaNatural, true);

      validarRucPersonaNatural = validador.validarRucPersonaNatural('0926687856001');
      assert.equal(validarRucPersonaNatural, true);

      validarRucPersonaNatural = validador.validarRucPersonaNatural('0910005917001');
      assert.equal(validarRucPersonaNatural, true);
    });

  });

  describe('validarRucSociedadPrivada',()=>{

    it('should set errors and return true when id is valid',()=>{
      var validarRucSociedadPrivada;

      // parametro vacio o sin parametro (numero ci) deben dar false
      validarRucSociedadPrivada = validador.validarRucSociedadPrivada('');
      assert.equal(validarRucSociedadPrivada, false);
      assert.equal(validador.getError(), 'Valor no puede estar vacio');

      validarRucSociedadPrivada = validador.validarRucSociedadPrivada();
      assert.equal(validarRucSociedadPrivada, false);
      assert.equal(validador.getError(), 'Valor no puede estar vacio');

      // parametro debe tener solo digitos
      validarRucSociedadPrivada = validador.validarRucSociedadPrivada('-0992397535001');
      assert.equal(validarRucSociedadPrivada, false);
      assert.equal(validador.getError(), 'Valor ingresado solo puede tener dígitos');

      validarRucSociedadPrivada = validador.validarRucSociedadPrivada('099,2397535001');
      assert.equal(validarRucSociedadPrivada, false);
      assert.equal(validador.getError(), 'Valor ingresado solo puede tener dígitos');

      // ruc de sociedad privada debe tener 13 caracteres exactos
      validarRucSociedadPrivada = validador.validarRucSociedadPrivada('0992397535001998');
      assert.equal(validarRucSociedadPrivada, false);
      assert.equal(validador.getError(), 'Valor ingresado debe tener 13 caracteres');

      // revisar codigo de provincia, debe estar entre 0 y 24
      validarRucSociedadPrivada = validador.validarRucSociedadPrivada('9992397535001');
      assert.equal(validarRucSociedadPrivada, false);
      assert.equal(validador.getError(), 'Codigo de Provincia (dos primeros dígitos) no deben ser mayor a 24 ni menores a 0');

      // revisar tercer digito, debe ser igual a 9
      validarRucSociedadPrivada = validador.validarRucSociedadPrivada('0982397535001');
      assert.equal(validarRucSociedadPrivada, false);
      assert.equal(validador.getError(), 'Tercer dígito debe ser igual a 9 para sociedades privadas');

      // revisar que codigo de establecimiento (3 últimos dígitos) no sean menores a 1.
      validarRucSociedadPrivada = validador.validarRucSociedadPrivada('0992397535000');
      assert.equal(validarRucSociedadPrivada, false);
      assert.equal(validador.getError(), 'Código de establecimiento no puede ser 0');

      // ruc sociedad privada incorrecto de acuerdo a algoritmo modulo11
      validarRucSociedadPrivada = validador.validarRucSociedadPrivada('0992397532001');
      assert.equal(validarRucSociedadPrivada, false);
      assert.equal(validador.getError(), 'Dígitos iniciales no validan contra Dígito Idenficador');

      // revisar que ruc correcto valide
      validarRucSociedadPrivada = validador.validarRucSociedadPrivada('0992397535001');
      assert.equal(validarRucSociedadPrivada, true);
    });


  });

  describe('validarRucSociedadPublica',()=>{

    it('should set errors and return true when id is valid',()=>{
      var validarRucSociedadPublica;

      // parametro vacio o sin parametro (numero ci) deben dar false
      validarRucSociedadPublica = validador.validarRucSociedadPublica('');
      assert.equal(validarRucSociedadPublica, false);
      assert.equal(validador.getError(), 'Valor no puede estar vacio');

      validarRucSociedadPublica = validador.validarRucSociedadPublica();
      assert.equal(validarRucSociedadPublica, false);
      assert.equal(validador.getError(), 'Valor no puede estar vacio');

      // parametro debe tener solo digitos
      validarRucSociedadPublica = validador.validarRucSociedadPublica('-1760001550001');
      assert.equal(validarRucSociedadPublica, false);
      assert.equal(validador.getError(), 'Valor ingresado solo puede tener dígitos');

      validarRucSociedadPublica = validador.validarRucSociedadPublica('17600,01550001');
      assert.equal(validarRucSociedadPublica, false);
      assert.equal(validador.getError(), 'Valor ingresado solo puede tener dígitos');

      // ruc de sociedad pública debe tener 13 caracteres exactos
      validarRucSociedadPublica = validador.validarRucSociedadPublica('1760001550001990999');
      assert.equal(validarRucSociedadPublica, false);
      assert.equal(validador.getError(), 'Valor ingresado debe tener 13 caracteres');

      // revisar codigo de provincia, debe estar entre 0 y 24
      validarRucSociedadPublica = validador.validarRucSociedadPublica('2760001550001');
      assert.equal(validarRucSociedadPublica, false);
      assert.equal(validador.getError(), 'Codigo de Provincia (dos primeros dígitos) no deben ser mayor a 24 ni menores a 0');

      // revisar tercer digito, debe ser igual a 6
      validarRucSociedadPublica = validador.validarRucSociedadPublica('1790001550001');
      assert.equal(validarRucSociedadPublica, false);
      assert.equal(validador.getError(), 'Tercer dígito debe ser igual a 6 para sociedades públicas');

      // revisar que codigo de establecimiento (4 últimos dígitos) no sean menores a 1.
      validarRucSociedadPublica = validador.validarRucSociedadPublica('1760001550000');
      assert.equal(validarRucSociedadPublica, false);
      assert.equal(validador.getError(), 'Código de establecimiento no puede ser 0');

      // ruc sociedad privada incorrecto de acuerdo a algoritmo modulo11
      validarRucSociedadPublica = validador.validarRucSociedadPublica('1760001520001');
      assert.equal(validarRucSociedadPublica, false);
      assert.equal(validador.getError(), 'Dígitos iniciales no validan contra Dígito Idenficador');

      // revisar que ruc correcto valide
      validarRucSociedadPublica = validador.validarRucSociedadPublica('1760001550001');
      assert.equal(validarRucSociedadPublica, true);
    });
    
  });


});
