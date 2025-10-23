/*******************************************************************************
   SCRIPT DE ESTRUCTURA BASE DE DATOS PST
   
   Sistema: Planilla de Seguimiento de Trabajo
   Versión: Optimizada para portabilidad
   Fecha: Generado automáticamente
   
   IMPORTANTE:
   - Este script solo contiene ESTRUCTURA (no datos)
   - Compatible con diferentes servidores SQL Server
   - Sin rutas de archivos específicas
   - Incluye: Tablas, Vistas, Funciones, Procedimientos
   
   PREREQUISITOS:
   - Bases de datos: bdsystem y administracion deben existir previamente
   - SQL Server 2012 o superior (Compatibility Level 120)
   
*******************************************************************************/

USE [master]
GO

-- ============================================================================
-- SECCIÓN 1: CREACIÓN DE BASE DE DATOS
-- ============================================================================

-- Verificar si la base de datos existe
IF NOT EXISTS (SELECT name FROM sys.databases WHERE name = N'pst')
BEGIN
    CREATE DATABASE [pst]
END
GO

USE [pst]
GO

-- Configuración de la base de datos
ALTER DATABASE [pst] SET COMPATIBILITY_LEVEL = 120
GO

ALTER DATABASE [pst] SET ANSI_NULL_DEFAULT OFF 
GO
ALTER DATABASE [pst] SET ANSI_NULLS OFF 
GO
ALTER DATABASE [pst] SET ANSI_PADDING OFF 
GO
ALTER DATABASE [pst] SET ANSI_WARNINGS OFF 
GO
ALTER DATABASE [pst] SET ARITHABORT OFF 
GO
ALTER DATABASE [pst] SET AUTO_CLOSE OFF 
GO
ALTER DATABASE [pst] SET AUTO_SHRINK OFF 
GO
ALTER DATABASE [pst] SET AUTO_UPDATE_STATISTICS ON 
GO
ALTER DATABASE [pst] SET CURSOR_CLOSE_ON_COMMIT OFF 
GO
ALTER DATABASE [pst] SET CURSOR_DEFAULT GLOBAL 
GO
ALTER DATABASE [pst] SET CONCAT_NULL_YIELDS_NULL OFF 
GO
ALTER DATABASE [pst] SET NUMERIC_ROUNDABORT OFF 
GO
ALTER DATABASE [pst] SET QUOTED_IDENTIFIER OFF 
GO
ALTER DATABASE [pst] SET RECURSIVE_TRIGGERS OFF 
GO
ALTER DATABASE [pst] SET DISABLE_BROKER 
GO
ALTER DATABASE [pst] SET AUTO_UPDATE_STATISTICS_ASYNC OFF 
GO
ALTER DATABASE [pst] SET DATE_CORRELATION_OPTIMIZATION OFF 
GO
ALTER DATABASE [pst] SET TRUSTWORTHY OFF 
GO
ALTER DATABASE [pst] SET ALLOW_SNAPSHOT_ISOLATION OFF 
GO
ALTER DATABASE [pst] SET PARAMETERIZATION SIMPLE 
GO
ALTER DATABASE [pst] SET READ_COMMITTED_SNAPSHOT OFF 
GO
ALTER DATABASE [pst] SET HONOR_BROKER_PRIORITY OFF 
GO
ALTER DATABASE [pst] SET RECOVERY SIMPLE  -- Cambiado de FULL a SIMPLE para facilitar
GO
ALTER DATABASE [pst] SET MULTI_USER 
GO
ALTER DATABASE [pst] SET PAGE_VERIFY CHECKSUM  
GO

USE [pst]
GO

PRINT '✓ Base de datos PST creada/verificada correctamente'
GO

-- ============================================================================
-- SECCIÓN 2: CREACIÓN DE TABLAS
-- ============================================================================

PRINT 'Creando tablas...'
GO

-- Tabla: calibre
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[calibre]') AND type in (N'U'))
BEGIN
    CREATE TABLE [dbo].[calibre](
        [cod_calib] [int] IDENTITY(1,1) NOT NULL,
        [nombre] [nvarchar](255) NULL,
        [activo] [int] NULL,
        PRIMARY KEY CLUSTERED ([cod_calib] ASC)
        WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, 
              ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
    ) ON [PRIMARY]
    
    PRINT '  ✓ Tabla calibre creada'
END
GO

-- Tabla: calidad
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[calidad]') AND type in (N'U'))
BEGIN
    CREATE TABLE [dbo].[calidad](
        [cod_cald] [int] IDENTITY(1,1) NOT NULL,
        [nombre] [nvarchar](255) NULL,
        [activo] [int] NULL,
        PRIMARY KEY CLUSTERED ([cod_cald] ASC)
        WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, 
              ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
    ) ON [PRIMARY]
    
    PRINT '  ✓ Tabla calidad creada'
END
GO

-- Tabla: comentarios_informe_sala
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[comentarios_informe_sala]') AND type in (N'U'))
BEGIN
    CREATE TABLE [dbo].[comentarios_informe_sala](
        [cod_comentario] [int] IDENTITY(1,1) NOT NULL,
        [cod_informe] [int] NOT NULL,
        [cod_sala] [int] NOT NULL,
        [comentarios] [nvarchar](max) NULL,
        [fecha_creacion] [datetime] NULL,
        PRIMARY KEY CLUSTERED ([cod_comentario] ASC)
        WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, 
              ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
    ) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
    
    PRINT '  ✓ Tabla comentarios_informe_sala creada'
END
GO

-- Tabla: corte
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[corte]') AND type in (N'U'))
BEGIN
    CREATE TABLE [dbo].[corte](
        [cod_corte] [int] IDENTITY(1,1) NOT NULL,
        [nombre] [nvarchar](255) NULL,
        [activo] [int] NULL,
        PRIMARY KEY CLUSTERED ([cod_corte] ASC)
        WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, 
              ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
    ) ON [PRIMARY]
    
    PRINT '  ✓ Tabla corte creada'
END
GO

-- Tabla: departamentos
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[departamentos]') AND type in (N'U'))
BEGIN
    CREATE TABLE [dbo].[departamentos](
        [cod_departamento] [int] IDENTITY(1,1) NOT NULL,
        [nombre] [nvarchar](255) NULL,
        [activo] [int] NULL,
        PRIMARY KEY CLUSTERED ([cod_departamento] ASC)
        WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, 
              ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
    ) ON [PRIMARY]
    
    PRINT '  ✓ Tabla departamentos creada'
END
GO

-- Tabla: destino
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[destino]') AND type in (N'U'))
BEGIN
    CREATE TABLE [dbo].[destino](
        [cod_destino] [int] IDENTITY(1,1) NOT NULL,
        [nombre] [nvarchar](255) NULL,
        [activo] [int] NULL,
        PRIMARY KEY CLUSTERED ([cod_destino] ASC)
        WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, 
              ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
    ) ON [PRIMARY]
    
    PRINT '  ✓ Tabla destino creada'
END
GO

-- Tabla: detalle_informe_sala
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[detalle_informe_sala]') AND type in (N'U'))
BEGIN
    CREATE TABLE [dbo].[detalle_informe_sala](
        [cod_detalle_informe] [int] IDENTITY(1,1) NOT NULL,
        [cod_informe] [int] NOT NULL,
        [cod_sala] [int] NOT NULL,
        [tipo_planilla] [nvarchar](100) NULL,
        [dotacion_real] [int] NULL,
        [dotacion_esperada] [int] NULL,
        [kilos_entrega] [float] NULL,
        [kilos_recepcion] [float] NULL,
        [piezas_entrega] [int] NULL,
        [piezas_recepcion] [int] NULL,
        [kilos_premium] [decimal](18, 2) NULL,
        [premium] [decimal](5, 2) NULL,
        [horas_trabajadas] [decimal](10, 2) NULL,
        [tiempo_muerto_minutos] [int] NULL,
        [rendimiento] [decimal](5, 2) NULL,
        [productividad] [decimal](10, 2) NULL,
        PRIMARY KEY CLUSTERED ([cod_detalle_informe] ASC)
        WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, 
              ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
    ) ON [PRIMARY]
    
    PRINT '  ✓ Tabla detalle_informe_sala creada'
