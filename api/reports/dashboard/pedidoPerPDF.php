<?php
// Se verifica si existe el parámetro id en la url, de lo contrario se direcciona a la página web de origen.
if (isset($_GET['id'])) {
    require('../../helpers/dashboard_report.php');
    require('../../models/pedidosPersonalizados.php');
    // Se instancia el módelo pedidos personalizado para procesar los datos.
    $pedido = new PedidosPersonalizados;

    // Se verifica si el parámetro es un valor correcto, de lo contrario se direcciona a la página web de origen.
    if ($pedido->setId($_GET['id'])) {
        // Se instancia la clase para crear el reporte.
        $pdf = new Report;
        // Se inicia el reporte con el encabezado del documento.
        $pdf->startReport('Detalle de pedido personalizado', 'p');
        //Se verifica que el pedido exista
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
            $pdf->cell(175, 10, utf8_decode($rowPedido['id_pedidos_personalizado']), 1, 1, 'C', 1);
            $pdf->ln(5); //Espaciado
            //NOMBRE DEL CLIENTE
            $pdf->setFillColor(252, 85, 85, 1);
            // Se establece la fuente para los encabezados.
            $pdf->setFont('Times', 'B', 11);
            //Se llenan las celdas con la información del readPedido
            $pdf->cell(40, 10, utf8_decode('Nombre del cliente'), 1, 0, 'C', 1);
            $pdf->setFillColor(252, 190, 190, 1);
            // Se establece la fuente para los encabezados.
            $pdf->setFont('Times', '', 11);
            //Se llenan las celdas con la información del readPedido
            $pdf->cell(108, 10, utf8_decode($rowPedido['nombre_cliente'] . ' ' . $rowPedido['apellido_cliente']), 1, 0, 'C', 1);
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
            $pdf->cell(24, 10, utf8_decode($rowPedido['fecha_pedidopersonal']), 1, 1, 'C', 1);
            $pdf->Ln(05);
            //Correo del cliente
            $pdf->setFillColor(252, 85, 85, 1);
            // Se establece la fuente para los encabezados.
            $pdf->setFont('Times', 'B', 11);
            //Se llenan las celdas con la información del readPedido
            $pdf->cell(40, 10, utf8_decode('Correo del cliente'), 1, 0, 'C', 1);
            $pdf->setFillColor(252, 190, 190, 1);
            // Se establece la fuente para los encabezados.
            $pdf->setFont('Times', '', 11);
            //Se llenan las celdas con la información del readPedido
            $pdf->cell(97, 10, utf8_decode($rowPedido['correo_cliente']), 1, 0, 'C', 1);
            //ESPACIO ENTRE CELDA
            $pdf->cell(3, 10, ' ', 0, 0, 'C');
            //TAMAÑO SELECCIONADO
            $pdf->setFillColor(252, 85, 85, 1);
            // Se establece la fuente para los encabezados.
            $pdf->setFont('Times', 'B', 11);
            //Se llenan las celdas con la información del readPedido
            $pdf->cell(25, 10, utf8_decode('Tamaño'), 1, 0, 'C', 1);
            $pdf->setFillColor(252, 190, 190, 1);
            // Se establece la fuente para los encabezados.
            $pdf->setFont('Times', '', 11);
            //Se llenan las celdas con la información del readPedido
            $pdf->cell(40, 10, utf8_decode($rowPedido['tamano']), 1, 1, 'C', 1);
            $pdf->Ln(05);//Espaciado
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
            //DESCRIPCIÓN del pedido
            $y = $y + 25;
            $x = 5;
            $pdf->SetXY($x, $y);
            $pdf->setFillColor(252, 85, 85, 1);
            $pdf->setFont('Times', 'B', 11);
            $pdf->MultiCell(50, 20, utf8_decode('Descripción del pedido'), 1, 'C', 1);
            $pdf->setFillColor(252, 190, 190, 1);
            $pdf->setFont('Times', '', 11);
            $pdf->SetXY($x + 50, $y);
            $pdf->MultiCell(155, 20, utf8_decode($rowPedido['descripcion_pedidopersonal']), 1, 'C', 1);
            $pdf->Ln(5); //Espacio vertical
            $pdf->setFillColor(252, 85, 85, 1);
            $pdf->setFont('Times', 'B', 20);
            //Se llenan las celdas con la información del readPedido
            $pdf->cell(205, 10, utf8_decode('Imagen de referencia'), 1, 1, 'C', 1);
            //DIRECCIÓN DEL CLIENTE
            //DESCRIPCIÓN del pedido
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
            $pdf->setFillColor(252, 85, 85, 1);
            $pdf->setFont('Times', 'B', 20);
            $pdf->AddPage('p', 'letter'); //Espacio vertical
            //Se llenan las celdas con la información del readPedido
            $pdf->cell(205, 10, utf8_decode('Imagen de referencia'), 1, 1, 'C', 1);
            $pdf->Ln(10); //Espacio vertical
            //Espaciado a la derecha
            $pdf->SetX(15);
            if (file_exists("../../images/pedidosper/" . $rowPedido['imagenejemplo_pedidopersonal'])) {
                $pdf->Cell(180,180,$pdf->Image("../../images/pedidosper/" . $rowPedido['imagenejemplo_pedidopersonal'], $pdf->GetX(), $pdf->GetY(), 180, 180));
            } else {
                $pdf->cell(205, 10, utf8_decode('No se pudo encontrar la imagen de referencia'), 1, 1, 'C', 1);
            }
            //Imprimimos el pdf
            $pdf->output('I', 'Reporte del pedido personalizado.pdf', true);
        }else{
            header('location: ../../../views/dashboard/pendientesper.html');
        }
    } else {
        header('location: ../../../views/dashboard/pendientesper.html');
    }
} else {
    header('location: ../../../views/dashboard/pendientesper.html');
}
