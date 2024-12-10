<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data' => 'required|array',
            'data.*.departamento' => 'required|string|max:255',
            'data.*.ciudad' => 'required|string|max:255',
            'data.*.nombre_cliente' => 'required|string|max:255',
            'data.*.primer_nombre' => 'required|string|max:255',
            'data.*.telefono' => 'required|integer',
            'data.*.correo' => 'required|email|max:255',
            'data.*.tienda' => 'required|string|max:255',
            'data.*.vendedor' => 'required|string|max:255',
            'data.*.metodo_pago' => 'required|string|max:255',
            'data.*.segmentacion' => 'required|string|max:255',
            'data.*.alerta_devolucion' => 'required|string|max:255',
            'data.*.ordenes' => 'required|integer',
            'data.*.fecha_primera_orden' => 'required|date',
            'data.*.entregadas' => 'required|integer',
            'data.*.devoluciones' => 'required|integer',
            'data.*.fecha_ultima_orden' => 'required|date',
            'data.*.fecha_ultima_orden_entregada' => 'required|date',
            'data.*.ventas' => 'required|numeric',
            'data.*.ingresos' => 'required|numeric',
            'data.*.valor_devolucion' => 'required|numeric',
            'data.*.ultimo_item_comprado' => 'required|string|max:255',
            'data.*.antepenultimo_item_comprado' => 'nullable|string|max:255',
            'data.*.ultimos_dias_compra' => 'required|integer',
        ];
    }
}
