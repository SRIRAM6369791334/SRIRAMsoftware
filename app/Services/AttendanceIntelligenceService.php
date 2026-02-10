<?php

declare(strict_types=1);

namespace App\Services;

final class AttendanceIntelligenceService
{
    public function overtimeMinutes(int $workedMinutes, int $shiftMinutes = 480): int
    {
        return max(0, $workedMinutes - $shiftMinutes);
    }

    public function autoLeaveDeductionDays(int $absentDays, int $graceAbsences = 1): int
    {
        return max(0, $absentDays - $graceAbsences);
    }

    public function anomalyFlags(array $attendanceRows): array
    {
        $flags = [];
        foreach ($attendanceRows as $row) {
            if (($row['punch_in'] ?? null) === null || ($row['punch_out'] ?? null) === null) {
                $flags[] = ['employee_id' => $row['employee_id'] ?? null, 'type' => 'missing_punch'];
            }
            if (($row['geo_valid'] ?? 1) === 0) {
                $flags[] = ['employee_id' => $row['employee_id'] ?? null, 'type' => 'geo_mismatch'];
            }
        }
        return $flags;
    }
}
