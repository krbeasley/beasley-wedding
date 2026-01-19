<?php

namespace App\Http\API;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse extends JsonResponse
{
    public int $status;
    public string $message;
    public array $payload;

    public function __construct(
        array $payload = [],
        int $status = 200,
        string $message = "Success",
    )
    {
        $headers = [
            "Access-Control-Allow-Origin" => "*",
            "Content-Type" => "application/json",
        ];

        $this->status = $status;
        $this->message = $message;
        $this->payload = $payload;

        parent::__construct(
            $this->payload,
            $this->status,
            $headers,
        );
    }

    public static function failRateLimit(?string $message = null, bool $json = false) : ApiResponse
    {
        return (new ApiResponse(
            [$message ?? "Rate Limit"],
            429,
            $message ?? "Too many requests. Please try again in a few minutes."
        ));
    }

    public static function failBadRequest(?string $message = null) : ApiResponse
    {
        return new ApiResponse([$message ?? "Bad Request"], 400, $message ?? "Invalid request parameters.");
    }
}
