# !/usr/bin/env python
# -*- coding: utf-8 -*-
import queue
import threading

Thread_id = 1
class myThread(threading.Thread):
    def __init__(self, q,dealFunc):
        global Thread_id
        threading.Thread.__init__(self)
        self.q = q
        self.dealFunc=dealFunc
        self.Thread_id = Thread_id
        Thread_id = Thread_id + 1
    def run(self):
        while True:
            try:
                task = self.q.get(block = True, timeout = 1) #不设置阻塞的话会一直去尝试获取资源
            except queue.Empty:
                print ('Thread' ,  self.Thread_id , 'end')
                break
            print ("Starting " , self.Thread_id)
            try:
                self.dealFunc(**task)
            except:
                pass
            self.q.task_done()
            print ("Ending " , self.Thread_id)

def startThread(startThreadNum,  fun, dataArry=[]):
    q = queue.Queue(len(dataArry))
        #向资源池里面放10个数用作测试
    for i in range(len(dataArry)):
        q.put(dataArry[i])
    for i in range(0, startThreadNum):
        worker = myThread(q,fun)
        worker.start()
    q.join() #等待所有的队列资源都用完
    print ("Exiting Main Thread")

def f(a):
    print ("ddddddd",a)

if __name__ == '__main__':
    startThread(3, f,[1,2,3,4,5])