END
GO

-- Tabla: detalle_planilla_pst
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[detalle_planilla_pst]') AND type in (N'U'))
BEGIN
    CREATE TABLE [dbo].[detalle_planilla_pst](
        [cod_detalle] [int] IDENTITY(1,1) NOT NULL,
        [cod_planilla] [smallint] NULL,
        [cajas_entrega] [int] NULL,
        [kilos_entrega] [float] NULL,
        [piezas_entrega] [int] NULL,
        [cajas_recepcion] [int] NULL,
        [kilos_recepcion] [float] NULL,
        [piezas_recepcion] [int] NULL,
        [dotacion] [int] NULL,
        [cod_sala] [int] NULL,
        [observacion] [nvarchar](max) NULL,
        [productividad] [decimal](10, 2) NULL,
        [rendimiento] [decimal](10, 2) NULL,
        [embolsado_terminado] [int] NULL,
        [kilos_terminado] [float] NULL,
        PRIMARY KEY CLUSTERED ([cod_detalle] ASC)
        WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, 
              ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
    ) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
    
    PRINT '  ✓ Tabla detalle_planilla_pst creada'
END
GO

-- Tabla: fotos_informe
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[fotos_informe]') AND type in (N'U'))
BEGIN
    CREATE TABLE [dbo].[fotos_informe](
        [id_foto] [int] IDENTITY(1,1) NOT NULL,
        [cod_informe] [int] NOT NULL,
        [nombre_original] [nvarchar](255) NOT NULL,
        [nombre_archivo] [nvarchar](255) NOT NULL,
        [ruta_archivo] [nvarchar](500) NOT NULL,
        [tamaño_archivo] [bigint] NULL,
        [tipo_mime] [nvarchar](100) NULL,
        [fecha_subida] [datetime] NOT NULL,
        [cod_usuario_subida] [int] NULL,
        [activo] [bit] NOT NULL,
        [comentario] [nvarchar](500) NULL,
        CONSTRAINT [PK_fotos_informe] PRIMARY KEY CLUSTERED ([id_foto] ASC)
        WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, 
              ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
    ) ON [PRIMARY]
    
    PRINT '  ✓ Tabla fotos_informe creada'
END
GO

-- Tabla: informes_turno
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[informes_turno]') AND type in (N'U'))
BEGIN
    CREATE TABLE [dbo].[informes_turno](
        [cod_informe] [int] IDENTITY(1,1) NOT NULL,
        [fecha_turno] [date] NOT NULL,
        [cod_turno] [smallint] NOT NULL,
        [cod_jefe_turno] [numeric](18, 0) NULL,
        [cod_usuario_crea] [int] NULL,
        [comentarios] [nvarchar](max) NULL,
        [fecha_creacion] [datetime] NULL,
        [estado] [smallint] NULL,
        [fecha_finalizacion] [datetime] NULL,
        PRIMARY KEY CLUSTERED ([cod_informe] ASC)
        WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, 
              ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
    ) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
    
    PRINT '  ✓ Tabla informes_turno creada'
END
GO

-- Tabla: planillas_pst
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[planillas_pst]') AND type in (N'U'))
BEGIN
    CREATE TABLE [dbo].[planillas_pst](
        [cod_planilla] [smallint] IDENTITY(1,1) NOT NULL,
        [fec_turno] [date] NULL,
        [cod_turno] [smallint] NULL,
        [cod_planillero] [numeric](18, 0) NULL,
        [cod_supervisor] [numeric](18, 0) NULL,
        [fec_crea_planilla] [datetime] NULL,
        [cod_usuario_crea_planilla] [int] NULL,
        [guardado] [numeric](18, 0) NULL,
        [hora_inicio] [time](7) NULL,
        [hora_termino] [time](7) NULL,
        [cod_jefe_turno] [numeric](18, 0) NULL,
        [cod_tipo_planilla] [int] NULL,
        [tiempo_trabajado] [decimal](10, 2) NULL,
        -- Nuevas columnas para lomar_prod (sin dependencias)
        [lote_id_mp] [int] NULL,
        [lote_nombre] [nvarchar](255) NULL,
        [empresa_nombre] [nvarchar](255) NULL,
        [proveedor_nombre] [nvarchar](255) NULL,
        [especie_nombre] [nvarchar](255) NULL,
        [proceso_nombre] [nvarchar](255) NULL,
        [planta_nombre] [nvarchar](255) NULL,
        PRIMARY KEY CLUSTERED ([cod_planilla] ASC)
        WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, 
              ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
    ) ON [PRIMARY]
    
    PRINT '  ✓ Tabla planillas_pst creada'
    
    -- Crear índices para optimizar búsquedas
    CREATE NONCLUSTERED INDEX [IX_planillas_pst_lote_nombre] 
    ON [dbo].[planillas_pst]([lote_nombre] ASC);
    
    CREATE NONCLUSTERED INDEX [IX_planillas_pst_lote_id_mp] 
    ON [dbo].[planillas_pst]([lote_id_mp] ASC);
    
    PRINT '  ✓ Índices de planillas_pst creados'
END
GO

-- Tabla: registro_planilla_pst
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[registro_planilla_pst]') AND type in (N'U'))
BEGIN
    CREATE TABLE [dbo].[registro_planilla_pst](
        [cod_reg] [smallint] IDENTITY(1,1) NOT NULL,
        [cod_planilla] [smallint] NULL,
        [cod_corte_ini] [bigint] NULL,
        [cod_corte_fin] [bigint] NULL,
        [cod_destino] [numeric](18, 0) NULL,
        [cod_calibre] [smallint] NULL,
        [cod_calidad] [smallint] NULL,
        [piezas] [numeric](18, 0) NULL,
        [kilos] [float] NULL,
        [guardado] [numeric](18, 0) NULL,
        [es_producto_objetivo] [bit] NULL,
        PRIMARY KEY CLUSTERED ([cod_reg] ASC)
        WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, 
              ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
    ) ON [PRIMARY]
    
    PRINT '  ✓ Tabla registro_planilla_pst creada'
END
GO

-- Tabla: roles
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[roles]') AND type in (N'U'))
BEGIN
    CREATE TABLE [dbo].[roles](
        [cod_rol] [int] IDENTITY(1,1) NOT NULL,
        [nombre_rol] [nvarchar](255) NULL,
        [activo] [int] NULL,
        PRIMARY KEY CLUSTERED ([cod_rol] ASC)
        WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, 
              ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
    ) ON [PRIMARY]
    
    PRINT '  ✓ Tabla roles creada'
END
GO

-- Tabla: sala
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[sala]') AND type in (N'U'))
BEGIN
    CREATE TABLE [dbo].[sala](
        [cod_sala] [int] IDENTITY(1,1) NOT NULL,
        [nombre] [nvarchar](255) NULL,
        [activo] [int] NULL,
        PRIMARY KEY CLUSTERED ([cod_sala] ASC)
        WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, 
              ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
    ) ON [PRIMARY]
    
    PRINT '  ✓ Tabla sala creada'
END
GO

-- Tabla: tiempos_muertos
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[tiempos_muertos]') AND type in (N'U'))
BEGIN
    CREATE TABLE [dbo].[tiempos_muertos](
        [cod_tiempo_muerto] [int] IDENTITY(1,1) NOT NULL,
        [cod_planilla] [smallint] NOT NULL,
        [cod_departamento] [int] NOT NULL,
        [causa] [nvarchar](500) NULL,
        [duracion_minutos] [int] NOT NULL,
        [hora_inicio] [time](7) NULL,
        [hora_termino] [time](7) NULL,
        [fecha_registro] [datetime] NULL,
        PRIMARY KEY CLUSTERED ([cod_tiempo_muerto] ASC)
        WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, 
              ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
    ) ON [PRIMARY]
    
    PRINT '  ✓ Tabla tiempos_muertos creada'
