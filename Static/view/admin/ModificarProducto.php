<?php 

    include 'HeaderA.php';
    include '../../Controller/Productos.php';
    include '../../Controller/Proveedores.php';
    include '../../Controller/Connect/Db.php';
    include '../../Controller/Sesion.php';

    $ProductoId = isset($_GET['id']) ? $_GET['id'] : null;
    $Producto = null;

    if ($ProductoId) {
        $Producto = obtenerProductoPorId($conn, $ProductoId); 
    }

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CRUD de Productos</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Viga&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="../../Css/modificar.css">
    </head>
    <body>
        <div class="formContainer">
            <div class="formModificar">
                <h2>Modificar Producto</h2>

                <?php if ($Producto): ?>
                    <form action="../../Controller/Productos.php?accion=actualizar" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($Producto['folio'] ?? ''); ?>">

                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombreProd" name="nombreProd" value="<?php echo htmlspecialchars($Producto['nombreProd'] ?? ''); ?>" required>

                        <label for="categoria">Categoría:</label>
                        <input type="text" id="categoria" name="categoria" value="<?php echo htmlspecialchars($Producto['tipo'] ?? ''); ?>" required>

                        <label for="peso">Peso:</label>
                        <input type="number" step="0.01" id="peso" name="peso" value="<?php echo htmlspecialchars($Producto['peso'] ?? ''); ?>" required>
                        <p class="alert alert-danger" id="errorPeso" style="display:none;">
                            Ingresa un peso válido, por favor.
                        </p>


                        <label for="unidadM">Unidad:</label>
                        <select id="unidadM" name="unidadM" class="form-control" value="<?php echo htmlspecialchars($Producto['unidadM'] ?? ''); ?> required>
                            <option value="kg">Kilogramos</option>
                            <option value="lt">Litros</option>
                            <option value="g">Gramos</option>
                            <option value="ml">Mililitros</option>
                        </select>

                        <label for="precio">Precio:</label>
                        <input type="number" step="0.01" id="precio" name="precio" value="<?php echo htmlspecialchars($Producto['precio'] ?? ''); ?>" required>
                        <p class="alert alert-danger" id="errorPrecio" style="display:none;">
                            Ingresa un precio válido, por favor.
                        </p>

                        <input type="number" id="existencia" name="existencia" style="display:none" value="<?php echo htmlspecialchars($Producto['existencia'] ?? ''); ?>" required>

                        <p class="alert alert-danger" id="errorExistencias" style="display:none;">
                            Ingresa un número válido, por favor.
                        </p>

                        <label for="proveedor">Proveedor:</label>
                        <div class="busqueda mb-3">
                            <?php
                                $proveedor = obtenerProveedorPorID($conn, $Producto['idProveedor'] ?? null);
                                $nombreProveedor = $proveedor ? (!empty($proveedor['nombreComercial']) ? $proveedor['nombreComercial'] : $proveedor['razonSocial']) : 'Proveedor no encontrado';
                            ?>
                            <!-- Campo de texto para mostrar el nombre del proveedor (solo lectura) -->
                            <input type="text" id="proveedorNombre" value="<?php echo htmlspecialchars($nombreProveedor); ?>" readonly class="form-control">
                            <!-- Campo oculto para enviar el ID del proveedor -->
                            <input type="hidden" id="idProveedor" name="idProveedor" value="<?php echo htmlspecialchars($Producto['idProveedor'] ?? ''); ?>">
                        </div>


                        <label for="descripcion">Descripción:</label>
                        <textarea id="descripcion" name="descripcion" class="desc"  required><?php echo htmlspecialchars($Producto['descripcion'] ?? ''); ?></textarea>
                        
                        <div id="previewContainer">
                            <label>Imagen:</label>
                            <br>
                            <img id="previewImg" src="/SolucionesWeb/Static/Img/Productos/<?php echo htmlspecialchars($Producto['urlImagen'] ?? '/SolucionesWeb/Static/Img/Productos/imagengenerica.png'); ?>" name="previewImg" class="previewImg" style="display: block;">
                            <div id="removePreview" name="removePreview">&times;</div>
                            <br>
                        </div>

                        <label for="fotoProducto" class="custom-file-upload btn btn-secondary mt-3">
                            <p class="letraBlanca"> Subir foto del producto</p>
                        </label>
                        <input type="file" id="fotoProducto" name="fotoProducto" accept=".png, .jpg, .jpeg, .gif, .svg" class="form-control-file" style="display: none;">

                        <!-- Contenedor para la imagen de previsualización con la X para eliminar -->
                        <button type="submit" name="accion" class="btn-primario" value="actualizar" onclick="return validacionModiProducto();">Actualizar</button>
                        <br>

                    </form>

                    <button type="button" onclick="location.href='/SolucionesWeb/Static/View/Admin/ViewGestionProd.php'" class="btn-secundario">Cancelar</button>

                    <br><br>
                    
                <?php else: ?>
                    <p>No se encontró el Producto especificado.</p>
                <?php endif; ?>
            </div>
        </div>
                    
        <script src="/SolucionesWeb/Static/Controller/Js/Productos.js"></script>
        <script src="/SolucionesWeb/Static/Controller/Js/Validaciones.js"></script>

    </body>
</html>
