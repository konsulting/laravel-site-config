<?php

namespace Konsulting\Laravel\SiteConfig;

use Illuminate\Auth\Access\HandlesAuthorization;
use Qce\User;

class SiteConfigPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the site-config.
     *
     * @param  User       $user
     * @param  SiteConfig $siteConfig
     * @return mixed
     */
    public function view(User $user, SiteConfig $siteConfig)
    {
        return $user->can('manage-site-config');
    }

    /**
     * Determine whether the user can create site-configs.
     *
     * @param  User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('manage-site-config');
    }

    /**
     * Determine whether the user can update the site-config.
     *
     * @param  User       $user
     * @param  SiteConfig $siteConfig
     * @return mixed
     */
    public function update(User $user, SiteConfig $siteConfig)
    {
        return $user->can('manage-site-config');
    }

    /**
     * Determine whether the user can delete the site-config.
     *
     * @param  User       $user
     * @param  SiteConfig $siteConfig
     * @return mixed
     */
    public function delete(User $user, SiteConfig $siteConfig)
    {
        return $user->can('manage-site-config');
    }
}