END
GO

-- Tabla: tipo_planilla
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[tipo_planilla]') AND type in (N'U'))
BEGIN
    CREATE TABLE [dbo].[tipo_planilla](
        [cod_tipo_planilla] [int] IDENTITY(1,1) NOT NULL,
        [nombre] [nvarchar](255) NULL,
        [activo] [int] NULL,
        PRIMARY KEY CLUSTERED ([cod_tipo_planilla] ASC)
        WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, 
              ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
    ) ON [PRIMARY]
    
    PRINT '  ✓ Tabla tipo_planilla creada'
END
GO

-- Tabla: usuarios_pst
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[usuarios_pst]') AND type in (N'U'))
BEGIN
    CREATE TABLE [dbo].[usuarios_pst](
        [cod_usuario] [int] IDENTITY(1,1) NOT NULL,
        [usuario] [nvarchar](255) NULL,
        [nombre] [nvarchar](255) NULL,
        [apellido] [nvarchar](255) NULL,
        [pass] [nvarchar](255) NULL,
        [cod_rol] [int] NULL,
        [activo] [int] NULL,
        [cod_tipo_usuario] [int] NULL,
        PRIMARY KEY CLUSTERED ([cod_usuario] ASC)
        WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, 
              ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
    ) ON [PRIMARY]
    
    PRINT '  ✓ Tabla usuarios_pst creada'
END
GO

PRINT '✓ Todas las tablas creadas correctamente'
PRINT ''
GO

-- ============================================================================
-- SECCIÓN 3: DEFAULTS Y CONSTRAINTS
-- ============================================================================

PRINT 'Aplicando defaults y constraints...'
GO

-- Defaults para fotos_informe
IF NOT EXISTS (SELECT * FROM sys.default_constraints WHERE name = 'DF_fotos_informe_activo')
BEGIN
    ALTER TABLE [dbo].[fotos_informe] ADD CONSTRAINT [DF_fotos_informe_activo] DEFAULT ((1)) FOR [activo]
END
GO

IF NOT EXISTS (SELECT * FROM sys.default_constraints WHERE name = 'DF_fotos_informe_fecha_subida')
BEGIN
    ALTER TABLE [dbo].[fotos_informe] ADD CONSTRAINT [DF_fotos_informe_fecha_subida] DEFAULT (getdate()) FOR [fecha_subida]
END
GO

-- Defaults para informes_turno
IF NOT EXISTS (SELECT * FROM sys.default_constraints WHERE name = 'DF_informes_turno_estado')
BEGIN
    ALTER TABLE [dbo].[informes_turno] ADD CONSTRAINT [DF_informes_turno_estado] DEFAULT ((0)) FOR [estado]
END
GO

IF NOT EXISTS (SELECT * FROM sys.default_constraints WHERE name = 'DF_informes_turno_fecha_creacion')
BEGIN
    ALTER TABLE [dbo].[informes_turno] ADD CONSTRAINT [DF_informes_turno_fecha_creacion] DEFAULT (getdate()) FOR [fecha_creacion]
END
GO

-- Default para registro_planilla_pst
IF NOT EXISTS (SELECT * FROM sys.default_constraints WHERE name = 'DF__registro___es_pr__76969D2E')
BEGIN
    ALTER TABLE [dbo].[registro_planilla_pst] ADD CONSTRAINT [DF__registro___es_pr__76969D2E] DEFAULT ((0)) FOR [es_producto_objetivo]
END
GO

-- Default para tiempos_muertos
IF NOT EXISTS (SELECT * FROM sys.default_constraints WHERE name = 'DF_tiempos_muertos_fecha_registro')
BEGIN
    ALTER TABLE [dbo].[tiempos_muertos] ADD CONSTRAINT [DF_tiempos_muertos_fecha_registro] DEFAULT (getdate()) FOR [fecha_registro]
END
GO

PRINT '✓ Defaults y constraints aplicados'
PRINT ''
GO

-- ============================================================================
-- SECCIÓN 4: FUNCIONES DE USUARIO (TABLE-VALUED FUNCTIONS)
-- ============================================================================

PRINT 'Creando funciones...'
GO

-- Función: fn_GetDetalleProcesamiento
IF OBJECT_ID(N'[dbo].[fn_GetDetalleProcesamiento]', 'TF') IS NOT NULL
    DROP FUNCTION [dbo].[fn_GetDetalleProcesamiento]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE FUNCTION [dbo].[fn_GetDetalleProcesamiento]
(
    @fecha DATE,
    @turno INT
)
RETURNS TABLE
AS
RETURN
(
    SELECT
        p.cod_planilla,
        emp.descripcion,
        emp.cod_empresa,
        dp.cod_sala,
        tp.cod_tipo_planilla,
        tp.nombre as tipo_planilla,
        c_ini.nombre as corte_inicial,
        c_fin.nombre as corte_final,
        d.nombre as destino,
        cal.nombre as calibre,
        cld.nombre as calidad,
        esp.descripcion as especie,
        esp.cod_especie,
        rp.piezas,
        rp.kilos,
        rp.es_producto_objetivo,
        SUM(rp.piezas) OVER (PARTITION BY dp.cod_sala, tp.cod_tipo_planilla) as total_piezas_sala_tipo,
        SUM(rp.kilos) OVER (PARTITION BY dp.cod_sala, tp.cod_tipo_planilla) as total_kilos_sala_tipo
    FROM pst.dbo.planillas_pst p
    JOIN pst.dbo.detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
    JOIN pst.dbo.registro_planilla_pst rp ON p.cod_planilla = rp.cod_planilla
    JOIN pst.dbo.corte c_ini ON rp.cod_corte_ini = c_ini.cod_corte
    JOIN pst.dbo.corte c_fin ON rp.cod_corte_fin = c_fin.cod_corte
    JOIN pst.dbo.destino d ON rp.cod_destino = d.cod_destino
    JOIN pst.dbo.calibre cal ON rp.cod_calibre = cal.cod_calib
    JOIN pst.dbo.calidad cld ON rp.cod_calidad = cld.cod_cald
    JOIN pst.dbo.tipo_planilla tp ON p.cod_tipo_planilla = tp.cod_tipo_planilla
    JOIN bdsystem.dbo.empresas emp ON emp.cod_empresa = p.cod_empresa
    LEFT JOIN bdsystem.dbo.especies esp ON p.cod_especie = esp.cod_especie
    WHERE p.fec_turno = @fecha 
    AND p.cod_turno = @turno
    AND (
        (tp.nombre = 'Porciones' AND c_fin.nombre IN ('PORCION SIN PIEL', 'PORCION CON PIEL', 'PORCIONES'))
        OR
        (tp.nombre != 'Porciones' AND cld.nombre != 'SIN CALIDAD' AND c_fin.nombre != 'COLLARES EN MITADES')
    )
);
GO
PRINT '  ✓ fn_GetDetalleProcesamiento'
GO

