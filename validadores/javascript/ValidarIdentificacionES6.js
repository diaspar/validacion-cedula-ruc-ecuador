class ValidarIdentificacion{
    /**
   * Error
   *
   * Contiene errores globales de la clase
   *
   * @var string
   * @access protected
   */
  error = '';


   /**
   * Validar cédula
   *
   * @param  string  validar  Número de cédula
   *
   * @return Boolean
   */
  validarCedula=validar=>{
    let ced=validar.trim();
    if(ced.length===10){
      let valor=this.coeficiente(ced);
      let verificador=this.sumar(valor);
      verificador=10-verificador;
      console.log(verificador);
      if(verificador===Number(validar[9])){
         alert('Cedula valida.');
         return true;
      }else if(verificador===10 && Number(validar[9])===0){
        alert('Cedula valida.');
        return true;
      }else{
        alert('Cedula no valida.');
        return false;
      }
    }else{
      this.error='Error. La cedula debe tener 10 digitos.';
      alert(this.error);
    }
  }
  
  /**
   * Multiplicar el coeficiente en cada digito
   *
   * @param  string  ced  Número de cédula
   *
   * @return string valor
   */
  coeficiente=ced=>{
    let valor=Array(ced);
    for(let i=0; i<9; i++){
      if(i%2===0){
        valor[i]=this.biggerthan(ced[i]*2);
      }else{
        valor[i]=ced[i];
      }
    }
    return valor;
  }
  
  
  /**
   * Chequear si un numero es mayor o igual a 10 y si es asi sumar sus digitos
   *
   * @param  number  num  Coeficiente
   *
   * @return number num
   */
  biggerthan=num=>{
    if(num>=10){
      num=Number(String(num)[0])+Number(String(num)[1]);
      return num;
    }else{
      return num;
    }
  }
  
  
  /**
   * Sumar los valores del numero de coeficientes
   *
   * @param  array  num  Coeficientes
   *
   * @return number verificador
   */
  sumar=num=>{
    let valor=num;
    let sum=0;
    for(let i=0; i<valor.length; i++){
      sum=sum+Number(valor[i]);
    }
    let verificador=String(sum)[1];
    return Number(verificador);
  }
  
  
}