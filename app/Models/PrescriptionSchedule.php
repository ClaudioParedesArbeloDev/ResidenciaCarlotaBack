<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class PrescriptionSchedule extends Model {
    protected $fillable = ['medical_prescription_id','scheduled_time','label'];
    public function prescription(): BelongsTo { return $this->belongsTo(MedicalPrescription::class); }
}
