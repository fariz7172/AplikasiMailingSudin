<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class ReCaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secret = config('services.recaptcha.secret_key');

        if (empty($value)) {
            $fail('Silakan centang verifikasi Captcha terlebih dahulu.');
            return;
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $secret,
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            if (! $response->successful() || ! ($response->json('success') ?? false)) {
                $fail('Verifikasi Captcha gagal atau kadaluarsa. Silakan coba lagi.');
            }
        } catch (\Throwable $e) {
            // Jika terjadi gangguan koneksi ke Google reCAPTCHA
            report($e);
            $fail('Gagal memverifikasi Captcha. Periksa koneksi internet Anda.');
        }
    }
}

