<?php

namespace App\Mail;

use App\Models\Doctor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class DailyDoctorReportMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $appointments;
    public $doctor;

    public function __construct(Doctor $doctor, Collection $appointments)
    {
        $this->doctor = $doctor->load('user');
        $this->appointments = $appointments;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu Agenda del Día - ' . now()->format('d/m/Y'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.daily-doctor-report',
        );
    }
}
