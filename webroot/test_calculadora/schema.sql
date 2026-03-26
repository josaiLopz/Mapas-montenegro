-- ============================================================
--  Calculadora de Descuentos — Schema v2
--  Requiere MySQL 5.7+ / MariaDB 10.3+
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
-- Líneas de producto
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS lineas (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre        VARCHAR(120)   NOT NULL,
  precio_base   DECIMAL(12,4)  NOT NULL DEFAULT 0,
  activa        TINYINT(1)     NOT NULL DEFAULT 1,
  created_at    DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Campos de entrada dinámicos (por línea)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS campos (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  linea_id      INT UNSIGNED   NOT NULL,
  clave         VARCHAR(80)    NOT NULL,
  etiqueta      VARCHAR(200)   NOT NULL,
  tipo          ENUM('numero','checkbox','texto','suma','porcentaje') NOT NULL DEFAULT 'numero',
  formula       JSON           NULL COMMENT 'Para tipo suma/porcentaje',
  orden         SMALLINT       NOT NULL DEFAULT 99,
  activo        TINYINT(1)     NOT NULL DEFAULT 1,
  created_at    DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_campo_linea (linea_id, clave),
  FOREIGN KEY (linea_id) REFERENCES lineas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Descuentos configurables (por línea)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS descuentos (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  linea_id      INT UNSIGNED   NOT NULL,
  nombre        VARCHAR(200)   NOT NULL,
  depende_de    VARCHAR(80)    NOT NULL,
  escalonado    TINYINT(1)     NOT NULL DEFAULT 0,
  valor         DECIMAL(8,6)   NOT NULL DEFAULT 0 COMMENT 'Fracción, ej: 0.05 = 5%',
  tramos        JSON           NULL,
  sujeto_meta   TINYINT(1)     NOT NULL DEFAULT 0,
  activo        TINYINT(1)     NOT NULL DEFAULT 1,
  orden         SMALLINT       NOT NULL DEFAULT 99,
  created_at    DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (linea_id) REFERENCES lineas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Usuarios (clientes)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre        VARCHAR(200)   NOT NULL,
  email         VARCHAR(200)   NOT NULL,
  activo        TINYINT(1)     NOT NULL DEFAULT 1,
  created_at    DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Valores de campos por usuario y por línea
-- Almacena los valores numéricos (metas, ventas, etc.)
-- Los checkboxes NO se almacenan aquí (se capturan en tiempo real)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS usuario_valores (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario_id    INT UNSIGNED   NOT NULL,
  linea_id      INT UNSIGNED   NOT NULL,
  campo_clave   VARCHAR(80)    NOT NULL,
  valor         VARCHAR(500)   NOT NULL DEFAULT '',
  updated_at    DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_uv (usuario_id, linea_id, campo_clave),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (linea_id)   REFERENCES lineas(id)   ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Códigos de verificación OTP (login usuario)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS verificacion_codigos (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email         VARCHAR(200)   NOT NULL,
  codigo        CHAR(6)        NOT NULL,
  usado         TINYINT(1)     NOT NULL DEFAULT 0,
  expires_at    DATETIME       NOT NULL,
  created_at    DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_email_codigo (email, codigo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Sesiones de usuario (token simple)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS sesiones (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario_id    INT UNSIGNED   NOT NULL,
  token         CHAR(64)       NOT NULL,
  expires_at    DATETIME       NOT NULL,
  created_at    DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_token (token),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Cotizaciones guardadas
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS cotizaciones (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  linea_id      INT UNSIGNED   NOT NULL,
  usuario_id    INT UNSIGNED   NULL COMMENT 'NULL = cotización de admin',
  inputs_json   JSON           NOT NULL COMMENT 'Snapshot de todos los valores usados',
  desglose_json JSON           NOT NULL COMMENT 'Filas del desglose de descuentos',
  precio_base   DECIMAL(12,4)  NOT NULL,
  precio_final  DECIMAL(12,4)  NOT NULL,
  created_at    DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (linea_id)   REFERENCES lineas(id)   ON DELETE CASCADE,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- Datos de ejemplo (opcional — comentar en producción)
-- ============================================================
INSERT IGNORE INTO lineas (nombre, precio_base, activa) VALUES
  ('Línea Editorial', 300.00, 1),
  ('Línea Digital',   250.00, 1);
