<?php

namespace App\Http\Controller;

use App\Helpers\Storage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RegistryController extends Controller 
{
    /** Creates a standardized API Response.
     *
     * @param array|null $data
     * @param int $statusCode
     * @param string|null $statusMessage Custom status message. Otherwise, the defaults are used.
     * @param bool $forceOK Force a 200 response
     * @return JsonResponse
     */
    private static function APIResponse(
        ?array $data = null,
        int $statusCode = 200,
        ?string $statusMessage = null,
        bool $forceOK = false) : JsonResponse
    {
        // Default Status Codes
        $defaultStatusMessages = [
            200 => "Success",
            400 => "Bad Request",
            401 => "Unauthorized",
            403 => "Forbidden",
            404 => "Not Found",
            500 => "Internal Server Error",
            999 => "Unknown Error"
        ];

        // Provide a default status message if no custom one is provided.
        if (is_null($statusMessage)) {
            // Set the status message to "Unknown" if the status code isn't valid.
            $statusMessage = $defaultStatusMessages[$statusCode] ?? $defaultStatusMessages[999];
        }

        // Send back the response
        $response = [
            "StatusCode" => $statusCode,
            "StatusMessage" => $statusMessage,
        ];

        // Append the data to the response if it was included
        if (!is_null($data)) $response['Data'] = $data;

        // Build the headers
        $headers = [
            "Content-Type" => "application/json",
        ];

        return new JsonResponse(
            data: $response,
            status: $forceOK ? Response::HTTP_OK : $statusCode,
            headers: $headers
        );
    }

    public static function viewRegistry() : Response
    {
      $c = new RegistryController();
      $registryData = json_decode(
        Storage::get('registry_data.json'),
        true
      );

      return new Response($c->twig->render("pages/registry.html.twig", [
        "items" => $registryData["Items"],
      ]), Response::HTTP_OK);
    }
}
