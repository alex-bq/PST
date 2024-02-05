DROP TRIGGER trg_detalle_planilla_pst;

CREATE TRIGGER trg_detalle_planillas_pst
ON pst.dbo.planillas_pst
AFTER INSERT
AS
BEGIN
    INSERT INTO pst.dbo.detalle_planilla_pst (cod_planilla)
    SELECT cod_planilla
    FROM inserted;
END;



