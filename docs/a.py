# !/usr/bin/env python
# -*- coding: utf-8 -*-
import time
import urllib.request


def spider(url):

    while True:
        fp = urllib.request.urlopen("http://travelling.chinesecompanion.com/index.php/index/spby/pics?isShowPic=no&limit=1")
        mybytes = fp.read()
        print(mybytes)
        print ("Start : %s" % time.ctime())
        time.sleep( 5 )
        print ("End : %s" % time.ctime())