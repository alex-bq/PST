-- Verificar que exista la base de datos
IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = 'administracion')
BEGIN
    CREATE DATABASE [administracion]
END
GO

USE [administracion]
GO

-- Crear tabla tipos_turno si no existe
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[tipos_turno]') AND type in (N'U'))
BEGIN
    CREATE TABLE [dbo].[tipos_turno](
        [cod_tipo_turno] [int] IDENTITY(1,1) NOT NULL,
        [nombre] [nvarchar](255) NOT NULL,
        [activo] [int] DEFAULT ((1)) NOT NULL,
        PRIMARY KEY CLUSTERED ([cod_tipo_turno] ASC)
    )
END
GO

-- Insertar datos básicos si la tabla está vacía
IF NOT EXISTS (SELECT TOP 1 * FROM [dbo].[tipos_turno])
BEGIN
    INSERT INTO [dbo].[tipos_turno] (nombre, activo)
    VALUES 
        ('Día', 1),
        ('Tarde', 1),
        ('Noche', 1)
END
GO

PRINT 'Base de datos administracion creada y configurada exitosamente'
GO 