USE [administracion]
GO

-- Agregar columna activo a la tabla tipos_turno si no existe
IF NOT EXISTS (SELECT * FROM sys.columns WHERE object_id = OBJECT_ID(N'[dbo].[tipos_turno]') AND name = 'activo')
BEGIN
    ALTER TABLE [dbo].[tipos_turno]
    ADD [activo] [int] DEFAULT ((1)) NOT NULL;
END
GO

PRINT 'Columna activo agregada exitosamente a la tabla tipos_turno'
GO 