<?php

namespace Webkul\PushNotification\Console;

use Illuminate\Console\Command;
use phpseclib3\Crypt\EC;

class GenerateVapidKeysCommand extends Command
{
    protected $signature   = 'push:generate-keys';
    protected $description = 'Generate VAPID keys for Web Push notifications';

    public function handle(): void
    {
        $private = EC::createKey('prime256v1');
        $public  = $private->getPublicKey();

        $pubDer  = base64_decode(preg_replace('/-----[^-]+-----|\\s/', '', $public->toString('PKCS8')));
        $rawPub  = substr($pubDer, -65);

        $privDer = base64_decode(preg_replace('/-----[^-]+-----|\\s/', '', $private->toString('PKCS8')));
        preg_match('/\x02\x01\x01\x04\x20(.{32})/s', $privDer, $m);
        $rawPriv = $m[1] ?? '';

        if (strlen($rawPub) !== 65 || strlen($rawPriv) !== 32) {
            $this->error('Key generation failed — unexpected key length.');
            return;
        }

        $toBase64url = fn($b) => rtrim(strtr(base64_encode($b), '+/', '-_'), '=');
        $pubKey      = $toBase64url($rawPub);
        $privKey     = $toBase64url($rawPriv);

        $this->line('Add these to your <info>.env</info> file:');
        $this->newLine();
        $this->line("VAPID_PUBLIC_KEY={$pubKey}");
        $this->line("VAPID_PRIVATE_KEY={$privKey}");
        $this->newLine();

        $envPath = base_path('.env');

        if (file_exists($envPath)) {
            $env = file_get_contents($envPath);

            if (! str_contains($env, 'VAPID_PUBLIC_KEY')) {
                file_put_contents($envPath, $env . "\nVAPID_PUBLIC_KEY={$pubKey}\nVAPID_PRIVATE_KEY={$privKey}\n");
                $this->info('Keys appended to .env automatically.');
            } else {
                $this->warn('VAPID keys already exist in .env — update them manually if needed.');
            }
        }
    }
}
