<?php

declare(strict_types=1);

namespace HydroCommunity\Raindrop\Classes\Helpers;

use Cms\Classes\Page;
use Cms\Helpers\Cms;
use HydroCommunity\Raindrop\Models\Settings;
use Illuminate\Http\RedirectResponse;

/**
 * Class UrlHelper
 *
 * @package HydroCommunity\Raindrop\Classes
 */
final class UrlHelper
{
    public const URL_SETUP = '/hydro-raindrop/setup';
    public const URL_MFA = '/hydro-raindrop/mfa';

    /**
     * @var Cms
     */
    private $cmsHelper;

    public function __construct()
    {
        $this->cmsHelper = resolve(Cms::class);
    }

    /**
     * @return string
     */
    public function getSetupUrl(): string
    {
        return $this->cmsHelper->url(self::URL_SETUP);
    }

    /**
     * @return RedirectResponse
     */
    public function getSetupResponse(): RedirectResponse
    {
        return redirect()->to($this->getSetupUrl());
    }

    /**
     * @return string
     */
    public function getMfaUrl(): string
    {
        return $this->cmsHelper->url(self::URL_MFA);
    }

    /**
     * @return RedirectResponse
     */
    public function getMfaResponse(): RedirectResponse
    {
        return redirect()->to($this->getMfaUrl());
    }

    /**
     * @param bool $backend
     * @return RedirectResponse
     */
    public function getSignOnResponse(bool $backend = null): RedirectResponse
    {
        if ($backend) {
            /** @var \Backend\Helpers\Cms $helper */
            $helper = resolve(\Backend\Helpers\Cms::class);
            return redirect()->to($helper->url('backend/auth/signin'));
        }

        $page = Settings::get('page_sign_on');
        $url = '/';

        if (!empty($page)) {
            $url = Page::url($page);
        }

        return redirect()->to($url);
    }

    /**
     * @param bool $backend
     * @return RedirectResponse
     */
    public function getRedirectResponse(bool $backend): RedirectResponse
    {
        if ($backend) {
            /** @var \Backend\Helpers\Cms $helper */
            $helper = resolve(\Backend\Helpers\Cms::class);
            return redirect()->to($helper->url());
        }

        $page = Settings::get('page_redirect');

        if ($page === '') {
            return redirect()->refresh();
        }

        return redirect()->to(Page::url($page));
    }
}
