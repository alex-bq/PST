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
    pst_2.dbo.planillas_pst.guardado,
    pst_2.dbo.planillas_pst.fec_crea_planilla,
	user_crea.cod_usuario AS cod_usuario_crea,
	user_crea.usuario AS usuario_crea,
	pst_2.dbo.sala.nombre as sala,
	CONVERT(TIME(0), pst_2.dbo.planillas_pst.hora_inicio) as hora_inicio,
	CONVERT(TIME(0), pst_2.dbo.planillas_pst.hora_termino) as hora_termino

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
LEFT OUTER JOIN pst_2.dbo.usuarios_pst AS user_crea ON pst_2.dbo.planillas_pst.cod_usuario_crea_planilla = user_crea.cod_usuario
LEFT OUTER JOIN pst_2.dbo.detalle_planilla_pst AS detalle ON pst_2.dbo.planillas_pst.cod_planilla = detalle.cod_planilla
LEFT OUTER JOIN pst_2.dbo.sala ON pst_2.dbo.sala.cod_sala = detalle.cod_sala
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