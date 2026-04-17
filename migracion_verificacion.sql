ALTER TABLE `usuarios`
  ADD COLUMN `verificado`        TINYINT(1)   NOT NULL DEFAULT 0          AFTER `activo`,
  ADD COLUMN `codigo_verificacion` VARCHAR(6)  NULL DEFAULT NULL           AFTER `verificado`,
  ADD COLUMN `codigo_expira`     DATETIME     NULL DEFAULT NULL            AFTER `codigo_verificacion`;

CREATE TABLE IF NOT EXISTS `tokens_recuperacion` (
  `id`          INT(11)       NOT NULL AUTO_INCREMENT,
  `id_usuario`  INT(11)       NOT NULL,
  `token`       VARCHAR(64)   NOT NULL,
  `expira_en`   DATETIME      NOT NULL,
  `usado`       TINYINT(1)    NOT NULL DEFAULT 0,
  `creado_en`   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `fk_token_usuario` (`id_usuario`),
  CONSTRAINT `fk_token_usuario`
    FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

UPDATE `usuarios` SET `verificado` = 1 WHERE `verificado` = 0;


-- NOTAS:
--   • codigo_verificacion: código numérico de 6 dígitos (enviado por email/SMS (simulado))
--   • codigo_expira:        el código expira 15 minutos después de generarse
--   • tokens_recuperacion:  cada token tiene validez de 30 minutos
--   • Un usuario NO verificado no puede iniciar sesión