-- Función: fn_GetHorariosTurno
IF OBJECT_ID(N'[dbo].[fn_GetHorariosTurno]', 'TF') IS NOT NULL
    DROP FUNCTION [dbo].[fn_GetHorariosTurno]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE FUNCTION [dbo].[fn_GetHorariosTurno](
    @fecha DATE,
    @turno INT
)
RETURNS TABLE
AS
RETURN
(
    SELECT 
        ht.hora_inicio,
        ht.hora_termino,
        ht.tiene_colacion,
        ht.hora_inicio_colacion,
        ht.hora_fin_colacion,
        CAST(
            DATEDIFF(MINUTE, ht.hora_inicio, ht.hora_termino) / 60.0 -
            CASE 
                WHEN ht.tiene_colacion = 1 
                THEN DATEDIFF(MINUTE, ht.hora_inicio_colacion, ht.hora_fin_colacion) / 60.0
                ELSE 0 
            END
        AS DECIMAL(10,2)) AS horas_trabajadas
    FROM administracion.dbo.horarios_turnos ht
    WHERE ht.id_turno = @turno
    AND ht.dia_semana = CASE 
        WHEN DATEPART(WEEKDAY, @fecha) = 1 THEN 6
        ELSE DATEPART(WEEKDAY, @fecha) - 2
    END
)
GO
PRINT '  ✓ fn_GetHorariosTurno'
GO

-- Función: fn_GetHorariosReales
IF OBJECT_ID(N'[dbo].[fn_GetHorariosReales]', 'TF') IS NOT NULL
    DROP FUNCTION [dbo].[fn_GetHorariosReales]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE FUNCTION [dbo].[fn_GetHorariosReales](
    @fecha DATE,
    @turno INT
)
RETURNS TABLE
AS
RETURN
(
    SELECT TOP 1
        MIN(p.hora_inicio) as hora_inicio,
        MAX(p.hora_termino) as hora_termino,
        CAST(AVG(p.tiempo_trabajado) AS DECIMAL(10,2)) as horas_trabajadas,
        CAST(0 AS BIT) as tiene_colacion,
        NULL as hora_inicio_colacion,
        NULL as hora_fin_colacion
    FROM pst.dbo.planillas_pst p
    WHERE p.fec_turno = @fecha
    AND p.cod_turno = @turno
    AND p.guardado = 1
    AND p.hora_inicio IS NOT NULL
    AND p.hora_termino IS NOT NULL
)
GO
PRINT '  ✓ fn_GetHorariosReales'
GO

-- Función: fn_GetInformesDiarios
IF OBJECT_ID(N'[dbo].[fn_GetInformesDiarios]', 'TF') IS NOT NULL
    DROP FUNCTION [dbo].[fn_GetInformesDiarios]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE FUNCTION [dbo].[fn_GetInformesDiarios]
(
    @fecha DATE
)
RETURNS TABLE
AS
RETURN
(
    SELECT 
        i.cod_informe,
        i.fecha_turno,
        i.cod_turno as orden_turno,
        tt.nombre as turno,
        CONCAT(u.nombre, ' ', u.apellido) as jefe_turno_nom,
        u.cod_usuario as jefe_turno,
        i.comentarios,
        hr.hora_inicio,
        hr.hora_termino,
        hr.horas_trabajadas,
        hr.tiene_colacion,
        hr.hora_inicio_colacion,
        hr.hora_fin_colacion,
        SUM(d.dotacion_real) as dotacion_total,
        SUM(d.dotacion_esperada) as dotacion_esperada,
        SUM(d.kilos_entrega) as total_kilos_entrega,
        SUM(d.kilos_recepcion) as total_kilos_recepcion
    FROM dbo.informes_turno i
    JOIN administracion.dbo.tipos_turno tt ON i.cod_turno = tt.id
    JOIN dbo.usuarios_pst u ON i.cod_jefe_turno = u.cod_usuario
    LEFT JOIN dbo.detalle_informe_sala d ON i.cod_informe = d.cod_informe
    OUTER APPLY dbo.fn_GetHorariosReales(i.fecha_turno, i.cod_turno) hr
    WHERE i.fecha_turno = @fecha 
    AND i.estado = 1
    AND tt.activo = 1
    GROUP BY 
        i.cod_informe, i.fecha_turno, i.cod_turno, tt.nombre,
        u.nombre, u.apellido, u.cod_usuario, i.comentarios,
        hr.hora_inicio, hr.hora_termino, hr.horas_trabajadas,
        hr.tiene_colacion, hr.hora_inicio_colacion, hr.hora_fin_colacion
)
GO
PRINT '  ✓ fn_GetInformesDiarios'
GO

-- Función: fn_GetInformacionPorSala
IF OBJECT_ID(N'[dbo].[fn_GetInformacionPorSala]', 'TF') IS NOT NULL
    DROP FUNCTION [dbo].[fn_GetInformacionPorSala]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE FUNCTION [dbo].[fn_GetInformacionPorSala]
(
    @fecha DATE,
    @turno INT
)
RETURNS TABLE
AS
RETURN
(
    SELECT 
        s.nombre as nombre_sala,
        dp.cod_sala,
        tp.nombre as tipo_planilla,
        tp.cod_tipo_planilla,
        COUNT(DISTINCT p.cod_planilla) as cantidad_planillas,
        SUM(p.tiempo_trabajado) as horas_trabajadas,
        SUM(dp.kilos_entrega) as kilos_entrega_total,
        SUM(dp.kilos_recepcion) as kilos_recepcion_total,
        SUM(dp.piezas_entrega) as piezas_entrega_total,
        SUM(dp.piezas_recepcion) as piezas_recepcion_total,
        CASE 
            WHEN tp.cod_tipo_planilla = 2 THEN SUM(dp.embolsado_terminado)
            ELSE NULL
        END as embolsado_terminado_total,
        CASE 
            WHEN tp.cod_tipo_planilla = 2 THEN SUM(dp.kilos_terminado)
            ELSE NULL
        END as kilos_terminado_total
    FROM pst.dbo.planillas_pst p
    JOIN pst.dbo.detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
    JOIN pst.dbo.sala s ON dp.cod_sala = s.cod_sala
    JOIN pst.dbo.tipo_planilla tp ON p.cod_tipo_planilla = tp.cod_tipo_planilla
    WHERE p.fec_turno = @fecha 
    AND p.cod_turno = @turno
    AND p.guardado = 1
    GROUP BY 
        s.nombre,
        dp.cod_sala,
        tp.nombre,
        tp.cod_tipo_planilla
)
GO
PRINT '  ✓ fn_GetInformacionPorSala'
GO

-- Función: fn_GetTiemposMuertos
IF OBJECT_ID(N'[dbo].[fn_GetTiemposMuertos]', 'TF') IS NOT NULL
    DROP FUNCTION [dbo].[fn_GetTiemposMuertos]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE FUNCTION [dbo].[fn_GetTiemposMuertos]
(
    @fecha DATE,
    @turno INT
)
RETURNS TABLE
AS
RETURN
(
    SELECT 
        dp.cod_sala,
        d.nombre,
        d.cod_departamento,
        tp.cod_tipo_planilla,
        tp.nombre as tipo_planilla,
        tm.causa as motivo,
        tm.duracion_minutos,
        SUM(tm.duracion_minutos) OVER (PARTITION BY dp.cod_sala, tp.cod_tipo_planilla) as total_minutos_sala_tipo
    FROM pst.dbo.planillas_pst p
    JOIN pst.dbo.detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
    JOIN pst.dbo.tiempos_muertos tm ON p.cod_planilla = tm.cod_planilla
    JOIN pst.dbo.tipo_planilla tp ON p.cod_tipo_planilla = tp.cod_tipo_planilla
    JOIN pst.dbo.departamentos d ON tm.cod_departamento = d.cod_departamento
    WHERE p.fec_turno = @fecha 
    AND p.cod_turno = @turno
)
GO
PRINT '  ✓ fn_GetTiemposMuertos'
GO

-- Función: fn_tiempos_muertos_dashboard
IF OBJECT_ID(N'[dbo].[fn_tiempos_muertos_dashboard]', 'TF') IS NOT NULL
    DROP FUNCTION [dbo].[fn_tiempos_muertos_dashboard]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE FUNCTION [dbo].[fn_tiempos_muertos_dashboard]
