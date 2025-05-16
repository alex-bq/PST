<?php

try {
    $serverName = "192.168.1.130";
    $connectionOptions = array(
        "Database" => "pst",
        "Uid" => "sa",
        "PWD" => "admin"
    );

    $conn = sqlsrv_connect($serverName, $connectionOptions);

    if ($conn) {
        echo "<h2>Estructura Completa de la Base de Datos</h2>";

        // 1. TABLAS
        echo "<h3>Tablas</h3>";
        $query = "
            SELECT 
                t.name AS TableName,
                s.name AS SchemaName,
                p.rows AS RowCounts,
                SUM(a.total_pages) * 8 AS TotalSpaceKB
            FROM 
                sys.tables t
            INNER JOIN      
                sys.indexes i ON t.object_id = i.object_id
            INNER JOIN 
                sys.partitions p ON i.object_id = p.object_id AND i.index_id = p.index_id
            INNER JOIN 
                sys.allocation_units a ON p.partition_id = a.container_id
            LEFT OUTER JOIN 
                sys.schemas s ON t.schema_id = s.schema_id
            WHERE 
                t.is_ms_shipped = 0
                AND i.object_id > 255
            GROUP BY 
                t.name, s.name, p.rows
            ORDER BY 
                t.name;
        ";

        $stmt = sqlsrv_query($conn, $query);

        if ($stmt) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                echo "<h4>Tabla: " . $row['SchemaName'] . "." . $row['TableName'] . "</h4>";
                echo "Filas: " . $row['RowCounts'] . "<br>";
                echo "Espacio: " . $row['TotalSpaceKB'] . " KB<br>";

                // Obtener columnas de la tabla
                $columnsQuery = "
                    SELECT 
                        c.name AS ColumnName,
                        t.name AS DataType,
                        c.max_length AS MaxLength,
                        c.precision AS Precision,
                        c.scale AS Scale,
                        c.is_nullable AS IsNullable,
                        CASE WHEN pk.column_id IS NOT NULL THEN 'YES' ELSE 'NO' END AS IsPrimaryKey
                    FROM 
                        sys.columns c
                    INNER JOIN 
                        sys.types t ON c.user_type_id = t.user_type_id
                    LEFT JOIN 
                        (SELECT ic.column_id, ic.object_id 
                         FROM sys.index_columns ic
                         INNER JOIN sys.indexes i ON ic.object_id = i.object_id AND ic.index_id = i.index_id
                         WHERE i.is_primary_key = 1) pk 
                         ON c.object_id = pk.object_id AND c.column_id = pk.column_id
                    WHERE 
                        c.object_id = OBJECT_ID('" . $row['SchemaName'] . "." . $row['TableName'] . "')
                    ORDER BY 
                        c.column_id;
                ";

                $columnsStmt = sqlsrv_query($conn, $columnsQuery);

                if ($columnsStmt) {
                    echo "<table border='1' cellpadding='5'>";
                    echo "<tr><th>Columna</th><th>Tipo</th><th>Longitud</th><th>Nulo</th><th>Clave Primaria</th></tr>";

                    while ($column = sqlsrv_fetch_array($columnsStmt, SQLSRV_FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . $column['ColumnName'] . "</td>";
                        echo "<td>" . $column['DataType'] . "</td>";
                        echo "<td>" . $column['MaxLength'] . "</td>";
                        echo "<td>" . ($column['IsNullable'] ? 'Sí' : 'No') . "</td>";
                        echo "<td>" . $column['IsPrimaryKey'] . "</td>";
                        echo "</tr>";
                    }

                    echo "</table><br>";
                }
            }
        } else {
            echo "Error en la consulta: ";
            die(print_r(sqlsrv_errors(), true));
        }

        // 2. VISTAS
        echo "<h3>Vistas</h3>";
        $viewsQuery = "
            SELECT 
                s.name AS SchemaName,
                v.name AS ViewName,
                OBJECT_DEFINITION(OBJECT_ID(s.name + '.' + v.name)) AS ViewDefinition
            FROM 
                sys.views v
            INNER JOIN 
                sys.schemas s ON v.schema_id = s.schema_id
            ORDER BY 
                s.name, v.name;
        ";

        $viewsStmt = sqlsrv_query($conn, $viewsQuery);
        if ($viewsStmt) {
            while ($view = sqlsrv_fetch_array($viewsStmt, SQLSRV_FETCH_ASSOC)) {
                echo "<h4>Vista: " . $view['SchemaName'] . "." . $view['ViewName'] . "</h4>";
                echo "<pre>" . htmlspecialchars($view['ViewDefinition']) . "</pre><br>";
            }
        }

        // 3. PROCEDIMIENTOS ALMACENADOS
        echo "<h3>Procedimientos Almacenados</h3>";
        $procsQuery = "
            SELECT 
                s.name AS SchemaName,
                p.name AS ProcedureName,
                OBJECT_DEFINITION(OBJECT_ID(s.name + '.' + p.name)) AS ProcedureDefinition
            FROM 
                sys.procedures p
            INNER JOIN 
                sys.schemas s ON p.schema_id = s.schema_id
            ORDER BY 
                s.name, p.name;
        ";

        $procsStmt = sqlsrv_query($conn, $procsQuery);
        if ($procsStmt) {
            while ($proc = sqlsrv_fetch_array($procsStmt, SQLSRV_FETCH_ASSOC)) {
                echo "<h4>Procedimiento: " . $proc['SchemaName'] . "." . $proc['ProcedureName'] . "</h4>";
                echo "<pre>" . htmlspecialchars($proc['ProcedureDefinition']) . "</pre><br>";
            }
        }

        // 4. FUNCIONES
        echo "<h3>Funciones</h3>";
        $funcsQuery = "
            SELECT 
                s.name AS SchemaName,
                o.name AS FunctionName,
                o.type_desc AS FunctionType,
                OBJECT_DEFINITION(o.object_id) AS FunctionDefinition
            FROM 
                sys.objects o
            INNER JOIN 
                sys.schemas s ON o.schema_id = s.schema_id
            WHERE 
                o.type IN ('FN', 'IF', 'TF', 'FS', 'FT')
            ORDER BY 
                s.name, o.name;
        ";

        $funcsStmt = sqlsrv_query($conn, $funcsQuery);
        if ($funcsStmt) {
            while ($func = sqlsrv_fetch_array($funcsStmt, SQLSRV_FETCH_ASSOC)) {
                echo "<h4>Función: " . $func['SchemaName'] . "." . $func['FunctionName'] . " (" . $func['FunctionType'] . ")</h4>";
                echo "<pre>" . htmlspecialchars($func['FunctionDefinition']) . "</pre><br>";
            }
        }

        // 5. TRIGGERS
        echo "<h3>Triggers</h3>";
        $triggersQuery = "
            SELECT 
                s.name AS SchemaName,
                t.name AS TriggerName,
                OBJECT_NAME(t.parent_id) AS ParentTable,
                OBJECT_DEFINITION(t.object_id) AS TriggerDefinition
            FROM 
                sys.triggers t
            INNER JOIN 
                sys.objects o ON t.parent_id = o.object_id
            INNER JOIN 
                sys.schemas s ON o.schema_id = s.schema_id
            WHERE 
                t.is_ms_shipped = 0
            ORDER BY 
                s.name, t.name;
        ";

        $triggersStmt = sqlsrv_query($conn, $triggersQuery);
        if ($triggersStmt) {
            while ($trigger = sqlsrv_fetch_array($triggersStmt, SQLSRV_FETCH_ASSOC)) {
                echo "<h4>Trigger: " . $trigger['SchemaName'] . "." . $trigger['TriggerName'] . " (Tabla: " . $trigger['ParentTable'] . ")</h4>";
                echo "<pre>" . htmlspecialchars($trigger['TriggerDefinition']) . "</pre><br>";
            }
        }

        sqlsrv_close($conn);
    } else {
        echo "Error en la conexión: ";
        die(print_r(sqlsrv_errors(), true));
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}