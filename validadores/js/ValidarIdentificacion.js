class ValidarIdentificacion {
    _error = '';

    /**
     * Validar cédula
     *
     * @param  {string}  numero  Número de cédula
     *
     * @return {Boolean}
     */
    validarCedula(numero = '') {
        // fuerzo parametr de entrada a string
        numero = '' + numero;

        // borro pr si acaso errores de llamadas anteriores.
        this.error = '';

        // validaciones
        try {
            this._validarInicial(numero, 10);
            this._validarCodigoProvincia(numero.slice(0, 2));
            this._validarTercerDigito(numero[2], 'cedula');
            this._algoritmoModulo10(numero.slice(0, 9), numero[9]);
        } catch (error) {
            this.error = error.message;
            return false;
        }

        return true;
    }

    /**
     * Validar RUC persona natural
     *
     * @param  {string}  numero  Número de RUC persona natural
     *
     * @return {Boolean}
     */
    validarRucPersonaNatural(numero = '') {
        // fuerzo parametro de entrada a string
        numero = '' + numero;

        // borro por si acaso errores de llamadas anteriores
        this.error = '';

        // validaciones
        try {
            this._validarInicial(numero, 13);
            this._validarCodigoProvincia(numero.slice(0, 2));
            this._validarTercerDigito(numero[2], 'ruc_natural');
            this._validarCodigoEstablecimiento(numero.slice(10, 13));
            this._algoritmoModulo10(numero.slice(0, 9), numero[9]);
        } catch (error) {
            this.error = error.message;
            return false;
        }

        return true;
    }

    /**
     * Validar RUC sociedad privada
     *
     * @param  {string}  numero  Número de RUC sociedad privada
     *
     * @return {Boolean}
     */
    validarRucSociedadPrivada(numero = '') {
        // fuerzo parametro de entrada a string
        numero = '' + numero;

        // borro por si acaso errores de llamadas anteriores.
        this.error = '';

        // validaciones
        try {
            this._validarInicial(numero, 13);
            this._validarCodigoProvincia(numero.slice(0, 2));
            this._validarTercerDigito(numero[2], 'ruc_privada');
            this._validarCodigoEstablecimiento(numero.slice(10, 13));
            this._algoritmoModulo11(numero.slice(0, 9), numero[9], 'ruc_privada');
        } catch (error) {
            this.error = error.message;
            return false;
        }

        return true;
    }

    /**
     * Validar RUC sociedad publica
     *
     * @param  {string}  numero  Número de RUC sociedad publica
     *
     * @return {Boolean}
     */
    validarRucSociedadPublica(numero = '') {
        // fuerzo parametro de entrada a string
        numero = '' + numero;

        // borro por si acaso errores de llamadas anteriores.
        this.error = '';

        // validaciones
        try {
            this._validarInicial(numero, 13);
            this._validarCodigoProvincia(numero.slice(0, 2));
            this._validarTercerDigito(numero[2], 'ruc_publica');
            this._validarCodigoEstablecimiento(numero.slice(9, 13));
            this._algoritmoModulo11(numero.slice(0, 8), numero[8], 'ruc_publica');
        } catch(error) {
            this.error = error.message;
            return false
        }

        return true;
    }

    /**
     * Validaciones iniciales para CI y RUC
     *
     * @param  {string}  numero      CI o RUC
     * @param  {integer} caracteres  Cantidad de caracteres requeridos
     *
     * @return {boolean}
     *
     * @throws {Error} Cuando valor esta vacio, cuando no es dígito y
     * cuando no tiene cantidad requerida de caracteres
     */
    _validarInicial(numero, caracteres) {
        if (numero.length == 0) {
            throw new Error('Valor no puede estar vacío');
        }
        if (!numero.split('').reduce((acum, current) => acum && current >= '0' && current <= '9', true)){
            throw new Error('Valor ingresado solo puede tener dígitos');
        }

        if (numero.length != caracteres) {
            throw new Error(`Valor ingresado debe tener ${caracteres} caracteres`)
        }

        return true;
    }
    
    /**
     * Validación de código de provincia (dos primeros dígitos de CI/RUC)
     *
     * @param  {string}  numero  Dos primeros dígitos de CI/RUC
     *
     * @return {boolean}
     *
     * @throws {Error} Cuando el código de provincia no esta entre 00 y 24
     */
    _validarCodigoProvincia(numero, caracteres) {
        numero = Number(numero);
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
     * @param  {string} numero  tercer dígito de CI/RUC
     * @param  {string} tipo  tipo de identificador
     *
     * @return {boolean}
     *
     * @throws {Error} Cuando el tercer digito no es válido. El mensaje
     * de error depende del tipo de Idenficiación.
     */
    _validarTercerDigito(numero, tipo) {
        numero = Number(numero);
        switch (tipo) {
            case 'cedula':
            case 'ruc_natural':
                if (numero < 0 || numero > 5) {
                    throw new Error('Tercer dígito debe ser mayor o igual a 0 y menor a 6 para cédulas y RUC de persona natural');
                }
                break;
            case 'ruc_privada':
                if (numero != 9) {
                    throw new Error('Tercer dígito debe ser igual a 9 para sociedades privadas'); 
                }
                break;
            case 'ruc_publica':
                if (numero != 6) {
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
     * @param  {string} numero  tercer dígito de CI/RUC
     *
     * @return {boolean}
     *
     * @throws {Error} Cuando el establecimiento es menor a 1
     */
    _validarCodigoEstablecimiento(numero) {
        if (Number(numero) < 1) {
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
     * @param  {string} digitosIniciales   Nueve primeros dígitos de CI/RUC
     * @param  {string} digitoVerificador  Décimo dígito de CI/RUC
     *
     * @return {boolean}
     *
     * @throws {Error} Cuando los digitosIniciales no concuerdan contra
     * el código verificador.
     */
    _algoritmoModulo10(digitosIniciales, digitoVerificador) {
        digitoVerificador
        const arrayCoeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];

        digitoVerificador = Number(digitoVerificador);
        digitosIniciales = digitosIniciales.split('');

        let total = 0;

        for (const [key, value] of digitosIniciales.entries()) {
            let valorPosicion = Number(value) * arrayCoeficientes[key];
            if (valorPosicion >= 10) {
                valorPosicion = '' + valorPosicion;
                valorPosicion = valorPosicion.split('').map(x => Number(x)).reduce((acum, current) => acum + current);
            }

            total += valorPosicion;
        }

        const residuo = total % 10;

        const resultado = residuo == 0 ? 0 : 10 - residuo;

        if (resultado != digitoVerificador) {
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
     * @param  {string} digitoVerificador  Décimo dígito de RUC
     * @param  {string} tipo Tipo de identificador
     * @param  {string} digitosIniciales   Nueve primeros dígitos de RUC
     *
     * @return {boolean}
     *
     * @throws {Error} Cuando los digitosIniciales no concuerdan contra
     * el código verificador.
     */
    _algoritmoModulo11(digitosIniciales, digitoVerificador, tipo) {
        let arrayCoeficientes;
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
        digitoVerificador = Number(digitoVerificador);
        digitosIniciales = digitosIniciales.split('');

        let total = 0;

        for (const [key, value] of digitosIniciales.entries()) {
            total += Number(value) * arrayCoeficientes[key];;
        }

        const residuo = total % 11;

        const resultado = residuo == 0 ? 0 : 11 - residuo;

        if (resultado != digitoVerificador) {
            throw new Error('Dígitos iniciales no validan contra Dígito Idenficador');
        }

        return true;
    }

    get error() {
        return this._error;
    }

    set error(newError) {
        this._error = newError;
    }
}
