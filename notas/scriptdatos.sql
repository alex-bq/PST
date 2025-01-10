BEGIN TRY
    BEGIN TRANSACTION;

    -- Primero eliminar todos los datos existentes
    DELETE FROM pst_2.dbo.tiempos_muertos;
    DELETE FROM pst_2.dbo.detalle_planilla_pst;
    DELETE FROM pst_2.dbo.registro_planilla_pst;
    DELETE FROM pst_2.dbo.planillas_pst;

    -- Crear tabla temporal con la distribución de turnos
    CREATE TABLE #Turnos (
        turno_id INT,
        hora_inicio TIME,
        hora_termino TIME
    );

    INSERT INTO #Turnos VALUES 
    (1, '00:00', '08:00'),
    (2, '08:00', '16:00'),
    (3, '16:00', '00:00');

    -- Crear tabla temporal con los lotes específicos
    CREATE TABLE #LotesEspecificos (
        id INT IDENTITY(1,1),
        cod_lote INT,
        fecha DATE,
        turno_id INT,
        sala INT
    );

    -- Insertar los lotes específicos (3 planillas por turno = 9 por día)
    INSERT INTO #LotesEspecificos (cod_lote) VALUES 
    (194821), (194821), (194821), -- Turno 1, Día 1
    (194939), (194178), (195100), -- Turno 2, Día 1
    (195100), (195158), (195158), -- Turno 3, Día 1
    (195123), (195123), (195169), -- Turno 1, Día 2
    (195169), (195169), (195208), -- Turno 2, Día 2
    (195173), (195167), (195167), -- Turno 3, Día 2
    (195167), (195171), (195209), -- Turno 1, Día 3
    (195442), (195433), (195438), -- Turno 2, Día 3
    (195433), (195433), (195431), -- Turno 3, Día 3
    (195380), (195381), (195379), -- Turno 1, Día 4
    (195380), (195379), (195379); -- Turno 2, Día 4

    -- Actualizar fechas, turnos y salas
    UPDATE l
    SET 
        fecha = DATEADD(DAY, (id-1)/9, '2024-03-01'),
        turno_id = (((id-1) % 9) / 3) + 1,
        sala = ((id-1) % 3) + 1
    FROM #LotesEspecificos l;

    -- Insertar planillas
    INSERT INTO pst_2.dbo.planillas_pst (
        cod_lote, fec_turno, hora_inicio, hora_termino, 
        cod_turno, cod_empresa, cod_proveedor, cod_especie, 
        cod_proceso, cod_planillero, cod_supervisor, cod_jefe_turno,
        cod_usuario_crea_planilla, guardado
    )
    VALUES
        (194134, '2024-03-01', '08:00', '16:00', 1, 1, 1, 1, 1, 2, 1, 3, 7), -- Ejemplo con jefe de turno
        (194135, '2024-03-02', '16:00', '00:00', 2, 2, 2, 2, 2, 3, 4, 2, 7),
        (194136, '2024-03-03', '00:00', '08:00', 3, 3, 3, 1, 3, 5, 1, 1, 7),
        (194137, '2024-03-04', '08:00', '16:00', 1, 1, 4, 2, 1, 2, 4, 3, 7),
        (194138, '2024-03-05', '16:00', '00:00', 2, 2, 5, 1, 2, 3, 1, 2, 7),
        (194139, '2024-03-06', '00:00', '08:00', 3, 3, 1, 2, 3, 5, 4, 1, 7),
        (194140, '2024-03-07', '08:00', '16:00', 1, 1, 2, 1, 1, 2, 1, 3, 7),
        (194141, '2024-03-08', '16:00', '00:00', 2, 2, 3, 2, 2, 3, 4, 2, 7),
        (194142, '2024-03-09', '00:00', '08:00', 3, 3, 4, 1, 3, 5, 1, 1, 7),
        (194143, '2024-03-10', '08:00', '16:00', 1, 1, 5, 2, 1, 2, 4, 3, 7);

    -- Insertar múltiples registros por planilla (2-4 registros cada una)
    INSERT INTO pst_2.dbo.registro_planilla_pst (
        cod_planilla, cod_corte_ini, cod_corte_fin, 
        cod_destino, cod_calibre, cod_calidad, 
        piezas, kilos, guardado
    )
    SELECT 
        p.cod_planilla,
        ABS(CHECKSUM(NEWID())) % 5 + 1,
        ABS(CHECKSUM(NEWID())) % 5 + 1,
        ABS(CHECKSUM(NEWID())) % 4 + 1,
        ABS(CHECKSUM(NEWID())) % 5 + 1,
        ABS(CHECKSUM(NEWID())) % 5 + 1,
        FLOOR(RAND(CHECKSUM(NEWID())) * (200 - 100 + 1)) + 100,
        FLOOR(RAND(CHECKSUM(NEWID())) * (100 - 50 + 1)) + 50,
        1
    FROM pst_2.dbo.planillas_pst p
    CROSS APPLY (
        SELECT TOP (ABS(CHECKSUM(NEWID())) % 3 + 2) -- Genera 2-4 registros por planilla
            n = ROW_NUMBER() OVER (ORDER BY (SELECT NULL))
        FROM master.dbo.spt_values
    ) n;

    -- Insertar detalles (uno por planilla)
    ;WITH PlanillasUnicas AS (
        SELECT 
            p.cod_planilla,
            l.sala as cod_sala,
            SUM(r.piezas) as total_piezas,
            SUM(r.kilos) as total_kilos,
            ROW_NUMBER() OVER (PARTITION BY p.cod_planilla ORDER BY p.cod_planilla) as rn,
            DATEDIFF(HOUR, p.hora_inicio, p.hora_termino) as tiempo_trabajado
        FROM pst_2.dbo.planillas_pst p
        INNER JOIN pst_2.dbo.registro_planilla_pst r ON p.cod_planilla = r.cod_planilla
        INNER JOIN #LotesEspecificos l ON p.cod_lote = l.cod_lote AND p.fec_turno = l.fecha
        GROUP BY p.cod_planilla, l.sala, p.hora_inicio, p.hora_termino
    )
    INSERT INTO pst_2.dbo.detalle_planilla_pst (
        cod_planilla, cajas_entrega, kilos_entrega, piezas_entrega,
        cajas_recepcion, kilos_recepcion, piezas_recepcion,
        dotacion, cod_sala, productividad, rendimiento, observacion
    )
    SELECT 
        pu.cod_planilla,
        CEILING(pu.total_piezas / 20.0),
        pu.total_kilos,
        pu.total_piezas,
        FLOOR(CEILING(pu.total_piezas / 20.0) * 0.85),
        FLOOR(pu.total_kilos * 0.85) as kilos_recepcion,
        FLOOR(pu.total_piezas * 0.85),
        FLOOR(RAND(CHECKSUM(NEWID())) * (20 - 8 + 1)) + 8 as dotacion,
        pu.cod_sala,
        CAST(FLOOR(pu.total_kilos * 0.85) / (NULLIF((FLOOR(RAND(CHECKSUM(NEWID())) * (20 - 8 + 1)) + 8) * pu.tiempo_trabajado, 0)) AS DECIMAL(10,2)), -- Productividad = kilosRecepcion / (dotacion * tiempoTrabajado)
        CAST((FLOOR(pu.total_kilos * 0.85) / NULLIF(pu.total_kilos, 0)) * 100 AS DECIMAL(10,2)), -- Rendimiento = (kilosRecepcion / kilosEntrega) * 100
        CASE ABS(CHECKSUM(NEWID())) % 4
            WHEN 0 THEN 'Proceso normal'
            WHEN 1 THEN 'Alta productividad'
            WHEN 2 THEN 'Leve retraso por mantenimiento'
            WHEN 3 THEN 'Sin novedad'
        END
    FROM PlanillasUnicas pu
    WHERE pu.rn = 1;

    -- Insertar tiempos muertos (solo para algunas planillas)
    INSERT INTO pst_2.dbo.tiempos_muertos (
        cod_planilla, causa, hora_inicio, hora_termino, duracion_minutos
    )
    SELECT 
        p.cod_planilla,
        CASE ABS(CHECKSUM(NEWID())) % 4
            WHEN 0 THEN 'Mantenimiento preventivo'
            WHEN 1 THEN 'Cambio de formato'
            WHEN 2 THEN 'Falla mecánica'
            WHEN 3 THEN 'Limpieza programada'
        END,
        CAST(DATEADD(MINUTE, 
            CASE rn 
                WHEN 1 THEN ABS(CHECKSUM(NEWID())) % 120
                WHEN 2 THEN ABS(CHECKSUM(NEWID())) % 120 + 180
                WHEN 3 THEN ABS(CHECKSUM(NEWID())) % 120 + 360
            END,
            CAST(p.hora_inicio AS TIME)) AS TIME),
        CAST(DATEADD(MINUTE, 
            CASE rn 
                WHEN 1 THEN ABS(CHECKSUM(NEWID())) % 120 + 30
                WHEN 2 THEN ABS(CHECKSUM(NEWID())) % 120 + 210
                WHEN 3 THEN ABS(CHECKSUM(NEWID())) % 120 + 390
            END,
            CAST(p.hora_inicio AS TIME)) AS TIME),
        30
    FROM pst_2.dbo.planillas_pst p
    CROSS APPLY (
        SELECT TOP (ABS(CHECKSUM(NEWID())) % 2 + 2)
            ROW_NUMBER() OVER (ORDER BY (SELECT NULL)) as rn
        FROM master.dbo.spt_values
    ) n
    WHERE ABS(CHECKSUM(NEWID())) % 3 = 0;

    -- Eliminar registros NULL después de la inserción
    DELETE FROM pst_2.dbo.detalle_planilla_pst 
    WHERE kilos_entrega IS NULL 
       OR piezas_entrega IS NULL 
       OR cajas_entrega IS NULL;

    DROP TABLE #Turnos;
    DROP TABLE #LotesEspecificos;

    COMMIT TRANSACTION;
    PRINT 'Datos insertados correctamente';

END TRY
BEGIN CATCH
    IF @@TRANCOUNT > 0
        ROLLBACK TRANSACTION;
    
    PRINT 'Error en la inserción de datos:';
    PRINT ERROR_MESSAGE();
    PRINT 'Línea: ' + CAST(ERROR_LINE() AS VARCHAR);
    
    IF OBJECT_ID('tempdb..#Turnos') IS NOT NULL
        DROP TABLE #Turnos;
    IF OBJECT_ID('tempdb..#LotesEspecificos') IS NOT NULL
        DROP TABLE #LotesEspecificos;
END CATCH