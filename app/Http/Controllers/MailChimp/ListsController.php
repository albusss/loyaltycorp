<?php
declare(strict_types=1);

namespace App\Http\Controllers\MailChimp;

use App\Database\Entities\MailChimp\MailChimpList;
use App\Http\Controllers\Controller;
use App\Services\MailChimp\ListService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mailchimp\Mailchimp;

class ListsController extends Controller
{
    /**
     * @var \Mailchimp\Mailchimp
     */
    private $mailChimp;

    /**
     * @var \App\Services\MailChimp\ListService
     */
    private $listService;

    /**
     * ListsController constructor.
     *
     * @param \Mailchimp\Mailchimp $mailchimp
     * @param \App\Services\MailChimp\ListService $listService
     */
    public function __construct(Mailchimp $mailchimp, ListService $listService)
    {
        $this->mailChimp = $mailchimp;
        $this->listService = $listService;
    }

    /**
     * Create MailChimp list.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        // Validate data
        $validator = $this->getValidationFactory()->make($request->all(), MailChimpList::getValidationRules());

        if ($validator->fails()) {
            // Return error response if validation failed
            return $this->errorResponse([
                'message' => 'Invalid data given',
                'errors' => $validator->errors()->toArray()
            ]);
        }

        try {
            $result = $this->listService->create($request->all());
        } catch (Exception $exception) {
            // Return error response if something goes wrong
            return $this->errorResponse(['message' => $exception->getMessage()]);
        }

        return $this->successfulResponse($result);
    }

    /**
     * Remove MailChimp list.
     *
     * @param string $listId
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove(string $listId): JsonResponse
    {
        try {
            $this->listService->remove($listId);
        } catch (Exception $exception) {
            return $this->errorResponse(['message' => $exception->getMessage()]);
        }

        return $this->successfulResponse([]);
    }

    /**
     * Retrieve and return MailChimp list.
     *
     * @param string $listId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $listId): JsonResponse
    {
        try {
            $result = $this->listService->show($listId);
        } catch (Exception $exception) {
            return $this->errorResponse(['message' => $exception->getMessage()]);
        }

        return $this->successfulResponse($result);
    }

    /**
     * Update MailChimp list.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $listId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $listId): JsonResponse
    {
        // Validate data
        $validator = $this->getValidationFactory()->make($request->all(), MailChimpList::getValidationRules());

        if ($validator->fails()) {
            // Return error response if validation failed
            return $this->errorResponse([
                'message' => 'Invalid data given',
                'errors' => $validator->errors()->toArray()
            ]);
        }

        try {
            $result = $this->listService->update($request->all(), $listId);
        } catch (Exception $exception) {
            return $this->errorResponse(['message' => $exception->getMessage()]);
        }

        return $this->successfulResponse($result);
    }
}
