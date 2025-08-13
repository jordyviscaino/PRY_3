
let tipo_usuario= "";
let estado_usuario = false;


function seleccionarUsuario(arg){
 
  tipo_usuario=arg.value;
  let radio_activo = document.getElementById("radio_activo");
  let radio_inactivo = document.getElementById("radio_activo");
  console.log(radio_activo.checked);
  let estado_usuario=false;
   console.log(tipo_usuario);

   if(radio_activo.checked){
    estado_usuario = true;
    console.log("Usuario activado");
   }else if (radio_inactivo.checked) {
    estado_usuario = false;
     console.log("Usuario desactivado");
   }else{
    console.log("no existen usuarios activos actualmente");
   }
   let mensaje = document.getElementById("respuesta2");

   if (tipo_usuario === "admin" ){
    if (estado_usuario === true){
      console.log("el usuario " + tipo_usuario + " se encuentra activo");
        mensaje.innerHTML = "Su usuario admin esta activo";
        mensaje.style.color="green";
    }else{
      console.log("el usuario " + tipo_usuario + " se encuentra desactivado, activelo");
       mensaje.innerHTML = "Su usuario admin esta inactivo, activelo para acceder";
       mensaje.style.color="red"; 
    }
   }else{
        console.log("el usuario " + tipo_usuario + " sno es ADMIN");
       mensaje.innerHTML = "No es admin";
       mensaje.style.color = "orange";
   }
  

}



function seleccionarEstado(arg){
  estado_usuario=arg.value;
    estado_usuario = !estado_usuario;
    estado_usuario = true;
      console.log(typeof estado_usuario);
  if (estado_usuario === "1") {
    estado_usuario = !estado_usuario;

  console.log(estado_usuario);
  }else {
    estado_usuario = !estado_usuario;
    estado_usuario = false;
      console.log(typeof estado_usuario);
  }

  let usuario = document.getElementById("tipo_usuario");
  tipo_usuario = usuario.value;
let mensaje = document.getElementById("respuesta2");
   
   if (tipo_usuario === "admin" ){
    if (estado_usuario === true){
      console.log("el usuario " + tipo_usuario + " se encuentra activo");
        mensaje.innerHTML = "Su usuario admin esta activo";
        mensaje.style.color="green"
    }else{
      console.log("el usuario " + tipo_usuario + " se encuentra desactivado, activelo");
       mensaje.innerHTML = "Su usuario admin esta inactivo, activelo para acceder";
       mensaje.style.color="red"
    }
   }else{
        console.log("el usuario " + tipo_usuario + " no es ADMIN");
       mensaje.innerHTML = "No es admin";
       mensaje.style.color = "orange";
   }
  

}