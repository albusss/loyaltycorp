<?php

namespace App\Database\Entities\MailChimp;

use Doctrine\ORM\Mapping as ORM;
use EoneoPay\Utils\Str;

/**
 * @ORM\Entity
 * @ORM\Table(name="mail_chimp_member")
 */
class MailChimpMember extends MailChimpEntity
{
    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email_address", type="string")
     */
    private $emailAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="email_type", type="string", nullable=true)
     */
    private $emailType;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string")
     */
    private $status;

    /**
     * @var array
     *
     * @ORM\Column(name="merge_fields", type="array", nullable=true)
     */
    private $mergeFields;

    /**
     * @var array
     *
     * @ORM\Column(name="interests", type="array", nullable=true)
     */
    private $interests;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", nullable=true)
     */
    private $language;

    /**
     * @var bool
     *
     * @ORM\Column(name="vip", type="boolean", nullable=true)
     */
    private $vip;

    /**
     * @var array
     *
     * @ORM\Column(name="location", type="array", nullable=true)
     */
    private $location;

    /**
     * @var array
     *
     * @ORM\Column(name="marketing_permissions", type="array", nullable=true)
     */
    private $marketingPermissions;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_signup", type="string", nullable=true)
     */
    private $isSignUp;

    /**
     * @var string
     *
     * @ORM\Column(name="timestamp_signup", type="string", nullable=true)
     */
    private $timestampSignUp;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_opt", type="string", nullable=true)
     */
    private $ipOpt;

    /**
     * @var string
     *
     * @ORM\Column(name="timestamp_opt", type="string", nullable=true)
     */
    private $timestampOpt;

    /**
     * @var array
     *
     * @ORM\Column(name="tags", type="array", nullable=true)
     */
    private $tags;

    /**
     * @var MailChimpList
     *
     * @ORM\ManyToOne(targetEntity="MailChimpList", inversedBy="members")
     */
    private $list;

    /**
     * Get validation rules for mailchimp entity.
     *
     * @return array
     */
    public static function getValidationRules(): array
    {
        return [
            'email_address' => 'required|string',
            'email_type' => 'nullable|string',
            'status' => 'required|string|in:subscribed,unsubscribed,cleaned,pending',
            'merge_fields' => 'nullable|array',
            'interests' => 'nullable|array',
            'language' => 'nullable|string',
            'vip' => 'nullable|boolean',
            'location' => 'nullable|array',
            'location.latitude' => 'nullable|numeric',
            'location.longitude' => 'nullable|numeric',
            'marketing_permissions' => 'nullable|array',
            'marketing_permissions.marketing_permission_id' => 'nullable|string',
            'marketing_permissions.enabled' => 'nullable|boolean',
            'ip_signup' => 'nullable|string',
            'timestamp_signup' => 'nullable|string',
            'ip_opt' => 'nullable|string',
            'timestamp_opt' => 'nullable|string',
            'tags' => 'nullable|array',
        ];
    }

    /**
     * Get array representation of entity.
     *
     * @return array
     */
    public function toArray(): array
    {
        $array = [];
        $str = new Str();

        foreach (\get_object_vars($this) as $property => $value) {
            $array[$str->snake($property)] = $value;
        }

        return $array;
    }

    /**
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    /**
     * @param string $emailAddress
     * @return MailChimpMember
     */
    public function setEmailAddress(string $emailAddress): MailChimpMember
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getEmailType(): ?string
    {
        return $this->emailType;
    }

    /**
     * @param string $emailType
     * @return MailChimpMember
     */
    public function setEmailType(string $emailType): MailChimpMember
    {
        $this->emailType = $emailType;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return MailChimpMember
     */
    public function setStatus(string $status): MailChimpMember
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return null|array
     */
    public function getMergeFields(): ?array
    {
        return $this->mergeFields;
    }

    /**
     * @param array $mergeFields
     * @return MailChimpMember
     */
    public function setMergeFields(array $mergeFields): MailChimpMember
    {
        $this->mergeFields = $mergeFields;

        return $this;
    }

    /**
     * @return null|array
     */
    public function getInterests(): ?array
    {
        return $this->interests;
    }

    /**
     * @param array $interests
     * @return MailChimpMember
     */
    public function setInterests(array $interests): MailChimpMember
    {
        $this->interests = $interests;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * @param string $language
     * @return MailChimpMember
     */
    public function setLanguage(string $language): MailChimpMember
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return null|bool
     */
    public function isVip(): ?bool
    {
        return $this->vip;
    }

    /**
     * @param bool $vip
     * @return MailChimpMember
     */
    public function setVip(bool $vip): MailChimpMember
    {
        $this->vip = $vip;

        return $this;
    }

    /**
     * @return null|array
     */
    public function getLocation(): ?array
    {
        return $this->location;
    }

    /**
     * @param array $location
     * @return MailChimpMember
     */
    public function setLocation(array $location): MailChimpMember
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return null|array
     */
    public function getMarketingPermissions(): ?array
    {
        return $this->marketingPermissions;
    }

    /**
     * @param array $marketingPermissions
     * @return MailChimpMember
     */
    public function setMarketingPermissions(array $marketingPermissions): MailChimpMember
    {
        $this->marketingPermissions = $marketingPermissions;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getIsSignUp(): ?string
    {
        return $this->isSignUp;
    }

    /**
     * @param string $isSignUp
     * @return MailChimpMember
     */
    public function setIsSignUp(string $isSignUp): MailChimpMember
    {
        $this->isSignUp = $isSignUp;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getTimestampSignUp(): ?string
    {
        return $this->timestampSignUp;
    }

    /**
     * @param string $timestampSignUp
     * @return MailChimpMember
     */
    public function setTimestampSignUp(string $timestampSignUp): MailChimpMember
    {
        $this->timestampSignUp = $timestampSignUp;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getIpOpt(): ?string
    {
        return $this->ipOpt;
    }

    /**
     * @param string $ipOpt
     * @return MailChimpMember
     */
    public function setIpOpt(string $ipOpt): MailChimpMember
    {
        $this->ipOpt = $ipOpt;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getTimestampOpt(): ?string
    {
        return $this->timestampOpt;
    }

    /**
     * @param string $timestampOpt
     * @return MailChimpMember
     */
    public function setTimestampOpt(string $timestampOpt): MailChimpMember
    {
        $this->timestampOpt = $timestampOpt;

        return $this;
    }

    /**
     * @return null|array
     */
    public function getTags(): ?array
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     * @return MailChimpMember
     */
    public function setTags(array $tags): MailChimpMember
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @return null|MailChimpList
     */
    public function getList(): ?MailChimpList
    {
        return $this->list;
    }

    /**
     * @param MailChimpList $list
     * @return MailChimpMember
     */
    public function setList(MailChimpList $list): MailChimpMember
    {
        $this->list = $list;

        return $this;
    }
}
