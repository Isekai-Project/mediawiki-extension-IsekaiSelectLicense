<?php
namespace Isekai\SelectLicense;

use MediaWiki\Html\Html;
use MediaWiki\Parser\Parser;
use MediaWiki\Title\Title;
use PPFrame;

class SelectLicenseTag {
    public static function onParserSetup(Parser &$parser) {
        $parser->setHook('license', self::class . '::licenseTag');
        return true;
    }

    public static function onParserOptionsRegister(&$defaults, &$inCacheKey, &$lazyLoad) {
        $defaults['isekai-license'] = false;
        $inCacheKey['isekai-license'] = true;
    }

    public static function licenseTag($text, $params, Parser $parser, PPFrame $frame) {
        global $wgIsekaiLicenses;

        if ($text) { //必须存在license才行
            $text = $frame->expand($text);
            if (isset($wgIsekaiLicenses[$text])) {
                $licenseData = $wgIsekaiLicenses[$text];

                if (isset($params['type'])) {
                    switch ($params['type']) {
                        case 'box':
                            self::setLicense($text, $licenseData, $params, $parser, $frame);
                            return self::makeLicenseBox($text, $licenseData, $params, $parser, $frame);
                        case 'name':
                            return self::getLicenseName($text, $licenseData, $params, $parser, $frame);
                        case 'link':
                            return self::getLicenseLink($text, $licenseData, $params, $parser, $frame);
                    }
                }

                //默认操作
                self::setLicense($text, $licenseData, $params, $parser, $frame);
            } else {
                return '<p>' . wfMessage("isekai-selectlicense-notexists")->text() . '</p>';
            }
        }
        return '';
    }

    public static function setLicense($licenseId, $licenseData, $params, Parser $parser, PPFrame $frame) {
        $parser->getOutput()->setJsConfigVar('wgIsekaiLicense', $licenseId);
    }

    public static function makeLicenseBox($licenseId, $licenseData, $params, Parser $parser, PPFrame $frame) {
        $parser->getOutput()->addModuleStyles(['ext.isekai.selectLicense']);
        $licenseLink = self::getLicenseLink($licenseId, $licenseData, ['target' => '_blank'], $parser, $frame);
        $title = $parser->getPage();
        if ($title instanceof Title && $title->inNamespace(NS_FILE)) {
            $pageType = 'nstab-image';
        } else {
            $pageType = 'nstab-main';
        }

        $infoText = wfMessage("isekai-selectlicense-box-infotext", strtolower(wfMessage($pageType)->text()), $licenseLink)->text();


        $alertType = 'info';
        if (isset($licenseData['openness'])) {
            if ($defaultLicense = self::getDefaultLicense()) {
                if (isset($defaultLicense['openness']) && $licenseData['openness'] < $defaultLicense['openness']) {
                    //当前的授权协议开放程度比默认协议低
                    $infoText .= wfMessage("isekai-selectlicense-box-notdefault", strtolower(wfMessage($pageType)->text()))->text();
                }
            }

            switch ($licenseData['openness']) {
                case 3:
                    $alertType = 'success';
                    break;
                case 2:
                    $alertType = 'info';
                    break;
                case 1:
                    $alertType = 'warning';
                    break;
            }
        }

        //开始生成授权协议显示框
        return '<div class="isekai-alert isekai-alert-' . $alertType . '">' . $infoText . '</div>';
    }

    public static function getLicenseName($licenseId, $licenseData, $params, Parser $parser, PPFrame $frame) {
        if (isset($licenseData['namemsg'])) {
            $licenseData['name'] = wfMessage($licenseData['namemsg'])->text();
        }
        return $licenseData['name'];
    }

    public static function getLicenseLink($licenseId, $licenseData, $params, Parser $parser, PPFrame $frame) {
        if (isset($licenseData['namemsg'])) {
            $licenseData['name'] = wfMessage($licenseData['namemsg'])->text();
        }
        if (isset($licenseData['page'])) {
            $licenseData['url'] = Title::newFromText($licenseData['page'])->getLocalUrl();
        }
        $allowedParams = ['target', 'id', 'class', 'style'];
        $finalParams = array_filter($params, function ($key) use ($allowedParams) {
            return in_array($key, $allowedParams);
        }, ARRAY_FILTER_USE_KEY);

        return Html::element('a', array_merge($finalParams, [
            'href' => $licenseData['url'],
        ]), $licenseData['name']);
    }

    private static function getDefaultLicense() {
        global $wgIsekaiLicenses;
        foreach ($wgIsekaiLicenses as $license) {
            if (isset($license['default']) && $license['default']) {
                return $license;
            }
        }
        return false;
    }
}
