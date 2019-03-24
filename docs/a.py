# !/usr/bin/env python
# -*- coding: utf-8 -*-
import queue
import threading
import urllib.request

import time

def spider(url):

    while True:
        fp = urllib.request.urlopen("http://travelling.chinesecompanion.com/index.php/index/spby/pics?isShowPic=no&limit=1")
        mybytes = fp.read()
        print(mybytes)
        print ("Start : %s" % time.ctime())
        time.sleep( 5 )
        print ("End : %s" % time.ctime())