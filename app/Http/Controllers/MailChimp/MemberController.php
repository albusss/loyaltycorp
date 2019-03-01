<?php

namespace App\Http\Controllers\MailChimp;

use App\Http\Controllers\Controller;
use App\Services\MailChimp\MemberService;
use App\Database\Entities\MailChimp\MailChimpMember;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mailchimp\Mailchimp;

class MemberController extends Controller
{
    /**
     * @var \Mailchimp\Mailchimp
     */
    private $mailChimp;

    /**
     * @var \App\Services\MailChimp\MemberService
     */
    private $memberService;

    /**
     * ListsController constructor.
     *
     * @param Mailchimp $mailchimp
     * @param MemberService $memberService
     */
    public function __construct(Mailchimp $mailchimp, MemberService $memberService)
    {
        $this->mailChimp = $mailchimp;
        $this->memberService = $memberService;
    }

    /**
     * Create MailChimp list.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $listId
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request, string $listId): JsonResponse
    {
        // Validate data
        $validator = $this->getValidationFactory()->make($request->all(), MailChimpMember::getValidationRules());

        if ($validator->fails()) {
            // Return error response if validation failed
            return $this->errorResponse([
                'message' => 'Invalid data given',
                'errors' => $validator->errors()->toArray()
            ]);
        }

        try {
            $result = $this->memberService->create($request->all(), $listId);
        } catch (Exception $exception) {
            // Return error response if something goes wrong
            return $this->errorResponse(['message' => $exception->getMessage()]);
        }

        return $this->successfulResponse($result);
    }
}
