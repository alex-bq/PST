-- Verificar que existan ambas bases de datos
IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = 'pst')
BEGIN
    RAISERROR ('La base de datos "pst" no existe.', 16, 1)
    RETURN
END

USE [pst]
GO

-- Crear nuevas tablas si no existen
-- Tabla tipo_planilla
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[tipo_planilla]') AND type in (N'U'))
BEGIN
    CREATE TABLE [dbo].[tipo_planilla](
        [cod_tipo_planilla] [int] IDENTITY(1,1) NOT NULL,
        [nombre] [nvarchar](255) NULL,
        PRIMARY KEY CLUSTERED ([cod_tipo_planilla] ASC)
    )
    
    -- Insertar tipos de planilla básicos
    INSERT INTO [dbo].[tipo_planilla] (nombre) VALUES 
    ('Filete'),
    ('Porciones'),
    ('HG')
END

-- Tabla departamentos
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[departamentos]') AND type in (N'U'))
BEGIN
    CREATE TABLE [dbo].[departamentos](
        [cod_departamento] [int] IDENTITY(1,1) NOT NULL,
        [nombre] [nvarchar](255) NULL,
        [activo] [int] DEFAULT ((1)) NULL,
        PRIMARY KEY CLUSTERED ([cod_departamento] ASC)
    )
END

-- Tabla tiempos_muertos
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[tiempos_muertos]') AND type in (N'U'))
BEGIN
    CREATE TABLE [dbo].[tiempos_muertos](
        [cod_tiempo_muerto] [int] IDENTITY(1,1) NOT NULL,
        [cod_planilla] [smallint] NULL,
        [causa] [nvarchar](max) NULL,
        [hora_inicio] [time](7) NULL,
        [hora_termino] [time](7) NULL,
        [duracion_minutos] [int] NULL,
        [cod_departamento] [int] NULL,
        PRIMARY KEY CLUSTERED ([cod_tiempo_muerto] ASC)
    )
END

-- Tabla informes_turno
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[informes_turno]') AND type in (N'U'))
BEGIN
    CREATE TABLE [dbo].[informes_turno](
        [cod_informe] [int] IDENTITY(1,1) NOT NULL,
        [fecha_turno] [date] NOT NULL,
        [cod_turno] [smallint] NOT NULL,
        [cod_jefe_turno] [numeric](18, 0) NULL,
        [cod_usuario_crea] [int] NULL,
        [comentarios] [nvarchar](max) NULL,
        [fecha_creacion] [datetime] DEFAULT (getdate()) NULL,
        [estado] [smallint] DEFAULT ((1)) NULL,
        PRIMARY KEY CLUSTERED ([cod_informe] ASC)
    )
END

-- Tabla detalle_informe_sala
IF NOT EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[detalle_informe_sala]') AND type in (N'U'))
BEGIN
    CREATE TABLE [dbo].[detalle_informe_sala](
        [cod_detalle_informe] [int] IDENTITY(1,1) NOT NULL,
        [cod_informe] [int] NULL,
        [cod_sala] [int] NULL,
        [dotacion_real] [int] NULL,
        [dotacion_esperada] [int] NULL,
        [kilos_entrega] [float] NULL,
        [kilos_recepcion] [float] NULL,
        [horas_trabajadas] [decimal](10, 2) NULL,
        [tiempo_muerto_minutos] [int] NULL,
        [rendimiento] [decimal](10, 2) NULL,
        [productividad] [decimal](10, 2) NULL,
        [piezas_entrega] [int] NULL,
        [piezas_recepcion] [int] NULL,
        [tipo_planilla] [nvarchar](255) NULL,
        [kilos_premium] [decimal](18, 2) DEFAULT ((0)) NULL,
        [premium] [decimal](5, 2) DEFAULT ((0)) NULL,
        PRIMARY KEY CLUSTERED ([cod_detalle_informe] ASC)
    )
END

