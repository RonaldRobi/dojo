<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Dojo;
use App\Services\AuditService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MemberService
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function create(array $data, Dojo $dojo): Member
    {
        return DB::transaction(function () use ($data, $dojo) {
            $member = Member::create([
                'dojo_id' => $dojo->id,
                'user_id' => $data['user_id'] ?? null,
                'name' => $data['name'],
                'birth_date' => $data['birth_date'] ?? null,
                'gender' => $data['gender'] ?? null,
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'status' => $data['status'] ?? 'active',
                'join_date' => $data['join_date'] ?? now(),
                'style' => $data['style'] ?? null,
                'medical_notes' => $data['medical_notes'] ?? null,
            ]);

            // Generate QR code
            $this->generateQRCode($member);

            $this->auditService->logCreate($member, $data, $dojo->id);

            return $member->fresh();
        });
    }

    public function update(Member $member, array $data): Member
    {
        return DB::transaction(function () use ($member, $data) {
            $oldAttributes = $member->toArray();

            $member->update([
                'name' => $data['name'] ?? $member->name,
                'birth_date' => $data['birth_date'] ?? $member->birth_date,
                'gender' => $data['gender'] ?? $member->gender,
                'phone' => $data['phone'] ?? $member->phone,
                'address' => $data['address'] ?? $member->address,
                'status' => $data['status'] ?? $member->status,
                'style' => $data['style'] ?? $member->style,
                'medical_notes' => $data['medical_notes'] ?? $member->medical_notes,
            ]);

            $this->auditService->logUpdate($member, $oldAttributes, $member->toArray(), $member->dojo_id);

            return $member->fresh();
        });
    }

    public function generateQRCode(Member $member): void
    {
        $qrCode = Str::random(32);
        $expiresAt = now()->addMonths(6); // QR code valid for 6 months

        $member->update([
            'qr_code' => $qrCode,
            'qr_code_expires_at' => $expiresAt,
        ]);
    }

    public function linkParent(Member $member, int $parentUserId, int $dojoId): void
    {
        $member->parents()->syncWithoutDetaching([
            $parentUserId => [
                'dojo_id' => $dojoId,
                'linked_by_user_id' => auth()->id(),
                'linked_at' => now(),
            ],
        ]);
    }
}

