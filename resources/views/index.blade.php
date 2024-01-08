<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Planilla PST</title>
    <link rel="stylesheet" href=".\resources\css\style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
</head>

<body>
    <div class="container-fluid align-text">
        <div class="row">
            <!-- Columna 1: Fecha y Turno -->
            <div class="col-2">
                <p><strong>Fecha Turno:</strong> 03/01/2024</p>
            </div>
            <div class="col-2">
                <p><strong>Turno:</strong> Noche</p>

            </div>

            <div class="col-2">
                <p><strong>Proveedor:</strong> Patagonia King Salmon</p>
            </div>
            <div class="col-2">
                <p><strong>Especie:</strong> Salmon Chinook</p>

            </div>

            <!-- Columna 3: Supervisor y Planillera -->
            <div class="col-2">
                <p><strong>Supervisor:</strong> Natalie Altamirano</p>
            </div>

            <div class="col-2">
                <p><strong>Planillera:</strong> Soledad B</p>

            </div>
        </div>


        <div class="row">
            <div id="columna1" class="col-4">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">

                            <br> <br>
                            <h6>Corte Inicial</h6>
                            <select class="form-select form-select-sm" aria-label="Default select example">
                                <option selected></option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>

                            <h6>Proceso</h6>
                            <select class="form-select form-select-sm" aria-label="Default select example">
                                <option selected></option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                            <h6>Calibre</h6>
                            <select class="form-select form-select-sm" aria-label="Default select example">
                                <option selected></option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                            <br />
                            <h6>Piezas</h6>
                            <input type="number" class="form-control form-control-sm" placeholder="123" />

                        </div>

                        <div class="col-md-6">

                            <br /><br />

                            <h6>Corte Final</h6>
                            <select class="form-select form-select-sm" aria-label="Default select example">
                                <option selected></option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                            <h6>Destino</h6>
                            <select class="form-select form-select-sm" aria-label="Default select example">
                                <option selected></option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>

                            <h6>Calidad</h6>
                            <select class="form-select form-select-sm" aria-label="Default select example">
                                <option selected></option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                            <br />

                            <h6>Kilos</h6>
                            <input type="number" class="form-control form-control-sm" placeholder="123" />
                        </div>
                    </div>

                    <br />

                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-success btn-lg">
                                Agregar
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-warning btn-lg">
                                Limpiar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-8">
                <h1>Planilla Control De Proceso SG</h1>
                <div class="table-wrapper">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Lote</th>
                                <th scope="col">Corte Inicial</th>
                                <th scope="col">Corte Final</th>
                                <th scope="col">Proceso</th>
                                <th scope="col">Destino</th>
                                <th scope="col">Calibre</th>
                                <th scope="col">Calidad</th>
                                <th scope="col">Piezas</th>
                                <th scope="col">Kilos</th>
                                <th scope="col">Seleccionar</th>
                                <th scope="col">Opción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>012312457</th>
                                <td>HON</td>
                                <td>Trim D</td>
                                <td>Congelado</td>
                                <th>xx</th>
                                <td>2-3</td>
                                <td>Premium</td>
                                <td>8</td>
                                <th>9,0</th>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="flexCheckDefault" />
                                        <label class="form-check-label" for="flexCheckDefault">
                                        </label>
                                    </div>
                                </td>
                                <td><a href="">editar</a></td>
                            </tr>
                            @foreach($listado as $i)
                            <tr>
                                <th>{{$i->cod_planilla}}</th>
                                <td>{{$i->cod_corte_ini}}</td>
                                <td>{{$i->cod_corte_fin}}</td>
                                <td>{{$i->cod_proceso}}</td>
                                <td>xx</td>
                                <td>{{$i->cod_calibre}}</td>
                                <td>{{$i->cod_calidad}}</td>
                                <td>{{$i->piezas}}</td>
                                <td>{{$i->kilos}}</td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="flexCheckDefault" />
                                        <label class="form-check-label" for="flexCheckDefault">
                                        </label>
                                    </div>
                                </td>
                                <td><a href="">editar</a></td>







                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <div class="row mt-4">
                        <div class="col-4">
                            <h6>Entrega Frigorífico</h6>
                            <label for="cajasEntrega">Cajas:</label>
                            <input type="number" class="form-control form-control-sm" id="cajasEntrega"
                                placeholder="Cajas" />
                            <label for="kilosEntrega">Kilos:</label>
                            <input type="number" class="form-control form-control-sm" id="kilosEntrega"
                                placeholder="Kilos" />
                        </div>
                        <div class="col-4">
                            <h6>Recepción Planta</h6>
                            <label for="cajasRecepcion">Cajas:</label>
                            <input type="number" class="form-control form-control-sm" id="cajasRecepcion"
                                placeholder="Cajas" />
                            <label for="kilosRecepcion">Kilos:</label>
                            <input type="number" class="form-control form-control-sm" id="kilosRecepcion"
                                placeholder="Kilos" />
                        </div>
                    </div>

                    <!-- Nueva Fila: Recepción Planta -->

                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function ()                   $("#datepicker").datepick);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>