-- Agregar nuevas columnas a tablas existentes
-- planillas_pst
IF NOT EXISTS(SELECT * FROM sys.columns WHERE object_id = OBJECT_ID(N'[dbo].[planillas_pst]') AND name = 'hora_inicio')
BEGIN
    ALTER TABLE [dbo].[planillas_pst] ADD
        [hora_inicio] [time](7) NULL,
        [hora_termino] [time](7) NULL,
        [cod_jefe_turno] [numeric](18, 0) NULL,
        [cod_tipo_planilla] [int] NULL,
        [tiempo_trabajado] [decimal](10, 2) NULL
END

-- detalle_planilla_pst
IF NOT EXISTS(SELECT * FROM sys.columns WHERE object_id = OBJECT_ID(N'[dbo].[detalle_planilla_pst]') AND name = 'productividad')
BEGIN
    ALTER TABLE [dbo].[detalle_planilla_pst] ADD
        [productividad] [decimal](10, 2) NULL,
        [rendimiento] [decimal](10, 2) NULL,
        [embolsado_terminado] [int] NULL,
        [kilos_terminado] [float] NULL
END

-- Agregar Foreign Keys
IF NOT EXISTS (SELECT * FROM sys.foreign_keys WHERE name = 'FK_planillas_tipo_planilla')
BEGIN
    ALTER TABLE [dbo].[planillas_pst] ADD CONSTRAINT [FK_planillas_tipo_planilla] 
    FOREIGN KEY([cod_tipo_planilla]) REFERENCES [dbo].[tipo_planilla] ([cod_tipo_planilla])
END

IF NOT EXISTS (SELECT * FROM sys.foreign_keys WHERE name = 'FK_tiempos_muertos_departamento')
BEGIN
    ALTER TABLE [dbo].[tiempos_muertos] ADD CONSTRAINT [FK_tiempos_muertos_departamento]
    FOREIGN KEY([cod_departamento]) REFERENCES [dbo].[departamentos] ([cod_departamento])
END

IF NOT EXISTS (SELECT * FROM sys.foreign_keys WHERE name = 'FK_detalle_informe_sala_informe')
BEGIN
    ALTER TABLE [dbo].[detalle_informe_sala] ADD CONSTRAINT [FK_detalle_informe_sala_informe]
    FOREIGN KEY([cod_informe]) REFERENCES [dbo].[informes_turno] ([cod_informe])
END

IF NOT EXISTS (SELECT * FROM sys.foreign_keys WHERE name = 'FK_detalle_informe_sala_sala')
BEGIN
    ALTER TABLE [dbo].[detalle_informe_sala] ADD CONSTRAINT [FK_detalle_informe_sala_sala]
    FOREIGN KEY([cod_sala]) REFERENCES [dbo].[sala] ([cod_sala])
END

IF NOT EXISTS (SELECT * FROM sys.foreign_keys WHERE name = 'FK_informes_turno_usuario')
BEGIN
    ALTER TABLE [dbo].[informes_turno] ADD CONSTRAINT [FK_informes_turno_usuario]
    FOREIGN KEY([cod_usuario_crea]) REFERENCES [dbo].[usuarios_pst] ([cod_usuario])
END

-- Crear índices
IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'idx_detalle_informe_sala_informe')
BEGIN
    CREATE NONCLUSTERED INDEX [idx_detalle_informe_sala_informe] 
    ON [dbo].[detalle_informe_sala]([cod_informe] ASC)
END

IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'idx_informes_turno_fecha')
BEGIN
    CREATE NONCLUSTERED INDEX [idx_informes_turno_fecha] 
    ON [dbo].[informes_turno]([fecha_turno] ASC)
END

-- Crear o actualizar vistas
IF EXISTS (SELECT * FROM sys.views WHERE name = 'v_resumen_diario')
    DROP VIEW [dbo].[v_resumen_diario]
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
FROM 
    pst.dbo.planillas_pst p
    INNER JOIN pst.dbo.detalle_planilla_pst d ON p.cod_planilla = d.cod_planilla
WHERE 
    p.guardado = 1
GROUP BY 
    p.fec_turno
GO

-- Vista de resumen por turno
IF EXISTS (SELECT * FROM sys.views WHERE name = 'v_resumen_turno')
    DROP VIEW [dbo].[v_resumen_turno]
GO