(
    @fecha_inicio DATE,
    @fecha_fin DATE,
    @tipo_planilla VARCHAR(50)
)
RETURNS TABLE
AS
RETURN
(
    SELECT 
        p.fec_turno,
        CASE p.cod_turno 
            WHEN 1 THEN 'Día'
            WHEN 2 THEN 'Tarde'
            WHEN 3 THEN 'Noche'
        END AS turno_nombre,
        d.nombre AS departamento,
        SUM(tm.duracion_minutos) as total_minutos_muertos,
        CASE 
            WHEN SUM(p.tiempo_trabajado * 60) > 0 
            THEN ROUND((SUM(tm.duracion_minutos) * 100.0 / SUM(p.tiempo_trabajado * 60)), 2)
            ELSE 0 
        END as porcentaje_tiempo_muerto,
        s.nombre AS sala,
        tp.nombre as tipo_planilla,
        STUFF((
            SELECT ', ' + causa
            FROM pst.dbo.tiempos_muertos tm2
            WHERE tm2.cod_planilla = p.cod_planilla
            FOR XML PATH('')), 1, 2, '') as causas
    FROM pst.dbo.planillas_pst p
    INNER JOIN pst.dbo.tipo_planilla tp ON p.cod_tipo_planilla = tp.cod_tipo_planilla
    INNER JOIN pst.dbo.detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
    INNER JOIN pst.dbo.sala s ON dp.cod_sala = s.cod_sala
    INNER JOIN pst.dbo.tiempos_muertos tm ON p.cod_planilla = tm.cod_planilla
    INNER JOIN pst.dbo.departamentos d ON tm.cod_departamento = d.cod_departamento
    WHERE 
        p.fec_turno BETWEEN @fecha_inicio AND @fecha_fin
        AND tp.nombre = @tipo_planilla
        AND p.guardado = 1
    GROUP BY 
        p.fec_turno,
        p.cod_turno,
        d.nombre,
        s.nombre,
        tp.nombre,
        p.cod_planilla
)
GO
PRINT '  ✓ fn_tiempos_muertos_dashboard'
GO

PRINT '✓ Funciones creadas correctamente'
PRINT ''
GO

-- ============================================================================
-- SECCIÓN 5: VISTAS (VIEWS)
-- ============================================================================

PRINT 'Creando vistas...'
GO

-- Vista: v_data_usuario
IF OBJECT_ID(N'[dbo].[v_data_usuario]', 'V') IS NOT NULL
    DROP VIEW [dbo].[v_data_usuario]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[v_data_usuario] AS
SELECT
    usuarios_pst.cod_usuario,
    usuarios_pst.usuario,
    usuarios_pst.nombre + ' ' + usuarios_pst.apellido AS nombre,
    usuarios_pst.nombre AS snombre,
    usuarios_pst.apellido AS sapellido,
    usuarios_pst.pass,
    usuarios_pst.cod_rol,
    roles.nombre_rol AS rol,
    usuarios_pst.activo
FROM usuarios_pst
INNER JOIN roles ON roles.cod_rol = usuarios_pst.cod_rol
GO
PRINT '  ✓ v_data_usuario'
GO

-- Vista: v_registro_planilla_pst
IF OBJECT_ID(N'[dbo].[v_registro_planilla_pst]', 'V') IS NOT NULL
    DROP VIEW [dbo].[v_registro_planilla_pst]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[v_registro_planilla_pst] AS
SELECT
    rp.cod_reg,
    rp.cod_planilla,
    ini.nombre AS cInicial,
    fin.nombre AS cFinal,
    de.nombre AS destino,
    c.nombre AS calibre,
    ca.nombre AS calidad,
    rp.piezas,
    rp.kilos,
    rp.guardado,
    rp.es_producto_objetivo
FROM registro_planilla_pst AS rp
LEFT OUTER JOIN corte AS ini ON rp.cod_corte_ini = ini.cod_corte
LEFT OUTER JOIN corte AS fin ON rp.cod_corte_fin = fin.cod_corte
LEFT OUTER JOIN destino AS de ON rp.cod_destino = de.cod_destino
LEFT OUTER JOIN calibre AS c ON rp.cod_calibre = c.cod_calib
LEFT OUTER JOIN calidad AS ca ON rp.cod_calidad = ca.cod_cald
GO
PRINT '  ✓ v_registro_planilla_pst'
GO

-- Vista: v_planilla_pst
IF OBJECT_ID(N'[dbo].[v_planilla_pst]', 'V') IS NOT NULL
    DROP VIEW [dbo].[v_planilla_pst]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[v_planilla_pst] AS
SELECT DISTINCT TOP (100) PERCENT
    pst.dbo.planillas_pst.cod_planilla,
    bdsystem.dbo.lotes.nombre AS lote,
    pst.dbo.planillas_pst.fec_turno,
    tt.nombre AS turno,
    bdsystem.dbo.empresas.descripcion AS empresa,
    bdsystem.dbo.proveedores.descripcion AS proveedor,
    bdsystem.dbo.especies.descripcion AS especie,
    bdsystem.dbo.subproceso.nombre AS proceso,
    pst.dbo.planillas_pst.cod_planillero,
    planillero.nombre + ' ' + planillero.apellido AS planillero_nombre,
    pst.dbo.planillas_pst.cod_supervisor,
    supervisor.nombre + ' ' + supervisor.apellido AS supervisor_nombre,
    pst.dbo.planillas_pst.cod_jefe_turno,
    jefe_turno.nombre + ' ' + jefe_turno.apellido AS jefe_turno_nombre,
    pst.dbo.planillas_pst.guardado,
    pst.dbo.planillas_pst.fec_crea_planilla,
    user_crea.cod_usuario AS cod_usuario_crea,
    user_crea.usuario AS usuario_crea,
    pst.dbo.sala.nombre as sala,
    CONVERT(TIME(0), pst.dbo.planillas_pst.hora_inicio) as hora_inicio,
    CONVERT(TIME(0), pst.dbo.planillas_pst.hora_termino) as hora_termino,
    pst.dbo.planillas_pst.cod_tipo_planilla,
    tp.nombre AS tipo_planilla_nombre
FROM pst.dbo.planillas_pst
LEFT OUTER JOIN bdsystem.dbo.lotes ON pst.dbo.planillas_pst.cod_lote = bdsystem.dbo.lotes.cod_lote
LEFT OUTER JOIN administracion.dbo.tipos_turno tt ON pst.dbo.planillas_pst.cod_turno = tt.id
LEFT OUTER JOIN bdsystem.dbo.empresas ON pst.dbo.planillas_pst.cod_empresa = bdsystem.dbo.empresas.cod_empresa
LEFT OUTER JOIN bdsystem.dbo.detalle_lote ON pst.dbo.planillas_pst.cod_lote = bdsystem.dbo.detalle_lote.cod_lote
LEFT OUTER JOIN bdsystem.dbo.proveedores ON bdsystem.dbo.detalle_lote.cod_proveedor = bdsystem.dbo.proveedores.cod_proveedor
LEFT OUTER JOIN bdsystem.dbo.especies ON pst.dbo.planillas_pst.cod_especie = bdsystem.dbo.especies.cod_especie
LEFT OUTER JOIN bdsystem.dbo.subproceso ON pst.dbo.planillas_pst.cod_proceso = bdsystem.dbo.subproceso.cod_sproceso
LEFT OUTER JOIN pst.dbo.usuarios_pst AS planillero ON pst.dbo.planillas_pst.cod_planillero = planillero.cod_usuario
LEFT OUTER JOIN pst.dbo.usuarios_pst AS supervisor ON pst.dbo.planillas_pst.cod_supervisor = supervisor.cod_usuario
LEFT OUTER JOIN pst.dbo.usuarios_pst AS jefe_turno ON pst.dbo.planillas_pst.cod_jefe_turno = jefe_turno.cod_usuario
LEFT OUTER JOIN pst.dbo.usuarios_pst AS user_crea ON pst.dbo.planillas_pst.cod_usuario_crea_planilla = user_crea.cod_usuario
LEFT OUTER JOIN pst.dbo.detalle_planilla_pst AS detalle ON pst.dbo.planillas_pst.cod_planilla = detalle.cod_planilla
LEFT OUTER JOIN pst.dbo.sala ON pst.dbo.sala.cod_sala = detalle.cod_sala
LEFT OUTER JOIN pst.dbo.tipo_planilla tp ON pst.dbo.planillas_pst.cod_tipo_planilla = tp.cod_tipo_planilla
WHERE tt.activo = 1
GO
PRINT '  ✓ v_planilla_pst'
GO

