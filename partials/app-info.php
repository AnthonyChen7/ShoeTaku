<?php
class AppInfo
{
    // property declaration
    public $appID = '1540489982923433';

    // method declaration
    public function getAppID() {
        return $this->appID;
    }
}

$appInfo = new AppInfo();
$appInfo>getAppID();

?>