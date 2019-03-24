# !/usr/bin/env python
# -*- coding: utf-8 -*-
import threading
import time
import urllib2


def getHtml(url):
    header = {"User-Agent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.12; rv:48.0) Gecko/20100101 Firefox/48.0"}
    request = urllib2.Request(url=url, headers=header)  # 模拟浏览器进行访问
    response = urllib2.urlopen(request)
    text = response.read()
    return text


def spider():
    while True:
        html = getHtml("http://travelling.chinesecompanion.com/index.php/index/spby/pics?isShowPic=no&limit=100")
        print(html)
        print ("Start : %s" % time.ctime())
        time.sleep(1)
        print ("End : %s" % time.ctime())


if __name__ == '__main__':
    t = []
    for index in range(10):
        t.append(threading.Thread(target=spider))

    for index in range(len(t)):
        t[index].start()

    for index in range(len(t)):
        t[index].join()
