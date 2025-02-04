<?php include 'HeaderA.php'; ?>
<?php include '../../Controller/Connect/Db.php'; ?>
<?php include '../../Controller/Productos.php'; ?>
<?php include '../../Controller/Proveedores.php'; ?>
<?php include '../../Controller/Sesion.php'; ?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gestión de Productos</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="/SolucionesWeb/Static/Css/Productos.css"> <!-- Referencia al CSS externo -->
    </head>
    <body>
        
        <div class="container">

            <div class="titulo">
                <h1>Gestión de productos</h1>
            </div>

            <div class="row">
                <!-- Formulario de registro de productos -->
                <div class="titulosLabel formulario">
                    <h2>Registrar Producto</h2>
                    <form action="/SolucionesWeb/Static/Controller/Productos.php" method="POST" enctype="multipart/form-data">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" required>

                        <label for="categoria">Categoría:</label>
                        <select id="categoria" name="categoria" required>
                            <option value="Insecticida">Insecticida</option>
                            <option value="Herbicida">Herbicida</option>
                            <option value="Fertilizante">Fertilizante</option>
                            <option value="Hormiguicida">Hormiguicida</option>
                            <option value="Cucarachicida">Cucarachicida</option>
                            <option value="Trampa">Trampa</option>
                            <option value="Mosquicida">Mosquicida</option>
                            <option value="Otro">Otro</option>
                        </select>

                        <label for="peso">Peso:</label>
                        <input type="number" step="0.1" id="peso" name="peso" class="form-control" required>
                        <p class="alert alert-danger" id="errorPeso" style="display:none;">
                            Ingresa un peso válido, por favor.
                        </p>

                        <label for="unidad">Unidad:</label>
                        <select id="unidad" name="unidad" class="form-control" required>
                            <option value="kilogramos">Kilogramos</option>
                            <option value="litros">Litros</option>
                            <option value="gramos">Gramos</option>
                            <option value="mililitros">Mililitros</option>
                        </select>

                        <label for="precio">Precio:</label>
                        <input type="number" step="0.1" id="precio" name="precio" class="form-control" required>
                        <p class="alert alert-danger" id="errorPrecio" style="display:none;">
                            Ingresa un precio válido, por favor.
                        </p>

                        <label for="cantidad">Existencias:</label>
                        <input type="number" id="cantidad" name="cantidad" class="form-control" required>
                        <p class="alert alert-danger" id="errorExistencias" style="display:none;">
                            Ingresa un número válido, por favor.
                        </p>

                        <label for="Proveedor">Proveedor:</label>
                        <div class="busqueda">
                            <input type="text" id="proveedorNombre" placeholder="Busca un proveedor" class="form-control" autocomplete="off" oninput="buscarProveedor(this.value)">
                            <input type="hidden" id="idProveedor" name="idProveedor">
                        </div>

                        <p class="alert alert-danger" id="errorIdProveedor" style="display:none;">
                            Selecciona a un proveedor, por favor.
                        </p>

                        <div id="listaProveedores" class="list-group" style="display: none;">
                            <?php
                            foreach ($proveedores as $proveedor) {
                                echo "<a href='javascript:void(0)' onclick='seleccionarProveedor({$proveedor['id']}, \"{$proveedor['nombre']}\")' class='list-group-item list-group-item-action'>{$proveedor['nombre']}</a>";
                            }
                            ?>
                        </div> 

                        <label for="descripcion">Descripción:</label>
                        <textarea id="descripcion" name="descripcion" class="form-control" required></textarea>

                        <!-- Contenedor para la imagen de previsualización con la X para eliminar -->
                        <div id="previewContainer">
                            <label>Imagen: </label>
                            <img id="previewImg" src="/SolucionesWeb/Static/Img/imagengenerica.png" alt="Previsualización">
                            <div id="removePreview">&times;</div>
                        </div>

                        <label for="fotoProducto" class="custom-file-upload btn btn-secondary mt-3">
                            <img src="/SolucionesWeb/Static/Img/subir.png" alt="Subir" class="upload-icon">
                            <p class="letraBlanca"> Subir foto del producto</p>
                        </label>
                        <input type="file" id="fotoProducto" name="fotoProducto" accept=".png, .jpg, .jpeg, .gif, .svg" class="form-control-file" style="display: none;" required>

                        <!-- Contenedor centrado para el botón -->
                        <div class="botonRegistrar">
                            <button type="submit" class="btn btn-primary mt-3" name="accion" value="crear" onclick="return validacionProducto();">Registrar</button>
                        </div>
                    </form>
                </div>

                <!-- Contenedor de productos deslizante con buscador arriba -->
                <div class="contenedorProductos">
                    <div class="busqueda mb-3">
                        <input type="text" id="busqueda" placeholder="Buscar un producto por nombre" class="form-control" oninput="filtrarProductos(this.value)">
                    </div>

                    <div class="product-grid">
                        <div class="product-list">
                            <?php
                            $productos = solicitarProductos($conn);
                            foreach ($productos as $producto) {
                                echo "
                                <div class='product-card'>
                                    <img src='/SolucionesWeb/Static/Img/Productos/{$producto['urlImagen']}' alt='Producto' class='img-fluid'>

                                    <h5>{$producto['nombreProd']}</h5>

                                    <p>Precio: \${$producto['precio']}</p>

                                    <p>Peso: {$producto['peso']} {$producto['unidadM']}</p>

                                    <p>Categoría: {$producto['tipo']}</p>

                                    <p>Existencias: {$producto['existencia']}</p>
                                    
                                    <a href='ModificarProducto.php?accion=editar&id={$producto['folio']}' class='btn btn-sm'>
                                    <img src='/SolucionesWeb/Static/Img/editar.png' alt='Editar' class='icono'></a>
                                    
                                    <a href='/SolucionesWeb/Static/Controller/Productos.php?accion=eliminar&id={$producto['folio']}' class='btn btn-sm boton eliminar'><img src='/SolucionesWeb/Static/Img/eliminar.png' alt='Eliminar'class='icono'></a>

                                    <a href=\"/SolucionesWeb/Static/Controller/AgregarAlCarrito.php?folio=" . $producto['folio'] . "&cantidad=1\"class=\"btn btn-sm\" id=\"agregarCarrito\">
                                    <img src=\"/SolucionesWeb/Static/Img/carrito.png\" alt=\"Carrito\" class=\"icono\"></a>
                                </div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Confirmación -->
        <div id="confirmModal" class="modal">
            <div class="modal-content">
                <h2>Confirmar Eliminación</h2>
                <p>¿Estás seguro de que deseas eliminar este producto? Esta acción no se puede deshacer.</p>
                <button id="confirmDelete" class="confirm">Confirmar</button>
                <button id="cancelDelete" class="cancel">Cancelar</button>
            </div>
        </div>

        <div id="mensajeExito" class="alert alert-success" name="mensajeExito" style="display: none;">
            <a href="/SolucionesWeb/Static/View/Admin/ViewGestionVent.php">
                <span id="mensajeTexto"></span>
                <img src="/SolucionesWeb/Static/Img/palomita.png" alt="Éxito" class="pal">
            </a>
        </div>

        <script src="/SolucionesWeb/Static/Controller/Js/Productos.js"></script>
        <script src="/SolucionesWeb/Static/Controller/Js/ConfirmElim.js"></script>
        <script src="/SolucionesWeb/Static/Controller/Js/Validaciones.js"></script>
        <script src="/SolucionesWeb/Static/Controller/Js/Carrito.js"></script>
    </body>
</html>
