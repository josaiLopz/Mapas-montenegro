# Calculadora de Descuentos v2 — Guía de Instalación

## Archivos incluidos
| Archivo | Descripción |
|---------|-------------|
| `schema.sql` | Estructura de la base de datos |
| `api.php` | Backend PHP (API REST) |
| `index.html` | Panel de administración |
| `user.html` | Portal de usuario (cliente) |

---

## 1. Base de Datos

```sql
CREATE DATABASE calculadora CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Luego importa el schema:
```bash
mysql -u root -p calculadora < schema.sql
```

---

## 2. Configurar api.php

Abre `api.php` y edita las constantes al inicio del archivo:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'calculadora');   // nombre de tu BD
define('DB_USER', 'root');          // usuario MySQL
define('DB_PASS', '');              // contraseña MySQL

define('SMTP_FROM', 'tucorreo@gmail.com');  // remitente de los OTP
// ... (ver sección SMTP abajo)

define('ADMIN_KEY', 'mi_clave_secreta_admin_2024');  // ¡CAMBIA ESTO!
```

---

## 3. Subir archivos al servidor

Sube los 4 archivos a la misma carpeta de tu servidor PHP:
```
/public_html/calculadora/
  ├── api.php
  ├── index.html    ← admin
  └── user.html     ← clientes
```

---

## 4. Configurar la clave de admin en el HTML

Abre `index.html` y busca esta línea (cerca del inicio del script):
```js
const ADMIN_KEY = "mi_clave_secreta_admin_2024";
```
Cámbiala para que coincida exactamente con la del `api.php`.

---

## 5. SMTP — Envío de correos

### Opción A: PHP mail() nativo
Si tu servidor tiene `sendmail` configurado, funciona sin cambios.

### Opción B: Gmail con App Password (recomendado)
1. Activa la verificación en dos pasos en tu cuenta Gmail
2. Ve a: Google Account → Seguridad → Contraseñas de aplicación
3. Genera una contraseña para "Correo" en dispositivo "Otro"
4. En `api.php` descomenta y configura la sección PHPMailer:
   ```php
   define('SMTP_USER', 'tucorreo@gmail.com');
   define('SMTP_PASS', 'xxxx xxxx xxxx xxxx');  // la contraseña de app
   ```
5. Instala PHPMailer:
   ```bash
   composer require phpmailer/phpmailer
   ```
6. Descomenta el bloque `// Opción B` en la función `sendOtpEmail()`

### Sin SMTP (modo desarrollo)
Si el SMTP no está configurado, la API devuelve el código en la respuesta JSON (`dev_codigo`). Esto se muestra automáticamente en la pantalla de OTP para pruebas.

---

## 6. Flujo de uso

### Admin (index.html)
1. **Crear líneas** de producto (con precio base)
2. **Crear campos** de entrada por línea:
   - `numero`: el usuario escribe un número
   - `checkbox`: sí/no (pago puntual, pronto pago, etc.)
   - `suma`: calculado automáticamente (suma de otros campos)
   - `porcentaje`: calculado (A ÷ B × 100, o crecimiento)
3. **Crear descuentos** que dependen de los campos
4. **Registrar usuarios** con su correo electrónico
5. **Asignar valores** a cada usuario por línea (metas, ventas del año anterior, etc.)

### Usuario (user.html)
1. Ingresa su correo → recibe código OTP
2. Ingresa el código de 6 dígitos
3. Selecciona la línea de producto
4. Ve sus datos precargados (metas, ventas, etc.)
5. Marca los checkboxes que apliquen
6. Ve el cálculo en tiempo real con el precio final
7. Puede guardar la cotización

---

## 7. Seguridad en producción

- ✅ Cambia `ADMIN_KEY` por una cadena larga y aleatoria
- ✅ Usa HTTPS en tu servidor
- ✅ Considera agregar protección IP o autenticación básica HTTP al `api.php`
- ✅ El `index.html` (admin) debería estar en una ruta no pública o protegida
- ✅ Los tokens de sesión expiran en 8 horas por defecto (`SESSION_HOURS`)
- ✅ Los códigos OTP expiran en 15 minutos (`OTP_MINUTES`)

---

## 8. Preguntas frecuentes

**¿Por qué aparecen campos vacíos en la calculadora del admin?**
Porque ahora no hay campos hardcodeados. Ve a "Campos de Entrada" → "Nuevo Campo" y crea los que necesitas para tu línea.

**¿Cómo agrego más campos el siguiente año?**
Solo crea campos nuevos o desactiva los que ya no apliquen. Los descuentos que dependen de campos inactivos simplemente no aplican.

**¿Los checkboxes se guardan por usuario?**
No, intencionalmente. Los checkboxes (pago puntual, pronto pago, etc.) representan condiciones de la venta actual, no atributos del usuario.

**¿Puedo tener distintos campos por línea?**
Sí, cada línea tiene sus propios campos y descuentos independientes.
