DROP VIEW v_data_usuario;
DROP VIEW v_planilla_pst;
DROP VIEW v_registro_planilla_pst;
DROP VIEW v_planillas_pst_excel;


CREATE VIEW v_data_usuario AS

SELECT
    pst_2.dbo.usuarios_pst.cod_usuario,
    pst_2.dbo.usuarios_pst.usuario,
    pst_2.dbo.usuarios_pst.nombre + ' ' + pst_2.dbo.usuarios_pst.apellido AS nombre,
	pst_2.dbo.usuarios_pst.nombre AS snombre,
	pst_2.dbo.usuarios_pst.apellido AS sapellido,
    pst_2.dbo.usuarios_pst.pass,
    pst_2.dbo.usuarios_pst.cod_rol,
    pst_2.dbo.roles.nombre_rol AS rol,
	pst_2.dbo.usuarios_pst.activo
FROM
    pst_2.dbo.usuarios_pst
INNER JOIN
    pst_2.dbo.roles ON pst_2.dbo.roles.cod_rol = pst_2.dbo.usuarios_pst.cod_rol;


-- v_planilla_pst

CREATE VIEW v_planilla_pst AS


SELECT DISTINCT TOP (100) PERCENT
    pst_2.dbo.planillas_pst.cod_planilla,
    bdsystem.dbo.lotes.nombre AS lote,
    pst_2.dbo.planillas_pst.fec_turno,
    bdsystem.dbo.turno.NomTurno AS turno,
    bdsystem.dbo.empresas.descripcion AS empresa,
    bdsystem.dbo.proveedores.descripcion AS proveedor,
    bdsystem.dbo.especies.descripcion AS especie,
    bdsystem.dbo.subproceso.nombre AS proceso,
    pst_2.dbo.planillas_pst.cod_planillero,
    planillero.nombre + ' ' + planillero.apellido AS planillero_nombre,
    pst_2.dbo.planillas_pst.cod_supervisor,
    supervisor.nombre + ' ' + supervisor.apellido AS supervisor_nombre,
    pst_2.dbo.planillas_pst.cod_jefe_turno,
    jefe_turno.nombre + ' ' + jefe_turno.apellido AS jefe_turno_nombre,
    pst_2.dbo.planillas_pst.guardado,
    pst_2.dbo.planillas_pst.fec_crea_planilla,
    user_crea.cod_usuario AS cod_usuario_crea,
    user_crea.usuario AS usuario_crea,
    pst_2.dbo.sala.nombre as sala,
    CONVERT(TIME(0), pst_2.dbo.planillas_pst.hora_inicio) as hora_inicio,
    CONVERT(TIME(0), pst_2.dbo.planillas_pst.hora_termino) as hora_termino,
    pst_2.dbo.planillas_pst.cod_tipo_planilla,
    tp.nombre AS tipo_planilla_nombre
FROM
    pst_2.dbo.planillas_pst
