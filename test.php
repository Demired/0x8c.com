<?php
exec("sudo git --work-tree=/webSite/0x8c.com pull -f origin master 2>&1",$res,$status);
var_dump($res);
var_dump($status);