-- Vista: v_informe_por_turno
IF OBJECT_ID(N'[dbo].[v_informe_por_turno]', 'V') IS NOT NULL
    DROP VIEW [dbo].[v_informe_por_turno]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[v_informe_por_turno] AS
SELECT 
    p.fec_turno AS fecha, 
    p.cod_turno, 
    t.NomTurno AS nombre_turno, 
    p.cod_supervisor, 
    u.nombre AS nombre_supervisor, 
    COUNT(DISTINCT r.cod_planilla) AS total_registros, 
    ISNULL(dp.dotacion, 0) AS total_dotacion, 
    ISNULL(CAST(dp.productividad AS DECIMAL(10, 2)), 0) AS promedio_productividad, 
    ISNULL(CAST(dp.rendimiento AS DECIMAL(10, 2)), 0) AS promedio_rendimiento, 
    ISNULL(CAST(dp.kilos_entrega AS DECIMAL(10, 2)), 0) AS total_kilos_entrega, 
    ISNULL(CAST(dp.kilos_recepcion AS DECIMAL(10, 2)), 0) AS total_kilos_recepcion
FROM dbo.planillas_pst AS p 
LEFT OUTER JOIN dbo.detalle_planilla_pst AS dp ON p.cod_planilla = dp.cod_planilla 
LEFT OUTER JOIN dbo.registro_planilla_pst AS r ON p.cod_planilla = r.cod_planilla 
LEFT OUTER JOIN bdsystem.dbo.turno AS t ON p.cod_turno = t.CodTurno 
LEFT OUTER JOIN dbo.v_data_usuario AS u ON p.cod_supervisor = u.cod_usuario
WHERE (p.guardado = 1)
GROUP BY 
    p.fec_turno, p.cod_turno, t.NomTurno, p.cod_supervisor, u.nombre, 
    dp.dotacion, dp.productividad, dp.rendimiento, dp.kilos_entrega, dp.kilos_recepcion
GO
PRINT '  ✓ v_informe_por_turno'
GO

-- Vista: v_informe_turno_productos
IF OBJECT_ID(N'[dbo].[v_informe_turno_productos]', 'V') IS NOT NULL
    DROP VIEW [dbo].[v_informe_turno_productos]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[v_informe_turno_productos] AS
SELECT
    i.cod_informe,
    i.fecha_turno,
    i.cod_turno,
    tt.nombre AS nombre_turno,
    s.cod_sala,
    s.nombre AS nombre_sala,
    tp.cod_tipo_planilla,
    tp.nombre AS tipo_planilla,
    emp.descripcion AS empresa,
    dpe.especie,
    dpe.corte_inicial,
    dpe.corte_final,
    dpe.calibre,
    dpe.calidad,
    dpe.destino,
    dpe.es_producto_objetivo,
    SUM(dpe.kilos) AS kilos
FROM pst.dbo.informes_turno i
JOIN pst.dbo.planillas_pst p ON p.fec_turno = i.fecha_turno AND p.cod_turno = i.cod_turno
JOIN pst.dbo.detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
JOIN pst.dbo.sala s ON dp.cod_sala = s.cod_sala
JOIN pst.dbo.tipo_planilla tp ON p.cod_tipo_planilla = tp.cod_tipo_planilla
JOIN bdsystem.dbo.empresas emp ON p.cod_empresa = emp.cod_empresa
JOIN administracion.dbo.tipos_turno tt ON i.cod_turno = tt.id
OUTER APPLY (
    SELECT *
    FROM pst.dbo.fn_GetDetalleProcesamiento(i.fecha_turno, i.cod_turno) dpe
    WHERE dpe.cod_planilla = p.cod_planilla
      AND dpe.cod_sala = dp.cod_sala
      AND dpe.cod_tipo_planilla = tp.cod_tipo_planilla
      AND dpe.descripcion = emp.descripcion
) dpe
WHERE dpe.especie IS NOT NULL
GROUP BY
    i.cod_informe, i.fecha_turno, i.cod_turno, tt.nombre,
    s.cod_sala, s.nombre, tp.cod_tipo_planilla, tp.nombre,
    emp.descripcion, dpe.especie, dpe.corte_inicial, dpe.corte_final,
    dpe.calibre, dpe.calidad, dpe.destino, dpe.es_producto_objetivo
GO
PRINT '  ✓ v_informe_turno_productos'
GO

-- Vista: v_informe_turno_indicadores
IF OBJECT_ID(N'[dbo].[v_informe_turno_indicadores]', 'V') IS NOT NULL
    DROP VIEW [dbo].[v_informe_turno_indicadores]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[v_informe_turno_indicadores] AS
SELECT
    i.cod_informe,
    i.fecha_turno,
    i.cod_turno,
    tt.nombre AS nombre_turno,
    s.cod_sala,
    s.nombre AS nombre_sala,
    tp.cod_tipo_planilla,
    tp.nombre AS tipo_planilla,
    emp.descripcion AS empresa,
    emp.cod_empresa,
    COUNT(DISTINCT p.cod_planilla) as total_planillas,
    SUM(dp.dotacion) as dotacion_total,
    SUM(dp.kilos_entrega) as kilos_entrega,
    SUM(dp.kilos_recepcion) as kilos_recepcion,
    CAST(AVG(p.tiempo_trabajado) AS DECIMAL(10, 2)) as horas_trabajadas,
    CASE 
        WHEN SUM(dp.kilos_entrega) > 0 
        THEN CAST((SUM(dp.kilos_recepcion) * 100.0 / SUM(dp.kilos_entrega)) AS DECIMAL(10, 2))
        ELSE 0 END AS rendimiento,
    cs.comentarios AS comentario_sala
FROM pst.dbo.informes_turno i
JOIN pst.dbo.planillas_pst p ON p.fec_turno = i.fecha_turno AND p.cod_turno = i.cod_turno
JOIN pst.dbo.detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
JOIN pst.dbo.sala s ON dp.cod_sala = s.cod_sala
JOIN pst.dbo.tipo_planilla tp ON p.cod_tipo_planilla = tp.cod_tipo_planilla
JOIN bdsystem.dbo.empresas emp ON p.cod_empresa = emp.cod_empresa
JOIN administracion.dbo.tipos_turno tt ON i.cod_turno = tt.id
LEFT JOIN pst.dbo.comentarios_informe_sala cs ON cs.cod_informe = i.cod_informe AND cs.cod_sala = s.cod_sala
OUTER APPLY (
    SELECT TOP 1 *
    FROM pst.dbo.fn_GetHorariosTurno(i.fecha_turno, i.cod_turno)
) ht
WHERE p.guardado = 1
GROUP BY
    i.cod_informe, i.fecha_turno, i.cod_turno,tt.nombre,
    s.cod_sala, s.nombre, tp.cod_tipo_planilla, tp.nombre,
    emp.descripcion, emp.cod_empresa, cs.comentarios, ht.horas_trabajadas
GO
PRINT '  ✓ v_informe_turno_indicadores'
GO

-- Vista: v_informe_turno_tiempos_muertos
IF OBJECT_ID(N'[dbo].[v_informe_turno_tiempos_muertos]', 'V') IS NOT NULL
    DROP VIEW [dbo].[v_informe_turno_tiempos_muertos]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[v_informe_turno_tiempos_muertos] AS