LEFT OUTER JOIN bdsystem.dbo.lotes ON pst_2.dbo.planillas_pst.cod_lote = bdsystem.dbo.lotes.cod_lote
LEFT OUTER JOIN bdsystem.dbo.turno ON pst_2.dbo.planillas_pst.cod_turno = bdsystem.dbo.turno.CodTurno
LEFT OUTER JOIN bdsystem.dbo.empresas ON pst_2.dbo.planillas_pst.cod_empresa = bdsystem.dbo.empresas.cod_empresa
LEFT OUTER JOIN bdsystem.dbo.detalle_lote ON pst_2.dbo.planillas_pst.cod_lote = bdsystem.dbo.detalle_lote.cod_lote
LEFT OUTER JOIN bdsystem.dbo.proveedores ON bdsystem.dbo.detalle_lote.cod_proveedor = bdsystem.dbo.proveedores.cod_proveedor
LEFT OUTER JOIN bdsystem.dbo.especies ON pst_2.dbo.planillas_pst.cod_especie = bdsystem.dbo.especies.cod_especie
LEFT OUTER JOIN bdsystem.dbo.subproceso ON pst_2.dbo.planillas_pst.cod_proceso = bdsystem.dbo.subproceso.cod_sproceso
LEFT OUTER JOIN pst_2.dbo.usuarios_pst AS planillero ON pst_2.dbo.planillas_pst.cod_planillero = planillero.cod_usuario
LEFT OUTER JOIN pst_2.dbo.usuarios_pst AS supervisor ON pst_2.dbo.planillas_pst.cod_supervisor = supervisor.cod_usuario
LEFT OUTER JOIN pst_2.dbo.usuarios_pst AS jefe_turno ON pst_2.dbo.planillas_pst.cod_jefe_turno = jefe_turno.cod_usuario
LEFT OUTER JOIN pst_2.dbo.usuarios_pst AS user_crea ON pst_2.dbo.planillas_pst.cod_usuario_crea_planilla = user_crea.cod_usuario
LEFT OUTER JOIN pst_2.dbo.detalle_planilla_pst AS detalle ON pst_2.dbo.planillas_pst.cod_planilla = detalle.cod_planilla
LEFT OUTER JOIN pst_2.dbo.sala ON pst_2.dbo.sala.cod_sala = detalle.cod_sala
LEFT OUTER JOIN pst_2.dbo.tipo_planilla tp ON pst_2.dbo.planillas_pst.cod_tipo_planilla = tp.cod_tipo_planilla
ORDER BY
    pst_2.dbo.planillas_pst.fec_turno,
    pst_2.dbo.planillas_pst.fec_crea_planilla;


-- v_registro_planilla_pst

CREATE VIEW v_registro_planilla_pst AS

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
    rp.guardado
FROM
    pst_2.dbo.registro_planilla_pst AS rp
LEFT OUTER JOIN
    pst_2.dbo.corte AS ini ON rp.cod_corte_ini = ini.cod_corte
LEFT OUTER JOIN
    pst_2.dbo.corte AS fin ON rp.cod_corte_fin = fin.cod_corte
LEFT OUTER JOIN
    pst_2.dbo.calibre AS c ON rp.cod_calibre = c.cod_calib
LEFT OUTER JOIN
    pst_2.dbo.calidad AS ca ON rp.cod_calidad = ca.cod_cald
LEFT OUTER JOIN
    pst_2.dbo.destino AS de ON rp.cod_destino = de.cod_destino;



CREATE VIEW v_planillas_pst_excel AS 

SELECT DISTINCT TOP (100) PERCENT
	pst_2.dbo.v_registro_planilla_pst.cod_reg,
    pst_2.dbo.planillas_pst.cod_planilla,
    bdsystem.dbo.lotes.nombre AS lote,
    pst_2.dbo.planillas_pst.fec_turno,
    bdsystem.dbo.turno.NomTurno AS turno,
    bdsystem.dbo.empresas.descripcion AS empresa,
    bdsystem.dbo.proveedores.descripcion AS proveedor,
    bdsystem.dbo.especies.descripcion AS especie,
    bdsystem.dbo.subproceso.nombre AS proceso,
    planillero.nombre + ' ' + planillero.apellido AS planillero_nombre,
    supervisor.nombre + ' ' + supervisor.apellido AS supervisor_nombre,
    pst_2.dbo.planillas_pst.fec_crea_planilla,
    pst_2.dbo.v_planilla_pst.usuario_crea,
	pst_2.dbo.v_registro_planilla_pst.cInicial,
	pst_2.dbo.v_registro_planilla_pst.cFinal,
	pst_2.dbo.v_registro_planilla_pst.destino,
	pst_2.dbo.v_registro_planilla_pst.calibre,
	pst_2.dbo.v_registro_planilla_pst.calidad,
	pst_2.dbo.v_registro_planilla_pst.piezas,
	pst_2.dbo.v_registro_planilla_pst.kilos,
	pst_2.dbo.detalle_planilla_pst.cajas_entrega AS cajas_ef,
	pst_2.dbo.detalle_planilla_pst.piezas_entrega AS piezas_ef,
	pst_2.dbo.detalle_planilla_pst.kilos_entrega AS kilos_ef, 
	pst_2.dbo.detalle_planilla_pst.cajas_recepcion AS cajas_rp,
	pst_2.dbo.detalle_planilla_pst.piezas_recepcion AS piezas_rp,
	pst_2.dbo.detalle_planilla_pst.kilos_recepcion AS kilos_rp,
	pst_2.dbo.detalle_planilla_pst.dotacion AS dotacion,
	pst_2.dbo.sala.nombre AS sala,
	pst_2.dbo.detalle_planilla_pst.observacion
