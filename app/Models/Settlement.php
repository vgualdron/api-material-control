<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    public $table = "settlement";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'consecutive',
        'third',
        'date',
        'subtotal_amount',
        'subtotal_settlement',
        'total_settle',
        'unit_royalties',
        'royalties',
        'retentions_percentaje',
        'retentions',
        'observation',
        'invoice',
        'invoice_date',
        'internal_document',
        'start_date',
        'final_date',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
    ];

    function toArray(){
        return [
            'id' => $this->id,
            'consecutive' => $this->consecutive,
            'third' => $this->third,
            'date' => $this->date,
            'subtotalAmount' => $this->subtotal_amount,
            'subtotalSettlement' => $this->subtotal_settlement,
            'totalSettle' => $this->total_settle,
            'unitRoyalties' => $this->unit_royalties,
            'royalties' => $this->royalties,
            'retentionsPercentage' => $this->retentions_percentage,
            'retentions' => $this->retentions,
            'observation' => $this->observation,
            'invoice' => $this->invoice,
            'invoiceDate' => $this->invoice,
            'internalDocument' => $this->internal_document,
            'startDate' => $this->start_date,
            'finalDate' => $this->final_date
        ];
    }
}
