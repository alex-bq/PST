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
            <div class="col-4">
                <p><strong>Usuario:</strong> Cesar</p>
            </div>
            <div class="col-8"></div>
        </div>

        <div class="row">
            <div id="columna1" class="col-4">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Fecha</h6>
                            <div class="input-group date" id="datepicker">
                                <input type="text" class="form-control" id="date" placeholder="DD/MM/YYYY" />
                                <span class="input-group-append">
                                    <span class="input-group-text bg-light d-block">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                </span>
                            </div>
                            <br /><br />

                            <h6>Corte MP</h6>
                            <select class="form-select" aria-label="Default select example">
                                <option selected></option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>

                            <h6>Kilos MP</h6>
                            <input type="text" class="form-control" placeholder="123" />
                            <h6>Empresa</h6>
                            <select class="form-select" aria-label="Default select example">
                                <option selected></option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                            <br />
                            <h6>Tipo Proceso</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault"
                                    id="flexRadioDefault1" />
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Pesaje
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault"
                                    id="flexRadioDefault2" checked="" />
                                <label class="form-check-label" for="flexRadioDefault2">
                                    Corte
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault"
                                    id="flexRadioDefault2" checked="" />
                                <label class="form-check-label" for="flexRadioDefault2">
                                    Baader
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6>Lote</h6>
                            <select class="form-select" aria-label="Default select example">
                                <option selected></option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                            <br /><br />

                            <h6>Corte Final</h6>
                            <select class="form-select" aria-label="Default select example">
                                <option selected></option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                            <h6>Calidad</h6>
                            <select class="form-select" aria-label="Default select example">
                                <option selected></option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>

                            <h6>Calibre</h6>
                            <select class="form-select" aria-label="Default select example">
                                <option selected></option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                            <br />
                            <h6>Piezas</h6>
                            <input type="text" class="form-control" placeholder="123" />
                            <h6>Kilos</h6>
                            <input type="text" class="form-control" placeholder="123" />
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
                                <th scope="col">Fecha</th>
                                <th scope="col">Empresa</th>
                                <th scope="col">Lote</th>
                                <th scope="col">Corte MMPP</th>
                                <th scope="col">Corte Final</th>
                                <th scope="col">Calidad</th>
                                <th scope="col">Calibre</th>
                                <th scope="col">Piezas</th>
                                <th scope="col">Kilos</th>
                                <th scope="col">Seleccionar</th>
                                <th scope="col">Opción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>01/04/2024</th>
                                <td>Multi x</td>
                                <td>012310501</td>
                                <td>HON</td>
                                <th>xx</th>
                                <td>xx</td>
                                <td>xx</td>
                                <td>xx</td>
                                <th>xx</th>
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
                            <tr>
                                <th>01/04/2024</th>
                                <td>Multi x</td>
                                <td>012310501</td>
                                <td>HON</td>
                                <th>xx</th>
                                <td>xx</td>
                                <td>xx</td>
                                <td>xx</td>
                                <th>xx</th>
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
                            <tr>
                                <th>01/04/2024</th>
                                <td>Multi x</td>
                                <td>012310501</td>
                                <td>HON</td>
                                <th>xx</th>
                                <td>xx</td>
                                <td>xx</td>
                                <td>xx</td>
                                <th>xx</th>
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
                            <tr>
                                <th>01/04/2024</th>
                                <td>Multi x</td>
                                <td>012310501</td>
                                <td>HON</td>
                                <th>xx</th>
                                <td>xx</td>
                                <td>xx</td>
                                <td>xx</td>
                                <th>xx</th>
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
                            <tr>
                                <th>01/04/2024</th>
                                <td>Multi x</td>
                                <td>012310501</td>
                                <td>HON</td>
                                <th>xx</th>
                                <td>xx</td>
                                <td>xx</td>
                                <td>xx</td>
                                <th>xx</th>
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function ()                   $("#datepicker").datepick            });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>