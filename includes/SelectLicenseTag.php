<?php
namespace Isekai\SelectLicense;

use Html;
use Parser;
use PPFrame;
use Title;

class SelectLicenseTag {
    public static function onParserSetup(Parser &$parser){
        $parser->setHook('license', self::class . '::setLicense');
        $parser->setHook('licensename', self::class . '::getLicenseName');
        $parser->setHook('licenselink', self::class . '::getLicenseLink');
		return true;
    }

    public static function onParserOptionsRegister(&$defaults, &$inCacheKey, &$lazyLoad){
        $defaults['isekai-license'] = false;
        $inCacheKey['isekai-license'] = true;
    }

    public static function setLicense($text, $params, Parser $parser, PPFrame $frame){
        global $wgIsekaiLicenses;
        if($text){
            $text = $frame->expand($text);
            if(isset($wgIsekaiLicenses[$text])){
                $parser->getOutput()->addJsConfigVars('wgIsekaiLicense', $text);
            }
        }
        return '';
    }

    public static function getLicenseName($text, $params, Parser $parser, PPFrame $frame){
        global $wgIsekaiLicenses;
        if($text){
            $text = $frame->expand($text);
            if(isset($wgIsekaiLicenses[$text])){
                $licenseData = $wgIsekaiLicenses[$text];
                if(isset($licenseData['namemsg'])){
                    $licenseData['name'] = wfMessage($licenseData['namemsg'])->text();
                }
                return $licenseData['name'];
            }
        }
        return '';
    }

    public static function getLicenseLink($text, $params, Parser $parser, PPFrame $frame){
        global $wgIsekaiLicenses;
        if($text){
            $text = $frame->expand($text);
            if(isset($wgIsekaiLicenses[$text])){
                $licenseData = $wgIsekaiLicenses[$text];
                if(isset($licenseData['namemsg'])){
                    $licenseData['name'] = wfMessage($licenseData['namemsg'])->text();
                }
                if(isset($licenseData['page'])){
                    $licenseData['url'] = Title::newFromText($licenseData['page'])->getLocalUrl();
                }
                $allowedParams = ['target', 'id', 'class', 'style'];
                $finalParams = array_filter($params, function($key) use($allowedParams) {
                    return in_array($key, $allowedParams);
                }, ARRAY_FILTER_USE_KEY);

                return Html::element('a', array_merge($finalParams, [
                    'href' => $licenseData['url'],
                ]), $licenseData['name']);
            }
        }

        return '';
    }
}