FROM
    pst_2.dbo.planillas_pst
INNER JOIN bdsystem.dbo.lotes ON pst_2.dbo.planillas_pst.cod_lote = bdsystem.dbo.lotes.cod_lote
INNER JOIN bdsystem.dbo.turno ON pst_2.dbo.planillas_pst.cod_turno = bdsystem.dbo.turno.CodTurno
INNER JOIN bdsystem.dbo.empresas ON pst_2.dbo.planillas_pst.cod_empresa = bdsystem.dbo.empresas.cod_empresa
INNER JOIN bdsystem.dbo.detalle_lote ON pst_2.dbo.planillas_pst.cod_lote = bdsystem.dbo.detalle_lote.cod_lote
INNER JOIN bdsystem.dbo.proveedores ON bdsystem.dbo.detalle_lote.cod_proveedor = bdsystem.dbo.proveedores.cod_proveedor
INNER JOIN bdsystem.dbo.especies ON pst_2.dbo.planillas_pst.cod_especie = bdsystem.dbo.especies.cod_especie
INNER JOIN bdsystem.dbo.subproceso ON pst_2.dbo.planillas_pst.cod_proceso = bdsystem.dbo.subproceso.cod_sproceso
INNER JOIN pst_2.dbo.usuarios_pst AS planillero ON pst_2.dbo.planillas_pst.cod_planillero = planillero.cod_usuario
INNER JOIN pst_2.dbo.usuarios_pst AS supervisor ON pst_2.dbo.planillas_pst.cod_supervisor = supervisor.cod_usuario

INNER JOIN v_registro_planilla_pst ON pst_2.dbo.v_registro_planilla_pst.cod_planilla = pst_2.dbo.planillas_pst.cod_planilla
INNER JOIN v_planilla_pst ON pst_2.dbo.v_planilla_pst.cod_planilla = pst_2.dbo.planillas_pst.cod_planilla
INNER JOIN pst_2.dbo.detalle_planilla_pst ON pst_2.dbo.planillas_pst.cod_planilla = pst_2.dbo.detalle_planilla_pst.cod_planilla
INNER JOIN pst_2.dbo.sala ON pst_2.dbo.detalle_planilla_pst.cod_sala = pst_2.dbo.sala.cod_sala

WHERE pst_2.dbo.planillas_pst.guardado = 1
-- Rango de fecha de planillas (Formato yyyy-mm-dd)
AND pst_2.dbo.planillas_pst.fec_turno >= '2024-02-01' -- Fecha de inicio 
AND pst_2.dbo.planillas_pst.fec_turno <= '2024-03-01' -- Fecha de fin
ORDER BY
    pst_2.dbo.planillas_pst.fec_turno,
    pst_2.dbo.planillas_pst.fec_crea_planilla;








-- v_informe_por_turno

CREATE OR ALTER VIEW pst_2.dbo.v_informe_por_turno AS
SELECT 
    p.fec_turno as fecha,
    p.cod_turno,
    t.NomTurno as nombre_turno,
    p.cod_supervisor,
    u.nombre as nombre_supervisor,
    COUNT(DISTINCT r.cod_planilla) as total_registros,
    ISNULL(dp.dotacion, 0) as total_dotacion,
    ISNULL(CAST(dp.productividad AS DECIMAL(10,2)), 0) as promedio_productividad,
    ISNULL(CAST(dp.rendimiento AS DECIMAL(10,2)), 0) as promedio_rendimiento,
    ISNULL(CAST(dp.kilos_entrega AS DECIMAL(10,2)), 0) as total_kilos_entrega,
    ISNULL(CAST(dp.kilos_recepcion AS DECIMAL(10,2)), 0) as total_kilos_recepcion
