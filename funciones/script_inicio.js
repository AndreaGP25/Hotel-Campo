document.getElementById("btn_registrarse").addEventListener("click", registro);
document.getElementById("btn_iniciar-sesion").addEventListener("click", iniciarSesion);

//cada que se mueva (resize)
window.addEventListener("resize", anchoPagina);


var contenedor_acceso_registro= document.querySelector(".contenedor_acceso_registro");
var formulario_acceso = document.querySelector(".formulario_acceso");
var formulario_registro = document.querySelector(".formulario_registro");
var caja_trasera_acceso = document.querySelector(".caja_trasera_acceso");
var caja_trasera_registro= document.querySelector(".caja_trasera_registro");


document.addEventListener("DOMContentLoaded", function () {
    const btnIniciar = document.getElementById("btn-iniciar");
    const btnRegistrar = document.getElementById("btn-registrar");
    const formLogin = document.getElementById("form-login");
    const formRegister = document.getElementById("form-register");

    btnIniciar.addEventListener("click", function () {
        formLogin.classList.remove("d-none");
        formRegister.classList.add("d-none");
    });

    btnRegistrar.addEventListener("click", function () {
        formRegister.classList.remove("d-none");
        formLogin.classList.add("d-none");
    });
});

function anchoPagina(){
    if(window.innerWidth > 850){
        caja_trasera_acceso.style.display = "block";
        caja_trasera_registro.style.display ="block";
    }else{
        caja_trasera_registro.style.display = "block";
        caja_trasera_registro.style.opacity ="1";
        caja_trasera_acceso.style.display = "none";
        formulario_acceso.style.display = "block";
        formulario_registro.style.display = "none";
        contenedor_acceso_registro.style.left ="0px";

    } 
}

anchoPagina();

function registro(){

    if(window.innerWidth > 840){
        formulario_registro.style.display = "block";
        contenedor_acceso_registro.style.left = "410px";
        formulario_acceso.style.display ="none";
        caja_trasera_registro.style.opacity = "0";
        caja_trasera_acceso.style.opacity = "1";

    }
    else{
        formulario_registro.style.display = "block";
        contenedor_acceso_registro.style.left = "0px";
        formulario_acceso.style.display ="none";
        caja_trasera_registro.style.display = "none";
        caja_trasera_acceso.style.display = "block";
        caja_trasera_acceso.style.opacity = "1";

    }
  

}

function iniciarSesion(){
    if(window.innerWidth > 850){
        formulario_registro.style.display = "none";
        contenedor_acceso_registro.style.left = "10px";
        formulario_acceso.style.display ="block";
        caja_trasera_registro.style.opacity = "1";
        caja_trasera_acceso.style.opacity = "0";
    }
    else{
        formulario_registro.style.display = "none";
        contenedor_acceso_registro.style.left = "0px";
        formulario_acceso.style.display ="block";
        caja_trasera_registro.style.display = "block";
        caja_trasera_acceso.style.display = "none";
    }

}

// No permite ingresar otros caracteres que no sean numeros
document.querySelector('input[name="numero"]').addEventListener('input', function(e) {
    const input = e.target;
    input.value = input.value.replace(/[^0-9\-]/g, ''); //
  });

// No permitir Caracteres raros en nombres
  document.querySelector('input[name="nombre"]').addEventListener('input', function(e) {
    const input = e.target;
    input.value = input.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚ\s]/g, '');
});

