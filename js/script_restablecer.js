document.getElementById('form-restablecer').addEventListener('submit', function (event) {
    event.preventDefault();
  
    const emailInput = document.getElementById('email').value.trim();
    const nuevaContrasena = document.getElementById('nueva-contrasena').value.trim();
    const confirmarContrasena = document.getElementById('confirmar-contrasena').value.trim();
    const mensaje = document.getElementById('mensaje');
  
    if (!emailInput || !nuevaContrasena || !confirmarContrasena) {
      mensaje.textContent = 'Por favor, completa todos los campos.';
      mensaje.style.color = 'red';
      return;
    }
  
    if (nuevaContrasena !== confirmarContrasena) {
      mensaje.textContent = 'Las contraseñas no coinciden. Intenta nuevamente.';
      mensaje.style.color = 'red';
      return;
    }
  
    if (nuevaContrasena.length < 0) {
      mensaje.textContent = 'La contraseña debe tener al menos 8 caracteres.';
      mensaje.style.color = 'red';
      return;
    }
  
    // Simular envío al servidor
    setTimeout(() => {
      mensaje.textContent = `Contraseña restablecida con éxito para ${emailInput}.`;
      mensaje.style.color = 'green';
    }, 1000);
  });
  