CREATE VIEW [dbo].[v_resumen_turno] AS
SELECT 
    p.fec_turno,
    t.NomTurno as turno,
    COUNT(DISTINCT p.cod_planilla) as total_planillas,
    SUM(d.dotacion) as total_dotacion,
    -- Datos de recepción
    SUM(d.cajas_recepcion) as total_cajas_recepcion,
    SUM(d.kilos_recepcion) as total_kilos_recepcion,
    SUM(d.piezas_recepcion) as total_piezas_recepcion,
    -- Datos de entrega
    SUM(d.cajas_entrega) as total_cajas_entrega,
    SUM(d.kilos_entrega) as total_kilos_entrega,
    SUM(d.piezas_entrega) as total_piezas_entrega,
    -- Cálculos de rendimiento
    CAST(SUM(d.kilos_entrega) / NULLIF(SUM(d.dotacion), 0) AS DECIMAL(10,2)) as kg_persona,
    CAST(SUM(d.piezas_entrega) / NULLIF(SUM(d.dotacion), 0) AS DECIMAL(10,2)) as piezas_persona
FROM 
    pst.dbo.planillas_pst p
    INNER JOIN pst.dbo.detalle_planilla_pst d ON p.cod_planilla = d.cod_planilla
    INNER JOIN bdsystem.dbo.turno t ON p.cod_turno = t.CodTurno
WHERE 
    p.guardado = 1
GROUP BY 
    p.fec_turno,
    t.NomTurno
GO

-- Vista de resumen de informes
IF EXISTS (SELECT * FROM sys.views WHERE name = 'v_resumen_informes')
    DROP VIEW [dbo].[v_resumen_informes]
GO

CREATE VIEW [dbo].[v_resumen_informes] AS
SELECT 
    i.cod_informe,
    i.fecha_turno,
    i.cod_turno,
    SUM(d.dotacion_real) as dotacion_total_real,
    SUM(d.dotacion_esperada) as dotacion_total_esperada,
    CASE 
        WHEN SUM(d.dotacion_esperada) > 0 
        THEN ((SUM(d.dotacion_esperada) - SUM(d.dotacion_real)) / SUM(d.dotacion_esperada) * 100) 
        ELSE 0 
    END as porcentaje_ausentismo,
    SUM(d.kilos_entrega) as total_kilos_entrega,
    SUM(d.kilos_recepcion) as total_kilos_recepcion,
    AVG(d.rendimiento) as rendimiento_promedio,
    AVG(d.productividad) as productividad_promedio
FROM 
    pst.dbo.informes_turno i
    LEFT JOIN pst.dbo.detalle_informe_sala d ON i.cod_informe = d.cod_informe
WHERE 
    i.estado = 1
GROUP BY 
    i.cod_informe, i.fecha_turno, i.cod_turno
GO

-- Vista de análisis de informes
IF EXISTS (SELECT * FROM sys.views WHERE name = 'vw_analisis_informes')
    DROP VIEW [dbo].[vw_analisis_informes]
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
    DATEPART(WEEKDAY, i.fecha_turno) AS dia_semana,
    CASE 
        WHEN d.horas_trabajadas > 0 
        THEN (d.tiempo_muerto_minutos * 100.0 / (d.horas_trabajadas * 60)) 
        ELSE 0 
    END AS porcentaje_tiempo_muerto,
    i.comentarios,
    i.estado,
    d.piezas_entrega,
    d.piezas_recepcion
FROM 
    dbo.informes_turno AS i 
    INNER JOIN dbo.detalle_informe_sala AS d ON i.cod_informe = d.cod_informe 
    INNER JOIN dbo.sala AS s ON d.cod_sala = s.cod_sala 
    LEFT OUTER JOIN dbo.usuarios_pst AS u ON i.cod_jefe_turno = u.cod_usuario
WHERE 
    i.estado = 1
GO

