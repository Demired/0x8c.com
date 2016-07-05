<?php
    $gitDir = "/webSite/0x8c.com";
    $remote = "origin master";
    exec("git --work-tree=$gitDir pull -f $remote");
