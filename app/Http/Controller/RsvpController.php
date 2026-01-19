<?php

namespace App\Http\Controller;

use App\Database\Database;
use App\General;
use App\Http\API\ApiResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RsvpController extends Controller
{
    // The main RSVP page
    public static function index() : Response
    {
        $errors = array();
        if (isset($_SESSION["form_errors"])) {
            $errors = unserialize($_SESSION["form_errors"]);
            General::clearSessionSetting("form_errors");
        }

        // check if the user is responding or has responded
        $isResponding = false;
        $hasResponded = false;
        if (General::getSessionSetting('confirm_id')) {
            $isResponding = true;
            if (General::getSessionSetting('confirm_has_responded')) {
                $hasResponded = true;
            }
        } else {
            // Check the user's cookies too
            if (isset($_COOKIE["confirm_data"])) {
                $confirmData = unserialize($_COOKIE["confirm_data"]);
                $isResponding = isset($confirmData["confirm_id"]);
                $hasResponded = isset($confirmData["confirm_has_responded"]);
            }
        }

        $c = new RsvpController();

        try {
            $html = $c->twig->render("pages/rsvp.html.twig", [
                // todo: handle these values on front end
                "errors" => $errors,
                "hasResponded" => $hasResponded,
                "isResponding" => $isResponding
            ]);
            return new Response($html, Response::HTTP_OK);
        }
        catch (\Exception $e) {
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // RSVP Confirmation Page
    public static function confirm(Request $request) : Response|RedirectResponse
    {
        if (General::isRateLimited("/rsvp/confirm")) {
            return new Response("Too many requests. Please try again in a bit.", Response::HTTP_TOO_MANY_REQUESTS);
        }

        $formErrors = array();
        if (!empty($request->request->get("your-product-key"))) {
            General::setRateLimit("/rsvp/confirm", 30);
            return new RedirectResponse("/rsvp");
        }
        else if (empty($request->get('g')) || empty($request->get('f')) || empty($request->get('l')) || empty($request->get('p'))) {
            General::setRateLimit("/rsvp/confirm", 10);
            return new RedirectResponse("/rsvp");
        }

        General::setSessionSetting("confirm_id", $request->get('g'));
        General::setSessionSetting("confirm_party_id", ($request->get('p') === 'null' ? -1 : $request->get('p')));
        General::setSessionSetting("confirm_first_name", $request->get('f'));
        General::setSessionSetting("confirm_last_name", $request->get('l'));

        $confirmGuestId = intval(General::getSessionSetting("confirm_id"));
        $confirmFirstName = General::getSessionSetting("confirm_first_name");
        $confirmLastName = General::getSessionSetting("confirm_last_name");
        $confirmPartyId = intval(General::getSessionSetting("confirm_party_id"));

        // Get data for a partyless guest
        if ($confirmPartyId === -1) {
            $sql = "SELECT g.first_name, g.last_name, g.allowed_plus_one FROM tbl_guest g WHERE g.id = :guestId AND g.first_name = :first AND g.last_name = :last AND g.deleted = 0";
            $p = ['guestId' => $confirmGuestId, 'first' => $confirmFirstName, 'last' => $confirmLastName];
            $twigFilePath = "pages/rsvp-confirm-guest.html.twig";
        }
        // Get data for whole party
        else {
            $sql = "SELECT g.first_name, g.last_name, g.allowed_plus_one FROM tbl_guest g LEFT JOIN tbl_parties p ON g.party_id = p.id WHERE g.party_id = :partyId AND g.deleted = 0 AND p.deleted = 0;";
            $p = ['partyId' => $confirmPartyId];
            $twigFilePath = "pages/rsvp-confirm-party.html.twig";
        }

        $data = Database::sqlQuery($sql, $p);

        // Sanity check...
        // make sure that the first and last name match at least ONE of the found guests
        // if these records were simply located via party id. Legit users will have the right values
        if (count($data) > 1) {
            $matched = false;
            foreach ($data as $r) {
                if ($r['first_name'] === $confirmFirstName && $r['last_name'] === $confirmLastName) {
                    $matched = true;
                }
            }

            if (!$matched) {
                // Just send them back for now
                return new RedirectResponse("/rsvp");
            }
        }

        $c = new RsvpController();
        return new Response($c->twig->render($twigFilePath, [
            "data" => $data
        ]));
    }
}
