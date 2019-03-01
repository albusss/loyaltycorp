<?php

namespace App\Services\MailChimp;

use App\Database\Entities\MailChimp\MailChimpList;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Mailchimp\Mailchimp;

class ListService
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \Mailchimp\Mailchimp
     */
    private $mailChimp;

    /**
     * ListsController constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \Mailchimp\Mailchimp $mailchimp
     */
    public function __construct(EntityManagerInterface $entityManager, Mailchimp $mailchimp)
    {
        $this->entityManager = $entityManager;
        $this->mailChimp = $mailchimp;
    }

    /**
     * Create MailChimp list.
     *
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        $list = new MailChimpList($data);

        $this->entityManager->persist($list);
        $this->entityManager->flush();

        // Save list into MailChimp
        $response = $this->mailChimp->post('lists', $list->toMailChimpArray());

        $list->setMailChimpId($response->get('id'));

        $this->entityManager->persist($list);
        $this->entityManager->flush();

        return $list->toArray();
    }

    /**
     * Remove MailChimp list.
     *
     * @param string $listId
     * @throws EntityNotFoundException
     */
    public function remove(string $listId): void
    {
        /** @var \App\Database\Entities\MailChimp\MailChimpList|null $list */
        $list = $this->entityManager->getRepository(MailChimpList::class)->find($listId);

        if ($list === null) {

            throw new EntityNotFoundException(sprintf('MailChimpList[%s] not found', $listId));
        }

        $this->entityManager->remove($list);
        $this->entityManager->flush();

        // Remove list from MailChimp
        $this->mailChimp->delete(sprintf('lists/%s', $list->getMailChimpId()));
    }

    /**
     * Retrieve and return MailChimp list.
     *
     * @param string $listId
     * @return array
     * @throws EntityNotFoundException
     */
    public function show(string $listId): array
    {
        /** @var \App\Database\Entities\MailChimp\MailChimpList|null $list */
        $list = $this->entityManager->getRepository(MailChimpList::class)->find($listId);

        if ($list === null) {

            throw new EntityNotFoundException(sprintf('MailChimpList[%s] not found', $listId));
        }

        return $list->toArray();
    }

    /**
     * Update MailChimp list.
     *
     * @param array $data
     * @param string $listId
     * @return array
     * @throws EntityNotFoundException
     */
    public function update(array $data, string $listId): array
    {
        /** @var \App\Database\Entities\MailChimp\MailChimpList|null $list */
        $list = $this->entityManager->getRepository(MailChimpList::class)->find($listId);

        if ($list === null) {

            throw new EntityNotFoundException(sprintf('MailChimpList[%s] not found', $listId));
        }

        // Update list properties
        $list->fill($data);

        $this->entityManager->persist($list);
        $this->entityManager->flush();

        // Update list into MailChimp
        $this->mailChimp->patch(\sprintf('lists/%s', $list->getMailChimpId()), $list->toMailChimpArray());

        return $list->toArray();
    }
}
