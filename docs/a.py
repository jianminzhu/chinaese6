# !/usr/bin/env python
# -*- coding: utf-8 -*-
import threading
import time
import urllib2
# SELECT                "bmember"  as type, COUNT(*) AS bmember FROM bmember WHERE isDownPics=0
# UNION ALL SELECT      "memberby "  as type, COUNT(*) AS memberby  FROM memberby
# UNION ALL SELECT      "membercontact"  as type, COUNT(*) AS membercontact FROM membercontact
# UNION ALL SELECT      "memberlevel"  as type, COUNT(*) AS memberlevel FROM memberlevel
# UNION ALL SELECT      "member "  as type, COUNT(*) AS member  FROM member

# cd ~/tt
# nohup python a.py> output10.html 2>&1 &

def getHtml(url):
    header = {"User-Agent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.12; rv:48.0) Gecko/20100101 Firefox/48.0"}
    request = urllib2.Request(url=url, headers=header)  # 模拟浏览器进行访问
    response = urllib2.urlopen(request)
    text = response.read()
    return text


def spider():
    while True:
        html = getHtml("http://travelling.chinesecompanion.com/index.php/index/spby/pics?isShowPic=no&limit=1")
        print(html)
        time.sleep(1)


if __name__ == '__main__':
    t = []
    for index in range(10):
        t.append(threading.Thread(target=spider))

    for index in range(len(t)):
        t[index].start()

    for index in range(len(t)):
        t[index].join()
