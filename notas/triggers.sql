DROP TRIGGER pst_2.trg_detalle_planilla_pst;

CREATE TRIGGER trg_detalle_planillas_pst
ON pst_2.dbo.planillas_pst
AFTER INSERT
AS
BEGIN
    INSERT INTO pst_2.dbo.detalle_planilla_pst (cod_planilla)
    SELECT cod_planilla
    FROM inserted;
END;


CREATE OR ALTER TRIGGER TR_ValidarTurnosPorFecha
ON pst_2.dbo.informes_turno
AFTER INSERT
AS
BEGIN
    SET NOCOUNT ON;

    -- Verificar si ya existe el mismo turno para la fecha insertada
    IF EXISTS (
        SELECT 1
        FROM pst_2.dbo.informes_turno i
        INNER JOIN inserted ins 
            ON i.fecha_turno = ins.fecha_turno 
            AND i.cod_turno = ins.cod_turno
        WHERE i.cod_informe != ins.cod_informe
    )
    BEGIN
        DECLARE @FechaInforme DATE;
        DECLARE @Turno INT;
        
        SELECT @FechaInforme = fecha_turno, @Turno = cod_turno
        FROM inserted;
        
        DECLARE @ErrorMessage NVARCHAR(200);
        SET @ErrorMessage = 'Ya existe un informe para la fecha ' + 
                           CONVERT(VARCHAR, @FechaInforme, 103) + 
                           ' en el turno ' + CONVERT(VARCHAR, @Turno);
        
        RAISERROR (@ErrorMessage, 16, 1);
        ROLLBACK TRANSACTION;
        RETURN;
    END
END;