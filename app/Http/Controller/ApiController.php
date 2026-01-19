<?php

namespace App\Http\Controller;

use App\Database\Database;
use App\General;
use App\Http\API\ApiResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController
{
    /** Searches the guest list for a name that kinda sorta matches
     * what was searched for.
     *
     * Ideally, this returns one row. The front end will handle what to
     * do with multiple results.
     *
     * @param Request $request
     * @return ApiResponse
     */
    public static function searchGuestList(Request $request) : ApiResponse
    {
        // Check not rate limited
        if (General::isRateLimited('/api/search-guest-list')) {
            return ApiResponse::failRateLimit();
        }
        // Check has required parameters
        else if (is_null($request->get('s'))) {
            return ApiResponse::failBadRequest("Wrong Parameters");
        }
        // Verify the csrf token is correct
        else if (!General::verifyCSRF($request->headers->get('x-csrf-token'))) {
            return ApiResponse::failBadRequest($request->headers->get('x-csrf-token'));
        }

        // Set the rate limiter
        General::setRateLimit('/api/search-guest-list', 10);

        // Validate the search token
        $searchToken = trim($request->get("s"));
        $searchToken = str_replace("%20", " ", $searchToken);
        if (empty($searchToken)) {
            return ApiResponse::failBadRequest("Bad Parameters");
        }

        // does this string have any weird characters?
        if (preg_match('/[^A-Za-z0-9\s]/', $searchToken)) {
            return ApiResponse::failBadRequest("Bad Request");
        }

        // perform your sql actions here
        $sql = <<<EOT
SELECT 
    g.id AS guest_id, 
    g.first_name, 
    g.last_name, 
    p.id AS party_id 
FROM tbl_guest g 
    LEFT JOIN tbl_parties p ON g.party_id = p.id 
WHERE 
    g.first_name LIKE :s1 
   OR 
    g.last_name LIKE :s2 
   OR 
    CONCAT_WS(' ', g.first_name, g.last_name) LIKE :s3
EOT;
        $results = Database::sqlQuery($sql, [
            's1' => "$searchToken%",
            's2' => "$searchToken%",
            's3' => "$searchToken%"
        ]);

        // return successfully
        return new ApiResponse([
            'data' => $results
        ]);
    }

    /**  */
    public static function getRsvpRequests(Request $request) : ApiResponse
    {
        return new ApiResponse([]);
    }
}
