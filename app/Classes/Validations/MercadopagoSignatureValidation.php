<?php

namespace App\Classes\Validations;

use Illuminate\Http\Request;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;
use Spatie\WebhookClient\WebhookConfig;

class MercadopagoSignatureValidation implements SignatureValidator
{
    public function isValid(Request $request, WebhookConfig $config): bool
    {
        if (!$request->application_id || !$request->user_id) {
            return false;
        }

        $requestSecret = $request->application_id . $request->user_id;
        $signingSecret = $config->signingSecret;

        $computedSignature = hash_hmac('sha256', $request->getContent(), $signingSecret);

        return hash_equals($requestSecret, $computedSignature);
    }
}