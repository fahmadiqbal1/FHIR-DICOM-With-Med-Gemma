<?php

namespace App\Helpers;

use App\Models\User;

class RoleHelper
{
    /**
     * Check if user has a specific role
     */
    public static function userHasRole(User $user, string $roleName): bool
    {
        return $user->roles()->where('name', $roleName)->exists();
    }

    /**
     * Check if user is a radiologist
     */
    public static function isRadiologist(User $user): bool
    {
        return self::userHasRole($user, 'Radiologist');
    }

    /**
     * Check if user is a doctor
     */
    public static function isDoctor(User $user): bool
    {
        return self::userHasRole($user, 'Doctor');
    }

    /**
     * Check if user is an admin
     */
    public static function isAdmin(User $user): bool
    {
        return self::userHasRole($user, 'Admin');
    }

    /**
     * Check if user is a lab technician
     */
    public static function isLabTechnician(User $user): bool
    {
        return self::userHasRole($user, 'Lab Technician');
    }

    /**
     * Check if user is a pharmacist
     */
    public static function isPharmacist(User $user): bool
    {
        return self::userHasRole($user, 'Pharmacist');
    }

    /**
     * Check if user is an owner
     */
    public static function isOwner(User $user): bool
    {
        return self::userHasRole($user, 'owner') || self::userHasRole($user, 'Owner');
    }

    /**
     * Check if user is a patient
     */
    public static function isPatient(User $user): bool
    {
        return self::userHasRole($user, 'Patient');
    }
}
