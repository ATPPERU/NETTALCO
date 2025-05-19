<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Especifica la tabla asociada al modelo
    protected $table = 'orders';

    // Los campos que pueden ser llenados masivamente
    protected $fillable = [
        'factory',
        'season',
        'del',
        'order_type',
        'po_number',
        'new_po_number',
        'customer',
        'material_number',
        'style_number',
        'style_name',
        'color_code',
        'color_description',
        'fabric_description',
        'product_category',
        'picture',
        'po_frd_confirmed_fob',
        'po_delivery_to_tm_fob',
        'rev_po_frd_confirmed_fob',
        'rev_po_delivery_to_tm_fob',
        'po_frd_ready_ldp',
        'rev_po_frd_ready_ldp',
        'ship_mode',
        'revised_ship_mode',
        'original_qty',
        'cut_qty',
        'shipped_qty',
        'shortage_qty',
        'replace_yn',
        'order_date',
        'estimate_date',
        'actual_date',
        'actual_in_date',
        'pass_fail',
        'estimate_start',
        'estimate_complete',
        'actual_complete',
        'percent_complete',
        'estimate_2',
        'actual_2',
        'percent_complete_2',
        'estimate_3',
        'actual_3',
        'percent_complete_3',
        'estimate_4',
        'actual_4',
        'percent_complete_4',
        'approval_date',
        'est_send_1',
        'act_send_1',
        'approval_date_2',
        'est_send_2',
        'act_send_2',
        'approval_date_3',
        'comments'
    ];

    // Los campos que no deben ser visibles cuando se obtiene el modelo
    protected $hidden = [
        // Si hay campos sensibles o que no deseas mostrar
    ];

    // Para manejar las fechas automáticamente
    protected $dates = [
        'po_frd_confirmed_fob',
        'po_delivery_to_tm_fob',
        'rev_po_frd_confirmed_fob',
        'rev_po_delivery_to_tm_fob',
        'po_frd_ready_ldp',
        'rev_po_frd_ready_ldp',
        'order_date',
        'estimate_date',
        'actual_date',
        'actual_in_date',
        'estimate_start',
        'estimate_complete',
        'actual_complete',
        'estimate_2',
        'actual_2',
        'estimate_3',
        'actual_3',
        'estimate_4',
        'actual_4',
        'approval_date',
        'est_send_1',
        'act_send_1',
        'approval_date_2',
        'est_send_2',
        'act_send_2',
        'approval_date_3',
    ];

    // Si la tabla no tiene campos created_at y updated_at, deshabilítalos
    public $timestamps = true;

    // Si necesitas definir un nombre de clave primaria diferente
    // protected $primaryKey = 'id';
}
