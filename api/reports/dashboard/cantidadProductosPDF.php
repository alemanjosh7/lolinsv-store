<?php
// Se verifica si existe el parámetro id en la url, de lo contrario se direcciona a la página web de origen.
if (isset($_GET['filt']) && is_numeric($_GET['filt'])) {
    require('../../helpers/dashboard_report.php');
    require('../../models/productos.php');
    // Se instancia el módelo productos para procesar los datos.
    $producto = new Productos;
    // Se instancia la clase para crear el reporte.
    $pdf = new Report;
    $pdf->startReport('Reporte de existencias de productos', 'p');
    if ($rowProducto = $producto->reporteCantFilt($_GET['filt'])) {
        //Se llenan las celdas con la información del readPedido
        // Se establece un color de relleno para los encabezados.
        $pdf->setFillColor(252, 85, 85, 1);
        // Se establece la fuente para los encabezados.
        $pdf->setFont('Times', 'B', 11);
        //Se llenan las celdas con la información del readPedido
        $pdf->cell(30, 10, utf8_decode('Codigo'), 1, 0, 'C', 1); //Codigo del producto
        $pdf->cell(50, 10, utf8_decode('Nombre'), 1, 0, 'C', 1); //Nombre del producto
        $pdf->cell(35, 10, utf8_decode('Precio ($)'), 1, 0, 'C', 1); //Precio del producto
        $pdf->cell(50, 10, utf8_decode('Valoración global (Clientes)'), 1, 0, 'C', 1); //Valoración del producto
        $pdf->cell(42, 10, utf8_decode('Cantidad'), 1, 1, 'C', 1); //Cantidad
        // Se establece un color de relleno para los encabezados.
        $pdf->setFillColor(255, 226, 205, 1);
        // Se establece la fuente para los datos de los productos.
        $pdf->setFont('Times', '', 11);
        $pdf->Ln(5); //Espacio vertical
        //Imprimimos los datos de la bd
        $cont = 0;
        foreach ($rowProducto as $rowProducto) {
            //Evaluamos si se debe poner el encabezado
            $cont++;
            //Evaluamos si el contador esta en 4 que es él número de columnas para cambio  de página
            if ($cont > 12) {
                $pdf->setFillColor(252, 85, 85, 1);
                // Se establece la fuente para los encabezados.
                $pdf->setFont('Times', 'B', 11);
                //Se llenan las celdas con la información del readPedido
                $pdf->cell(30, 10, utf8_decode('Codigo'), 1, 0, 'C', 1); //Codigo del producto
                $pdf->cell(50, 10, utf8_decode('Nombre'), 1, 0, 'C', 1); //Nombre del producto
                $pdf->cell(35, 10, utf8_decode('Precio ($)'), 1, 0, 'C', 1); //Precio del producto
                $pdf->cell(50, 10, utf8_decode('Valoración global (Clientes)'), 1, 0, 'C', 1); //Valoración del producto
                $pdf->cell(42, 10, utf8_decode('Cantidad'), 1, 1, 'C', 1); //Cantidad
                // Se establece un color de relleno para los encabezados.
                $pdf->setFillColor(255, 226, 205, 1);
                // Se establece la fuente para los datos de los productos.
                $pdf->setFont('Times', '', 11);
                $pdf->Ln(5); //Espacio vertical
                $cont = 0;
            }
            $pdf->SetWidths(array(30, 50, 35, 50, 42)); //Seteamos el ancho de las celdas
            $pdf->setHeight(10);
            $pdf->Row(array($rowProducto['id_producto'], utf8_encode($rowProducto['nombre_producto']), '$' . $rowProducto['precio_producto'], obtenerVal($rowProducto['valoraciones']), $rowProducto['cantidad']));
            $pdf->Ln(5); //Espacio vertical
        }
    } else {
        $pdf->cell(0, 10, utf8_decode('No hay productos registrados o ha ocurrido un error'), 1, 1);
    }
    if ($_GET['filt'] == 1) {
        $pdf->output('I', 'Reporte de productos ordenados por cantidad de mayor a menor', true);
    } else {
        $pdf->output('I', 'Reporte de productos ordenados por cantidad de menor a mayor', true);
    }
} else {
    header('location: ../../../views/dashboard/pendientesami.html');
}

function obtenerVal($valoracion)
{
    switch ($valoracion) {
        case 1:
            $val =  'Muy negativa';
            break;
        case 2:
            $val = 'Mala';
            break;
        case 3:
            $val  = 'Dudosa';
            break;
        case 4:
            $val = 'Positiva';
            break;
        case 5:
            $val = 'Muy positiva';
            break;
        default:
            $val = 'Dudosa';
            break;
    }

    return $val;
}
