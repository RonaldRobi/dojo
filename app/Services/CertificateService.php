<?php

namespace App\Services;

use App\Models\EventCertificate;
use App\Models\EventRegistration;

class CertificateService
{
    public function generateCertificate(EventRegistration $registration, string $certificateType = 'participation'): EventCertificate
    {
        // Generate certificate URL (would integrate with PDF generation library)
        $certificateUrl = '/certificates/' . $registration->id . '_' . time() . '.pdf';

        return EventCertificate::create([
            'event_registration_id' => $registration->id,
            'certificate_url' => $certificateUrl,
            'issued_at' => now(),
            'issued_by_instructor_id' => auth()->user()->hasRole('coach') ? auth()->user()->id : null,
            'certificate_type' => $certificateType,
        ]);
    }
}

