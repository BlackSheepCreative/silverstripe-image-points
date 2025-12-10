<?php

namespace LittleGiant\SilverStripeImagePoints\Forms;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;

/**
 * Trait EditableDataObject
 *
 * @package App\Traits
 */
trait EditableDataObject
{
    /**
     * @see \SilverStripe\ORM\DataObject::canView()
     * @param null|Member $member
     */
    public function canView($member = null): bool
    {
        return Permission::checkMember($member, 'CMS_ACCESS');
    }

    /**
     * @see \SilverStripe\ORM\DataObject::canEdit()
     * @param null|Member $member
     */
    public function canEdit($member = null): bool
    {
        return Permission::checkMember($member, 'CMS_ACCESS');
    }

    /**
     * @see \SilverStripe\ORM\DataObject::canDelete()
     * @param null|Member $member
     */
    public function canDelete($member = null): bool
    {
        return Permission::checkMember($member, 'CMS_ACCESS');
    }

    /**
     * @see \SilverStripe\ORM\DataObject::canCreate()
     * @param null|Member $member
     * @param array $context
     */
    public function canCreate($member = null, $context = []): bool
    {
        return Permission::checkMember($member, 'CMS_ACCESS');
    }
}