FROM pst_2.dbo.planillas_pst p
LEFT JOIN pst_2.dbo.detalle_planilla_pst dp 
    ON p.cod_planilla = dp.cod_planilla
LEFT JOIN pst_2.dbo.registro_planilla_pst r 
    ON p.cod_planilla = r.cod_planilla
LEFT JOIN bdsystem.dbo.turno t 
    ON p.cod_turno = t.codTurno
LEFT JOIN pst_2.dbo.v_data_usuario u 
    ON p.cod_supervisor = u.cod_usuario
WHERE p.guardado = 1
GROUP BY 
    p.fec_turno,
    p.cod_turno,
    t.NomTurno,
    p.cod_supervisor,
    u.nombre,
    dp.dotacion,
    dp.productividad,
    dp.rendimiento,
    dp.kilos_entrega,
    dp.kilos_recepcion;



-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

-- informacion de informes

DECLARE @fecha DATE = '2024-01-05';
DECLARE @turno INT = 1; -- 1 para día, 2 para tarde, 3 para noche

-- informes de turno y dia 

DECLARE @fecha DATE = '2024-01-05';
DECLARE @turno INT = 1; -- 1 para día, 2 para tarde, 3 para noche
SELECT 
	@fecha as fecha,
    t.NomTurno as turno,
    CONCAT(u.nombre, ' ', u.apellido) as jefe_turno,
	COUNT(DISTINCT p.cod_planilla) as cantidad_planillas,
    -- Dotación promedio (asumiendo que hay un campo dotacion en detalle_planilla_pst)
    AVG(dp.dotacion) as dotacion_promedio,
    -- Productividad promedio con 2 decimales
    ROUND(AVG(dp.productividad), 2) as productividad_promedio,
    -- Totales de kilos
    SUM(dp.kilos_entrega) as total_kilos_entrega,
    SUM(dp.kilos_recepcion) as total_kilos_recepcion
    
FROM pst_2.dbo.planillas_pst p
JOIN bdsystem.dbo.turno t ON p.cod_turno = t.CodTurno
JOIN pst_2.dbo.usuarios_pst u ON p.cod_jefe_turno = u.cod_usuario
JOIN pst_2.dbo.detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
WHERE p.fec_turno = @fecha
GROUP BY 
    t.NomTurno,
    u.nombre,
    u.apellido
ORDER BY 
    CASE 
        WHEN t.NomTurno LIKE '%Día%' THEN 1
        WHEN t.NomTurno LIKE '%Tarde%' THEN 2
        WHEN t.NomTurno LIKE '%Noche%' THEN 3
    END;

-- informacion de informes


-- 1. Información básica por sala
SELECT 
    s.nombre as nombre_sala,
    dp.cod_sala,
    tp.nombre as tipo_planilla,
	tp.cod_tipo_planilla,
    COUNT(DISTINCT p.cod_planilla) as cantidad_planillas,
    AVG(dp.dotacion) as dotacion_promedio,
    AVG(dp.productividad) as productividad_promedio,
    AVG(dp.rendimiento) as rendimiento_promedio,
    SUM(dp.kilos_entrega) as kilos_entrega_total,
    SUM(dp.kilos_recepcion) as kilos_recepcion_total
FROM pst_2.dbo.planillas_pst p
JOIN pst_2.dbo.detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
JOIN pst_2.dbo.sala s ON dp.cod_sala = s.cod_sala
JOIN pst_2.dbo.tipo_planilla tp ON p.cod_tipo_planilla = tp.cod_tipo_planilla
WHERE p.fec_turno = @fecha 
AND p.cod_turno = @turno
GROUP BY 
    s.nombre,
    dp.cod_sala,
    tp.nombre,
	tp.cod_tipo_planilla
