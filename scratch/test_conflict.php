<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Appointment;

$appt = Appointment::first();
if (!$appt) {
    echo "No appointments found to test with.\n";
    exit;
}

echo "Testing with Appointment ID: {$appt->id}\n";
echo "Range: {$appt->date} {$appt->start_time} - {$appt->end_time}\n";

// Simulate a new request with the same doctor and same time
$request = (object)[
    'doctor_id' => $appt->doctor_id,
    'date' => $appt->date,
    'start_time' => $appt->start_time,
    'end_time' => $appt->end_time
];

$doctorConflict = Appointment::where('doctor_id', $request->doctor_id)
    ->where('date', $request->date)
    ->where(function ($query) use ($request) {
        $query->where('start_time', '<', $request->end_time)
              ->where('end_time', '>', $request->start_time);
    })
    ->exists();

if ($doctorConflict) {
    echo "SUCCESS: Conflict detected for doctor.\n";
} else {
    echo "FAILURE: Conflict NOT detected for doctor.\n";
}

// Simulate a non-overlapping request
$requestNonOverlap = (object)[
    'doctor_id' => $appt->doctor_id,
    'date' => $appt->date,
    'start_time' => '23:00',
    'end_time' => '23:30'
];

$noConflict = Appointment::where('doctor_id', $requestNonOverlap->doctor_id)
    ->where('date', $requestNonOverlap->date)
    ->where(function ($query) use ($requestNonOverlap) {
        $query->where('start_time', '<', $requestNonOverlap->end_time)
              ->where('end_time', '>', $requestNonOverlap->start_time);
    })
    ->exists();

if (!$noConflict) {
    echo "SUCCESS: No conflict detected for non-overlapping time.\n";
} else {
    echo "FAILURE: False conflict detected for non-overlapping time.\n";
}
