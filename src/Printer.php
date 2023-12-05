<?php

namespace Ticketeradigital\Printers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class Printer extends Model
{
    public $fillable = ['ip', 'mac', 'name'];

    public function sendJob(array $job): void
    {
        $payload = [
            'printerId' => $this->id,
            'job' => $job,
        ];
        try {
            $response = Http::timeout(env('PRINTER_PROXY_TIMEOUT', 15))
                ->retry(env('PRINTER_PROXY_RETRIES', 3), env('PRINTER_PROXY_RETRY_WAIT', 100))
                ->post(config('printers.proxy_url').'/print-job', $payload)->throw();
        } catch (RequestException $e) {
            throw new PrintException($e->response->json()['error']);
        } catch (ConnectionException $e) {
            throw new PrintException('Error al conectar con el proxy de impresión');
        }
    }
}