SELECT
    i.cod_informe,
    i.fecha_turno,
    i.cod_turno,
    tt.nombre AS nombre_turno,
    tm.cod_sala,
    s.nombre AS nombre_sala,
    tm.cod_tipo_planilla,
    tp.nombre AS tipo_planilla,
    tm.nombre AS departamento,
    tm.motivo,
    tm.duracion_minutos,
    tm.total_minutos_sala_tipo
FROM pst.dbo.informes_turno i
JOIN administracion.dbo.tipos_turno tt ON i.cod_turno = tt.id
OUTER APPLY pst.dbo.fn_GetTiemposMuertos(i.fecha_turno, i.cod_turno) tm
JOIN pst.dbo.sala s ON tm.cod_sala = s.cod_sala
JOIN pst.dbo.tipo_planilla tp ON tm.cod_tipo_planilla = tp.cod_tipo_planilla
GO
PRINT '  ✓ v_informe_turno_tiempos_muertos'
GO

-- Vista: v_informe_turno_planillas
IF OBJECT_ID(N'[dbo].[v_informe_turno_planillas]', 'V') IS NOT NULL
    DROP VIEW [dbo].[v_informe_turno_planillas]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[v_informe_turno_planillas] AS
SELECT
    i.cod_informe,
    i.fecha_turno,
    i.cod_turno,
    tt.nombre AS nombre_turno,
    s.cod_sala,
    s.nombre AS nombre_sala,
    tp.cod_tipo_planilla,
    tp.nombre AS tipo_planilla,
    emp.descripcion AS empresa,
    p.cod_planilla AS numero_planilla,
    CONCAT(u.nombre, ' ', u.apellido) AS trabajador_nombre,
    dp.dotacion,
    p.tiempo_trabajado AS horas_trabajadas,
    dp.kilos_entrega,
    dp.kilos_recepcion AS pst_total
FROM pst.dbo.informes_turno i
JOIN pst.dbo.planillas_pst p ON p.fec_turno = i.fecha_turno AND p.cod_turno = i.cod_turno
LEFT JOIN pst.dbo.usuarios_pst u ON p.cod_planillero = u.cod_usuario
JOIN pst.dbo.detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
JOIN pst.dbo.sala s ON dp.cod_sala = s.cod_sala
JOIN pst.dbo.tipo_planilla tp ON p.cod_tipo_planilla = tp.cod_tipo_planilla
JOIN bdsystem.dbo.empresas emp ON p.cod_empresa = emp.cod_empresa
JOIN administracion.dbo.tipos_turno tt ON i.cod_turno = tt.id
GO
PRINT '  ✓ v_informe_turno_planillas'
GO

-- Vista: v_produccion_excel
IF OBJECT_ID(N'[dbo].[v_produccion_excel]', 'V') IS NOT NULL
    DROP VIEW [dbo].[v_produccion_excel]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[v_produccion_excel] AS
SELECT 
    p.fec_turno, 
    t.NomTurno AS turno, 
    l.nombre AS lote, 
    e.descripcion AS especie, 
    sp.nombre AS proceso, 
    emp.descripcion AS cliente,
    prov.descripcion AS proveedor,
    ci.nombre AS corte_inicial, 
    cf.nombre AS corte_final, 
    cal.nombre AS calidad, 
    cab.nombre AS calibre, 
    d.nombre AS destino, 
    dp.piezas_entrega AS piezas_iniciales, 
    dp.piezas_recepcion AS piezas_finales, 
    dp.kilos_entrega AS kilos_iniciales, 
    dp.kilos_recepcion AS kilos_finales, 
    SUM(rp.piezas) AS piezas_totales, 
    SUM(rp.kilos) AS kilos_totales, 
    CASE WHEN SUM(rp.piezas) > 0 
         THEN ROUND(SUM(rp.kilos) / SUM(rp.piezas), 2) 
         ELSE 0 
    END AS peso_promedio
FROM dbo.planillas_pst AS p 
INNER JOIN bdsystem.dbo.turno AS t ON p.cod_turno = t.CodTurno 
INNER JOIN bdsystem.dbo.lotes AS l ON p.cod_lote = l.cod_lote 
INNER JOIN bdsystem.dbo.especies AS e ON p.cod_especie = e.cod_especie 
INNER JOIN bdsystem.dbo.subproceso AS sp ON p.cod_proceso = sp.cod_sproceso 
INNER JOIN bdsystem.dbo.empresas AS emp ON p.cod_empresa = emp.cod_empresa
INNER JOIN bdsystem.dbo.detalle_lote AS dl ON p.cod_lote = dl.cod_lote
INNER JOIN bdsystem.dbo.proveedores AS prov ON dl.cod_proveedor = prov.cod_proveedor
INNER JOIN dbo.registro_planilla_pst AS rp ON p.cod_planilla = rp.cod_planilla 
INNER JOIN dbo.detalle_planilla_pst AS dp ON p.cod_planilla = dp.cod_planilla 
INNER JOIN dbo.corte AS ci ON rp.cod_corte_ini = ci.cod_corte 
INNER JOIN dbo.corte AS cf ON rp.cod_corte_fin = cf.cod_corte 
INNER JOIN dbo.calidad AS cal ON rp.cod_calidad = cal.cod_cald 
INNER JOIN dbo.calibre AS cab ON rp.cod_calibre = cab.cod_calib 
INNER JOIN dbo.destino AS d ON rp.cod_destino = d.cod_destino
WHERE p.guardado = 1
GROUP BY 
    p.fec_turno, t.NomTurno, l.nombre, e.descripcion, sp.nombre, 
    emp.descripcion, prov.descripcion, ci.nombre, cf.nombre, 
    cal.nombre, cab.nombre, d.nombre, dp.piezas_entrega, 
    dp.piezas_recepcion, dp.kilos_entrega, dp.kilos_recepcion
GO
PRINT '  ✓ v_produccion_excel'
GO

-- Vista: v_resumen_diario
IF OBJECT_ID(N'[dbo].[v_resumen_diario]', 'V') IS NOT NULL
    DROP VIEW [dbo].[v_resumen_diario]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[v_resumen_diario] AS
SELECT 
    p.fec_turno,
    COUNT(DISTINCT p.cod_planilla) as total_planillas,
    SUM(d.dotacion) as total_dotacion,
    SUM(d.cajas_recepcion) as total_cajas_recepcion,
    SUM(d.kilos_recepcion) as total_kilos_recepcion,
    SUM(d.piezas_recepcion) as total_piezas_recepcion,
    SUM(d.cajas_entrega) as total_cajas_entrega,
    SUM(d.kilos_entrega) as total_kilos_entrega,
    SUM(d.piezas_entrega) as total_piezas_entrega,
    CAST(SUM(d.kilos_entrega) / NULLIF(SUM(d.dotacion), 0) AS DECIMAL(10,2)) as kg_persona,
    CAST(SUM(d.piezas_entrega) / NULLIF(SUM(d.dotacion), 0) AS DECIMAL(10,2)) as piezas_persona,
    CAST((SUM(d.kilos_entrega) * 100.0) / NULLIF(SUM(d.kilos_recepcion), 0) AS DECIMAL(10,2)) as porcentaje_rendimiento_kilos,
    CAST((SUM(d.piezas_entrega) * 100.0) / NULLIF(SUM(d.piezas_recepcion), 0) AS DECIMAL(10,2)) as porcentaje_rendimiento_piezas
FROM planillas_pst p
INNER JOIN detalle_planilla_pst d ON p.cod_planilla = d.cod_planilla
WHERE p.guardado = 1
GROUP BY p.fec_turno
GO
PRINT '  ✓ v_resumen_diario'
GO

-- Vista: v_resumen_informes
IF OBJECT_ID(N'[dbo].[v_resumen_informes]', 'V') IS NOT NULL
    DROP VIEW [dbo].[v_resumen_informes]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[v_resumen_informes] AS
