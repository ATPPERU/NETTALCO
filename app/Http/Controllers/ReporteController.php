<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\OrderController;
use PhpOffice\PhpSpreadsheet\IOFactory;
class ReporteController extends Controller
{
    // Método para mostrar los reportes
    // Controlador
    public function show($id)
    {
        $reporte = Reporte::find($id);

        // Decodificar manualmente si no se está convirtiendo automáticamente
        if (is_string($reporte->rows)) {
            $reporte->rows = json_decode($reporte->rows, true);
        }

        return view('reporte.show', compact('reporte'));
    }


    // Método para mostrar el formulario de búsqueda
    public function searchForm()
    {
        return view('reportes.search');
    }

    // Método para buscar el reporte por código
    public function search(Request $request)
    {
        // Validar que el código no esté vacío
        $request->validate([
            'codigo' => 'required|string'
        ]);

        // Buscar el reporte por código
        $reporte = Reporte::where('codigo', $request->codigo)->first();

        if ($reporte) {
            // Si se encuentra el reporte, devolverlo a la vista
            return view('reportes.search', compact('reporte'));
        } else {
            // Si no se encuentra el reporte, mostrar un mensaje de error
            $error = 'No se encontró un reporte con ese código.';
            return view('reportes.search', compact('error'));
        }
    }

    public function buscarPorCodigo($codigo)
{
    // Normalizar el código
    $codigo = strtoupper(trim($codigo));

    $reporte = Reporte::where('codigo', $codigo)->first();

    if ($reporte) {
        return response()->json([
            'success' => true,
            'headers' => $reporte->headers,
            'rows' => $reporte->rows,
            'nombre' => $reporte->nombre,
            'codigo' => $reporte->codigo
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'No se encontró el reporte'
        ], 404);
    }
}


public function guardar(Request $request)
{
    // Obtener el último código existente
    $ultimo = Reporte::orderBy('id', 'desc')->first();

    if ($ultimo && preg_match('/A-(\d+)/', $ultimo->codigo, $match)) {
        $numero = intval($match[1]) + 1;
    } else {
        $numero = 1;
    }

    // Formatear con ceros a la izquierda
    $codigo = 'A-' . str_pad($numero, 8, '0', STR_PAD_LEFT);

    // Crear el nuevo reporte
    $reporte = new Reporte();
    $reporte->codigo = $codigo;
    $reporte->nombre = 'Carga de prueba desde API';
    $reporte->headers = $request->input('headers');
    $reporte->rows = $request->input('rows');
    $reporte->save();

    return response()->json(['success' => true, 'id' => $reporte->id, 'codigo' => $codigo]);
}
public function importarExcel(Request $request)
{

    $request->validate([
        'archivo_excel' => 'required|file|mimes:xlsx,xls|max:2048',
    ]);

    $file = $request->file('archivo_excel');

    if (!$file) {
        return back()->with('error', 'No se pudo cargar el archivo Excel.');
    }

    // Leer archivo Excel
    $spreadsheet = IOFactory::load($file->getRealPath());
    $sheet = $spreadsheet->getActiveSheet();
    $data = $sheet->toArray();

    // Extraer encabezados y filas
    $headersExcel = array_map('trim', $data[0]);
    $rowsExcelRaw = array_slice($data, 1);

    // Convertir filas del Excel a formato asociativo usando los encabezados
    $rowsExcel = [];
    foreach ($rowsExcelRaw as $row) {
        $rowsExcel[] = array_combine($headersExcel, $row);
    }

    // ✅ Llamar a la API simulada internamente sin HTTP
    $response = app(OrderController::class)->simularApi();
    $data = $response->getData(true);

    $headersJson = $data['headers'];
    $rowsJsonRaw = $data['rows'];

    $rowsJson = [];
    foreach ($rowsJsonRaw as $row) {
        $rowsJson[] = array_combine($headersJson, $row);
    }

    // Indexar JSON por 'po_number'
    $indexExcel = [];
    foreach ($rowsExcel as $row) {
        $indexExcel[$row['po_number']] = $row;
    }

    $combinedHeaders = array_unique(array_merge($headersJson, $headersExcel));
    $combinedRows = [];

    foreach ($rowsJson as $jsonRow) {
        $po = $jsonRow['po_number'];
        $excelData = $indexExcel[$po] ?? [];

        $combinedRow = [];
        foreach ($combinedHeaders as $h) {
            $combinedRow[$h] = $jsonRow[$h] ?? $excelData[$h] ?? null;
        }

        $combinedRows[] = $combinedRow;
    }

    return view('reportes.combinados', [
        'headers' => $combinedHeaders,
        'rows' => $combinedRows
    ]);
}
}
