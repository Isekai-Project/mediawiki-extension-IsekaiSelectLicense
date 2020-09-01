<?php
namespace Isekai\SelectLicense;

use Message;
use Title;

class Hooks {
    public static function onOutputPageBeforeHTML( \OutputPage &$out ){
        global $wgIsekaiLicenses, $wgIsekaiCustomLicense, $wgRightsPage, $wgRightsUrl, $wgRightsText, $wgRightsIcon, $wgFooterIcons;
        
        $configs = $out->getJsConfigVars();
        if(isset($configs['wgIsekaiLicense'])){
            $licenseName = $configs['wgIsekaiLicense'];
            if(isset($wgIsekaiLicenses[$licenseName])){
                $license = $wgIsekaiLicenses[$licenseName];
                if(isset($license['url'])){
                    $wgRightsUrl = $license['url'];
                }
                if(isset($license['page'])){
                    $wgRightsPage = $license['page'];
                    $license['url'] = Title::newFromText($wgRightsPage)->getLocalURL();
                }
                if(isset($license['name'])){
                    $wgRightsText = $license['name'];
                }
                if(isset($license['namemsg'])){
                    $wgRightsText = wfMessage($license['namemsg'])->text();
                }
                if(isset($license['icon'])){
                    $wgRightsIcon = $license['icon'];
                }
                $wgIsekaiCustomLicense = true;
                //设置页脚
                if ($wgRightsIcon || $wgRightsText) {
                    $wgFooterIcons['copyright']['copyright'] = [
                        'url' => $license['url'],
                        'src' => $wgRightsIcon,
                        'alt' => $wgRightsText,
                    ];
                    if(isset($license['iconset'])){
                        $wgFooterIcons['copyright']['copyright']['srcset'] = $license['iconset'];
                    }
                }
            }
        }

        //设置Header
		$out->addMeta('copyright', wfMessage('isekai-selectlicense-meta', $wgRightsText)->text());
    }

    public static function onSkinCopyrightFooter( Title $title, $type, &$msg, &$link, &$forContent){
        global $wgIsekaiCustomLicense;
        if($type != 'history'){
            if($wgIsekaiCustomLicense){
                $msg = 'isekai-selectlicense-copyright';
            }
        }
    }
}