SELECT 
    i.cod_informe,
    i.fecha_turno,
    i.cod_turno,
    CASE i.cod_turno 
        WHEN 1 THEN 'Día'
        WHEN 2 THEN 'Tarde'
        WHEN 3 THEN 'Noche'
    END AS turno_nombre,
    CONCAT(u.nombre, ' ', u.apellido) AS jefe_turno,
    i.fecha_creacion,
    i.estado,
    COUNT(DISTINCT di.cod_sala) as total_salas,
    SUM(di.dotacion_real) as dotacion_total,
    SUM(di.kilos_recepcion) as kilos_total,
    SUM(di.horas_trabajadas) as horas_totales
FROM informes_turno i
LEFT JOIN detalle_informe_sala di ON i.cod_informe = di.cod_informe
LEFT JOIN usuarios_pst u ON i.cod_jefe_turno = u.cod_usuario
GROUP BY 
    i.cod_informe, i.fecha_turno, i.cod_turno, i.fecha_creacion, 
    i.estado, u.nombre, u.apellido
GO
PRINT '  ✓ v_resumen_informes'
GO

-- Vista: v_resumen_turno
IF OBJECT_ID(N'[dbo].[v_resumen_turno]', 'V') IS NOT NULL
    DROP VIEW [dbo].[v_resumen_turno]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[v_resumen_turno] AS
SELECT DISTINCT 
    p.fec_turno AS fecha,
    tt.nombre AS turno,
    p.cod_turno,
    COUNT(DISTINCT p.cod_planilla) AS total_planillas,
    SUM(d.dotacion) AS dotacion_total,
    SUM(d.kilos_entrega) AS kilos_entrega_total,
    SUM(d.kilos_recepcion) AS kilos_recepcion_total,
    SUM(d.piezas_entrega) AS piezas_entrega_total,
    SUM(d.piezas_recepcion) AS piezas_recepcion_total
FROM planillas_pst p
INNER JOIN detalle_planilla_pst d ON p.cod_planilla = d.cod_planilla
INNER JOIN administracion.dbo.tipos_turno tt ON p.cod_turno = tt.id
WHERE p.guardado = 1 AND tt.activo = 1
GROUP BY p.fec_turno, tt.nombre, p.cod_turno
GO
PRINT '  ✓ v_resumen_turno'
GO

-- Vista: vista_informe_turno
IF OBJECT_ID(N'[dbo].[vista_informe_turno]', 'V') IS NOT NULL
    DROP VIEW [dbo].[vista_informe_turno]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[vista_informe_turno] AS
SELECT 
    i.fecha_turno AS Fecha,
    t.NomTurno AS Turno,
    s.nombre AS Sala,
    CONCAT(u.nombre, ' ', u.apellido) AS [Jefe Turno],
    di.dotacion_real AS [Dotacion Real],
    di.dotacion_esperada AS [Dotacion Esperada],
    di.kilos_recepcion AS [Kilos Total],
    NULL AS [Kilos Producto Objetivo],
    NULL AS [h.incial real],
    NULL AS [h.termino real],
    NULL AS [h.trabajadas real],
    NULL AS Productividad,
    NULL AS [h.incial tecnico],
    NULL AS [h.termino tecnico],
    NULL AS [h.trabajadas tecnico]
FROM pst.dbo.informes_turno i
JOIN pst.dbo.detalle_informe_sala di ON i.cod_informe = di.cod_informe
JOIN bdsystem.dbo.turno t ON t.CodTurno = i.cod_turno
JOIN pst.dbo.sala s ON s.cod_sala = di.cod_sala
JOIN pst.dbo.usuarios_pst u ON u.cod_usuario = i.cod_jefe_turno
GO
PRINT '  ✓ vista_informe_turno'
GO

-- Vista: vw_analisis_informes
IF OBJECT_ID(N'[dbo].[vw_analisis_informes]', 'V') IS NOT NULL
    DROP VIEW [dbo].[vw_analisis_informes]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[vw_analisis_informes] AS
SELECT 
    i.cod_informe, 
    i.fecha_turno, 
    i.cod_turno, 
    CASE i.cod_turno 
        WHEN 1 THEN 'Día' 
        WHEN 2 THEN 'Tarde' 
        WHEN 3 THEN 'Noche' 
    END AS turno_nombre, 
    i.cod_jefe_turno, 
    u.nombre AS jefe_turno_nombre, 
    i.fecha_creacion, 
    s.cod_sala, 
    s.nombre, 
    d.tipo_planilla, 
    d.dotacion_real, 
    d.dotacion_esperada, 
    CASE 
        WHEN d.dotacion_esperada > 0 
        THEN ((d.dotacion_esperada - d.dotacion_real) * 100.0 / d.dotacion_esperada) 
        ELSE 0 
    END AS porcentaje_ausentismo, 
    d.kilos_entrega, 
    d.kilos_recepcion, 
    d.kilos_premium, 
    d.premium AS porcentaje_premium, 
    d.horas_trabajadas, 
    d.tiempo_muerto_minutos, 
    d.horas_trabajadas * 60 - d.tiempo_muerto_minutos AS minutos_efectivos, 
    d.rendimiento, 
    d.productividad, 
    CASE 
        WHEN d.horas_trabajadas > 0 AND d.dotacion_real > 0 
        THEN d.kilos_recepcion / (d.horas_trabajadas * d.dotacion_real) 
        ELSE 0 
    END AS productividad_kg_hora_persona, 
    CASE 
        WHEN d.kilos_entrega > 0 
        THEN (d.kilos_premium * 100.0 / d.kilos_entrega) 
        ELSE 0 
    END AS rendimiento_premium, 
    DATEPART(WEEK, i.fecha_turno) AS semana, 
    DATEPART(MONTH, i.fecha_turno) AS mes, 
    DATEPART(YEAR, i.fecha_turno) AS año, 
    DATEPART(WEEKDAY, i.fecha_turno) + 1 AS dia_semana, 
    CASE 
        WHEN d.horas_trabajadas > 0 
        THEN (d.tiempo_muerto_minutos * 100.0 / (d.horas_trabajadas * 60)) 
        ELSE 0 
    END AS porcentaje_tiempo_muerto, 
    i.comentarios, 
    i.estado, 
    d.piezas_entrega, 
    d.piezas_recepcion
FROM dbo.informes_turno AS i 
INNER JOIN dbo.detalle_informe_sala AS d ON i.cod_informe = d.cod_informe 
INNER JOIN dbo.sala AS s ON d.cod_sala = s.cod_sala 
LEFT OUTER JOIN dbo.usuarios_pst AS u ON i.cod_jefe_turno = u.cod_usuario
WHERE (i.estado = 1)
GO
PRINT '  ✓ vw_analisis_informes'
GO

PRINT '✓ Vistas creadas correctamente'
PRINT ''
GO

-- ============================================================================
-- FINALIZACIÓN
-- ============================================================================

PRINT '================================================'
PRINT '  SCRIPT DE ESTRUCTURA COMPLETADO'
PRINT '================================================'
PRINT ''
PRINT 'RESUMEN:'
PRINT '  - Base de datos: PST'
PRINT '  - Tablas: 16 tablas creadas'
PRINT '  - Funciones: 7 funciones creadas'
PRINT '  - Vistas: 17 vistas creadas'
PRINT ''
PRINT 'NOTAS IMPORTANTES:'
PRINT '  1. Este script NO incluye datos, solo estructura'
PRINT '  2. Las bases de datos "bdsystem" y "administracion" deben existir'
PRINT '  3. Ejecutar en SQL Server 2012 o superior'
PRINT '  4. Revisar y ajustar permisos según necesidad'
PRINT ''
PRINT '¡Script ejecutado exitosamente!'
PRINT ''
GO

USE [master]
GO

