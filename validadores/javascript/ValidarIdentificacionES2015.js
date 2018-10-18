'use strict';

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var ValidarIdentificacion = function ValidarIdentificacion() {
  var _this = this;

  _classCallCheck(this, ValidarIdentificacion);


  /**
  * Error
  *
  * Contiene errores globales de la clase
  *
  * @var string
  * @access protected
  */

  this.error = '';



  /**
   * Validar cédula
   *
   * @param  string  validar  Número de cédula
   *
   * @return Boolean
   */

  this.validarCedula = function (validar) {
    var ced = validar.trim();
    if (ced.length === 10) {
      var valor = _this.coeficiente(ced);
      var verificador = _this.sumar(valor);
      verificador = 10 - verificador;
      if (verificador === Number(validar[9])) {
        alert('Cedula valida.');
        return true;
      } else {
        alert('Cedula no valida.');
        return false;
      }
    } else {
      _this.error = 'Error. La cedula debe tener 10 digitos.';
      alert(_this.error);
    }
  };


  /**
 * Multiplicar el coeficiente en cada digito
 *
 * @param  string  ced  Número de cédula
 *
 * @return string valor
 */

  this.coeficiente = function (ced) {
    var valor = Array(ced);
    for (var i = 0; i < 9; i++) {
      if (i % 2 === 0) {
        valor[i] = _this.biggerthan(ced[i] * 2);
      } else {
        valor[i] = ced[i];
      }
    }
    return valor;
  };


  /**
 * Chequear si un numero es mayor o igual a 10 y si es asi sumar sus digitos
 *
 * @param  number  num  Coeficiente
 *
 * @return number num
 */

  this.biggerthan = function (num) {
    if (num >= 10) {
      num = Number(String(num)[0]) + Number(String(num)[1]);
      return num;
    } else {
      return num;
    }
  };



  /**
 * Sumar los valores del numero de coeficientes
 *
 * @param  array  num  Coeficientes
 *
 * @return number verificador
 */

  this.sumar = function (num) {
    var valor = num;
    var sum = 0;
    for (var i = 0; i < valor.length; i++) {
      sum = sum + Number(valor[i]);
    }
    var verificador = String(sum)[1];
    return Number(verificador);
  };
};