ORDER BY 
    dp.cod_sala,
    tp.nombre;
-- 2. Detalle de procesamiento por sala
SELECT 
    dp.cod_sala,
    tp.cod_tipo_planilla,
    tp.nombre as tipo_planilla,
    c_ini.nombre as corte_inicial,
    c_fin.nombre as corte_final,
    d.nombre as destino,
    cal.nombre as calibre,
    cld.nombre as calidad,
    rp.piezas,
    rp.kilos,
    SUM(rp.piezas) OVER (PARTITION BY dp.cod_sala, tp.cod_tipo_planilla) as total_piezas_sala_tipo,
    SUM(rp.kilos) OVER (PARTITION BY dp.cod_sala, tp.cod_tipo_planilla) as total_kilos_sala_tipo
FROM pst_2.dbo.planillas_pst p
JOIN pst_2.dbo.detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
JOIN pst_2.dbo.registro_planilla_pst rp ON p.cod_planilla = rp.cod_planilla
JOIN pst_2.dbo.corte c_ini ON rp.cod_corte_ini = c_ini.cod_corte
JOIN pst_2.dbo.corte c_fin ON rp.cod_corte_fin = c_fin.cod_corte
JOIN pst_2.dbo.destino d ON rp.cod_destino = d.cod_destino
JOIN pst_2.dbo.calibre cal ON rp.cod_calibre = cal.cod_calib
JOIN pst_2.dbo.calidad cld ON rp.cod_calidad = cld.cod_cald
JOIN pst_2.dbo.tipo_planilla tp ON p.cod_tipo_planilla = tp.cod_tipo_planilla
WHERE p.fec_turno = @fecha 
AND p.cod_turno = @turno
ORDER BY dp.cod_sala, tp.cod_tipo_planilla, cld.nombre, cal.nombre, c_ini.nombre, c_fin.nombre, d.nombre;

-- 3. Tiempos muertos por sala
SELECT 
    dp.cod_sala,
    tp.cod_tipo_planilla,
    tp.nombre as tipo_planilla,
    tm.causa as motivo,
    tm.duracion_minutos,
    SUM(tm.duracion_minutos) OVER (PARTITION BY dp.cod_sala, tp.cod_tipo_planilla) as total_minutos_sala_tipo
FROM pst_2.dbo.planillas_pst p
JOIN pst_2.dbo.detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
JOIN pst_2.dbo.tiempos_muertos tm ON p.cod_planilla = tm.cod_planilla
JOIN pst_2.dbo.tipo_planilla tp ON p.cod_tipo_planilla = tp.cod_tipo_planilla
WHERE p.fec_turno = @fecha 
AND p.cod_turno = @turno
ORDER BY dp.cod_sala, tp.cod_tipo_planilla;

-- 4. Planillas por sala
SELECT 
    dp.cod_sala,
    tp.cod_tipo_planilla,
    tp.nombre as tipo_planilla,
    p.cod_planilla,
    p.fec_turno,
    CONCAT(u.nombre, ' ', u.apellido) as supervisor
FROM pst_2.dbo.planillas_pst p
JOIN pst_2.dbo.detalle_planilla_pst dp ON p.cod_planilla = dp.cod_planilla
JOIN pst_2.dbo.sala s ON dp.cod_sala = s.cod_sala
JOIN bdsystem.dbo.turno t ON p.cod_turno = t.CodTurno
JOIN pst_2.dbo.usuarios_pst u ON p.cod_supervisor = u.cod_usuario
JOIN pst_2.dbo.tipo_planilla tp ON p.cod_tipo_planilla = tp.cod_tipo_planilla
WHERE p.fec_turno = @fecha 
AND p.cod_turno = @turno
ORDER BY dp.cod_sala, tp.cod_tipo_planilla, p.cod_planilla;