-- Funciones almacenadas
IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[fn_GetInformesDiarios]') AND type in (N'FN', N'IF', N'TF', N'FS', N'FT'))
    DROP FUNCTION [dbo].[fn_GetInformesDiarios]
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
        t.NomTurno as turno,
        CONCAT(u.nombre, ' ', u.apellido) as jefe_turno_nom,
        u.cod_usuario as jefe_turno,
        i.comentarios,
        SUM(d.dotacion_real) as dotacion_total,
        SUM(d.dotacion_esperada) as dotacion_esperada,
        SUM(d.kilos_entrega) as total_kilos_entrega,
        SUM(d.kilos_recepcion) as total_kilos_recepcion
    FROM dbo.informes_turno i
    JOIN bdsystem.dbo.turno t ON i.cod_turno = t.CodTurno
    JOIN dbo.usuarios_pst u ON i.cod_jefe_turno = u.cod_usuario
    LEFT JOIN dbo.detalle_informe_sala d ON i.cod_informe = d.cod_informe
    WHERE i.fecha_turno = @fecha AND i.estado = 1
    GROUP BY 
        i.cod_informe,
        i.fecha_turno,
        i.cod_turno,
        t.NomTurno,
        u.nombre,
        u.apellido,
        u.cod_usuario,
        i.comentarios
)
GO

-- Función para obtener información por sala
IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[fn_GetInformacionPorSala]') AND type in (N'FN', N'IF', N'TF', N'FS', N'FT'))
    DROP FUNCTION [dbo].[fn_GetInformacionPorSala]
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
        s.cod_sala,
        s.nombre as nombre_sala,
        d.tipo_planilla,
        d.dotacion_real,
        d.dotacion_esperada,
        d.kilos_entrega,
        d.kilos_recepcion,
        d.piezas_entrega,
        d.piezas_recepcion,
        d.horas_trabajadas,
        d.tiempo_muerto_minutos,
        d.rendimiento,
        d.productividad,
        d.kilos_premium,
        d.premium
    FROM dbo.informes_turno i
    JOIN dbo.detalle_informe_sala d ON i.cod_informe = d.cod_informe
    JOIN dbo.sala s ON d.cod_sala = s.cod_sala
    WHERE i.fecha_turno = @fecha 
    AND i.cod_turno = @turno 
    AND i.estado = 1
)
GO

-- Función para obtener tiempos muertos
IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[fn_GetTiemposMuertos]') AND type in (N'FN', N'IF', N'TF', N'FS', N'FT'))
    DROP FUNCTION [dbo].[fn_GetTiemposMuertos]
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
        t.cod_tiempo_muerto,
        t.causa,
        t.hora_inicio,
        t.hora_termino,
        t.duracion_minutos,
        d.nombre as departamento
    FROM dbo.tiempos_muertos t
    JOIN dbo.departamentos d ON t.cod_departamento = d.cod_departamento
    JOIN dbo.planillas_pst p ON t.cod_planilla = p.cod_planilla
    WHERE p.fec_turno = @fecha 
    AND p.cod_turno = @turno
)
GO

-- Función para obtener detalle de procesamiento
IF EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[fn_GetDetalleProcesamiento]') AND type in (N'FN', N'IF', N'TF', N'FS', N'FT'))
    DROP FUNCTION [dbo].[fn_GetDetalleProcesamiento]
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
        r.cod_reg,
        r.kilos,
        r.piezas,
        ci.nombre as corte_inicial,
        cf.nombre as corte_final,
        cal.nombre as calibre,
        cld.nombre as calidad,
        d.nombre as destino
    FROM dbo.registro_planilla_pst r
    JOIN dbo.planillas_pst p ON r.cod_planilla = p.cod_planilla
    LEFT JOIN dbo.corte ci ON r.cod_corte_ini = ci.cod_corte
    LEFT JOIN dbo.corte cf ON r.cod_corte_fin = cf.cod_corte
    LEFT JOIN dbo.calibre cal ON r.cod_calibre = cal.cod_calib
    LEFT JOIN dbo.calidad cld ON r.cod_calidad = cld.cod_cald
    LEFT JOIN dbo.destino d ON r.cod_destino = d.cod_destino
    WHERE p.fec_turno = @fecha 
    AND p.cod_turno = @turno
    AND p.guardado = 1
)
GO

PRINT 'Actualización de pst completada exitosamente'
GO