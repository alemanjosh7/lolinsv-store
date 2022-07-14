<?php
// Se verifica si existe el parámetro id en la url, de lo contrario se direcciona a la página web de origen.
if (isset($_GET['id'])) {
    require('../../helpers/dashboard_report.php');
    require('../../models/pedidosEstablecidos.php');
    // Se instancia el módelo pedidos personalizado para procesar los datos.
    $pedido = new PedidosEstablecidos;

    // Se verifica si el parámetro es un valor correcto, de lo contrario se direcciona a la página web de origen.
    if ($pedido->setId($_GET['id'])) {
        // Se instancia la clase para crear el reporte.
        $pdf = new Report;
        // Se inicia el reporte con el encabezado del documento.
        $pdf->startReport('Detalle de pedido establecido', 'p');
        //Se verifica si el pedido existe de lo contrario se direcciona a la página de origen
        if ($rowPedido = $pedido->readPedido()) {
            $pdf->setFillColor(252, 85, 85, 1);
            $pdf->setFont('Times', 'B', 20);
            //Se llenan las celdas con la información del readPedido
            $pdf->cell(205, 10, utf8_decode('Información del pedido'), 1, 1, 'C', 1);
            $pdf->Ln(10); //Espacio vertical
            //CODIGO del pedido
            // Se establece un color de relleno para los encabezados.
            $pdf->setFillColor(252, 85, 85, 1);
            // Se establece la fuente para los encabezados.
            $pdf->setFont('Times', 'B', 11);
            //Se llenan las celdas con la información del readPedido
            $pdf->cell(30, 10, utf8_decode('Codigo Pedido'), 1, 0, 'C', 1);
            $pdf->setFillColor(252, 190, 190, 1);
            // Se establece la fuente para los encabezados.
            $pdf->setFont('Times', '', 11);
            //Se llenan las celdas con la información del readPedido
            $pdf->cell(175, 10, utf8_decode($rowPedido['id_pedidos_establecidos']), 1, 1, 'C', 1);
            $pdf->ln(5); //Espaciado
            //NOMBRE DEL CLIENTE
            $pdf->setFillColor(252, 85, 85, 1);
            // Se establece la fuente para los encabezados.
            $pdf->setFont('Times', 'B', 11);
            //Se llenan las celdas con la información del readPedido
            
            //ESPACIO ENTRE CELDA
            $pdf->cell(50, 10, ' ', 0, 0, 'C');
            //MONTO A PAGAR
            $pdf->setFillColor(252, 85, 85, 1);
            // Se establece la fuente para los encabezados.
            $pdf->setFont('Times', 'B', 11);
            //Se llenan las celdas con la información del readPedido
            $pdf->cell(25, 10, utf8_decode('Total (US$)'), 1, 0, 'C', 1);
            $pdf->setFillColor(252, 190, 190, 1);
            // Se establece la fuente para los encabezados.
            $pdf->setFont('Times', '', 11);
            //Se llenan las celdas con la información del readPedido
            $pdf->cell(30, 10, utf8_decode('$' . $rowPedido['montototal_pedidoesta']), 1, 0, 'C', 1);
            //ESPACIO ENTRE CELDA
            $pdf->cell(3, 10, ' ', 0, 0, 'C');
            //FECHA en que se realizo
            $pdf->setFillColor(252, 85, 85, 1);
            // Se establece la fuente para los encabezados.
            $pdf->setFont('Times', 'B', 11);
            //Se llenan las celdas con la información del readPedido
            $pdf->cell(30, 10, utf8_decode('Fecha del pedido'), 1, 0, 'C', 1);
            $pdf->setFillColor(252, 190, 190, 1);
            // Se establece la fuente para los encabezados.
            $pdf->setFont('Times', '', 11);
            //Se llenan las celdas con la información del readPedido
            $pdf->cell(24, 10, utf8_decode($rowPedido['fecha_pedidoesta']), 1, 1, 'C', 1);
            $pdf->Ln(5);
            //CORREO del cliente
            //FECHA en que se realizo
            $pdf->setFillColor(252, 85, 85, 1);
            // Se establece la fuente para los encabezados.
            $pdf->setFont('Times', 'B', 11);
            //Se llenan las celdas con la información del readPedido
            $pdf->cell(40, 10, utf8_decode('Correo del cliente'), 1, 0, 'C', 1);
            $pdf->setFillColor(252, 190, 190, 1);
            // Se establece la fuente para los encabezados.
            $pdf->setFont('Times', '', 11);
            //Se llenan las celdas con la información del readPedido
            $pdf->cell(165, 10, utf8_decode($rowPedido['correo_cliente']), 1, 0, 'C', 1);
            $pdf->Ln(5);
            //DESCRIPCION LUGAR ENTREGA del pedido
            $y = 115;
            $x = 5;
            $pdf->SetXY($x, $y);
            $pdf->setFillColor(252, 85, 85, 1);
            $pdf->setFont('Times', 'B', 11);
            $pdf->MultiCell(50, 10, utf8_decode('Descripción del lugar de entrega'), 1, 'C', 1);
            $pdf->setFillColor(252, 190, 190, 1);
            $pdf->setFont('Times', '', 11);
            $pdf->SetXY($x + 50, $y);
            $pdf->MultiCell(155, 20, utf8_decode($rowPedido['descripcionlugar_entrega']), 1, 'C', 1);
            //DIRECCION del cliente del pedido
            $y = $y + 25;
            $x = 5;
            $pdf->SetXY($x, $y);
            $pdf->setFillColor(252, 85, 85, 1);
            $pdf->setFont('Times', 'B', 11);
            $pdf->MultiCell(50, 20, utf8_decode('Dirección del cliente'), 1, 'C', 1);
            $pdf->setFillColor(252, 190, 190, 1);
            $pdf->setFont('Times', '', 11);
            $pdf->SetXY($x + 50, $y);
            $pdf->MultiCell(155, 20, utf8_decode($rowPedido['direccion_cliente']), 1, 'C', 1);
            $pdf->Ln(5); //Espacio vertical
            //Seteando el color del fondo
            $pdf->setFillColor(252, 85, 85, 1);
            $pdf->setFont('Times', 'B', 20);
            //Se llenan las celdas con la información del readPedido
            $pdf->cell(205, 10, utf8_decode('Detalle del pedido'), 1, 1, 'C', 1);
            $pdf->Ln(10); //Espacio vertical
            //Color del fondo
            $pdf->setFillColor(252, 226, 205, 1);
            //Verficamos si se puede obtener el detalle del pedido
            // Se establece la fuente para los encabezados.
            $pdf->setFont('Times', 'B', 11);
            //Encabezados para el detalle
            $pdf->setFillColor(252, 190, 190, 1);
            //Se preparan los encabezados para el detalle
            $pdf->cell(50, 10, utf8_decode('Nombre del producto'), 1, 0, 'C', 1);
            $pdf->cell(40, 10, utf8_decode('Imagen'), 1, 0, 'C', 1);
            //Se preparan los encabezados para el detalle
            $pdf->cell(20, 10, utf8_decode('Cantidad'), 1, 0, 'C', 1);
            //Se llenan las celdas con la información del readPedido
            //Se preparan los encabezados para el detalle
            $pdf->cell(50, 10, utf8_decode('Precio individual (US$)'), 1, 0, 'C', 1);
            //Se llenan las celdas con la información del readPedido
            //Se preparan los encabezados para el detalle
            $pdf->cell(45, 10, utf8_decode('Sub Total (US$)'), 1, 0, 'C', 1);
            $pdf->Ln(10); //Espacio vertical
            //Validamos que halla un detalle de pedido a cargar
            if ($detalle = $pedido->ObtenerDetalle()) {
                // Se establece un color de relleno para los encabezados.
                $pdf->setFillColor(255, 226, 205, 1);
                // Se establece la fuente para los datos de los productos.
                $pdf->setFont('Times', '', 11);
                //Declaramos algunas variables cont(Para saber el número de elementos que lleva) inicio(para saber si esta en la página de inicio)
                $cont = 0;
                $pinicio = false;
                // Se recorren los registros ($dataProductos) fila por fila ($rowDetalle).
                foreach ($detalle as $rowDetalle) {
                    $cont++;
                    if($cont > 6  && $pinicio){
                        //Color del fondo
                        $pdf->setFillColor(252, 226, 205, 1);
                        //Verficamos si se puede obtener el detalle del pedido
                        // Se establece la fuente para los encabezados.
                        $pdf->setFont('Times', 'B', 11);
                        //Encabezados para el detalle
                        $pdf->setFillColor(252, 190, 190, 1);
                        //Se preparan los encabezados para el detalle
                        $pdf->cell(50, 10, utf8_decode('Nombre del producto'), 1, 0, 'C', 1);
                        $pdf->cell(40, 10, utf8_decode('Imagen'), 1, 0, 'C', 1);
                        //Se preparan los encabezados para el detalle
                        $pdf->cell(20, 10, utf8_decode('Cantidad'), 1, 0, 'C', 1);
                        //Se llenan las celdas con la información del readPedido
                        //Se preparan los encabezados para el detalle
                        $pdf->cell(50, 10, utf8_decode('Precio individual (US$)'), 1, 0, 'C', 1);
                        //Se llenan las celdas con la información del readPedido
                        //Se preparan los encabezados para el detalle
                        $pdf->cell(45, 10, utf8_decode('Sub Total (US$)'), 1, 1, 'C', 1);
                        // Se establece un color de relleno para los encabezados.
                        $pdf->setFillColor(255, 226, 205, 1);
                        // Se establece la fuente para los datos de los productos.
                        $pdf->setFont('Times', '', 11);
                        $cont = 0;
                    }
                    // Se imprimen las celdas con los datos del detalle
                    $pdf->cell(50, 30, utf8_decode($rowDetalle['nombre_producto']), 1, 0, 'C', 1);
                    $pdf->cell(40, 30, $pdf->Image("../../images/productos/" . $rowDetalle['imagen_producto'], $pdf->GetX(), $pdf->GetY(), 40, 30), 1, 0, 'C');
                    //Se preparan los encabezados para el detalle
                    $pdf->cell(20, 30, utf8_decode($rowDetalle['cantidad_detallep']), 1, 0, 'C', 1);
                    //Se llenan las celdas con la información del readPedido
                    //Se preparan los encabezados para el detalle
                    $pdf->cell(50, 30, utf8_decode('$'.$rowDetalle['precio_producto']), 1, 0, 'C', 1);
                    //Se llenan las celdas con la información del readPedido
                    //Se preparan los encabezados para el detalle
                    $pdf->cell(45, 30, utf8_decode('$'.$rowDetalle['subtotal_detallep']), 1, 1, 'C', 1);
                    $pdf->Ln(5); //Espacio vertical
                    if (!$pinicio) {
                        $pinicio = true;
                        $pdf->Ln(50); //Espacio vertical
                        //Color del fondo
                        $pdf->setFillColor(252, 226, 205, 1);
                        //Verficamos si se puede obtener el detalle del pedido
                        // Se establece la fuente para los encabezados.
                        $pdf->setFont('Times', 'B', 11);
                        //Encabezados para el detalle
                        $pdf->setFillColor(252, 190, 190, 1);
                        //Se preparan los encabezados para el detalle
                        $pdf->cell(50, 10, utf8_decode('Nombre del producto'), 1, 0, 'C', 1);
                        $pdf->cell(40, 10, utf8_decode('Imagen'), 1, 0, 'C', 1);
                        //Se preparan los encabezados para el detalle
                        $pdf->cell(20, 10, utf8_decode('Cantidad'), 1, 0, 'C', 1);
                        //Se llenan las celdas con la información del readPedido
                        //Se preparan los encabezados para el detalle
                        $pdf->cell(50, 10, utf8_decode('Precio individual (US$)'), 1, 0, 'C', 1);
                        //Se llenan las celdas con la información del readPedido
                        //Se preparan los encabezados para el detalle
                        $pdf->cell(45, 10, utf8_decode('Sub Total (US$)'), 1, 1, 'C', 1);
                        // Se establece un color de relleno para los encabezados.
                        $pdf->setFillColor(255, 226, 205, 1);
                        // Se establece la fuente para los datos de los productos.
                        $pdf->setFont('Times', '', 11);
                    };
                }
            } else {
                $pdf->cell(0, 10, utf8_decode('No hay un detalle que mostrar'), 1, 1);
            }
            //Se llenan las celdas con la información del readPedido
            $pdf->output('I', 'Reporte de la compra.pdf', true);
        } else {
            header('location: ../../../views/public/');
        }
    } else {
        header('location: ../../../views/public/');
    }
} else {
    header('location: ../../../views/public/');
}
