# -*- coding: utf-8 -*-
#!/usr/bin/env python

import urllib2
import urllib
import serial
import time
import sys
import datetime

ser = serial.Serial(
    port='/dev/tty.usbmodem3d11',
    baudrate=9600,
    timeout=1
)

def main():
    try:
        host = 'http://thhy.sakura.ne.jp/php/recvThermoHygro.php'
        while True:
            if ser.inWaiting() > 0:
                data = ser.readline().rstrip()
                data_list = data.split(',')
                kind = data_list[0]
                no = data_list[1]
                thermo = float(data_list[2])
                hygro = float(data_list[3])
                date = datetime.datetime.today().strftime('%Y/%m/%d %H:%M:%S')
                
                print date, kind, no, thermo, hygro
                
                params = urllib.urlencode({
                    'kind' : kind,
                    'no' : no,
                    'thermo' : thermo,
                    'hygro' : hygro
                })
                
                # サーバに送信
                res = urllib2.urlopen(host, params)

                # 送信に失敗した場合
                if res.code != 200:
                    print res.code, res.msg
            
            time.sleep(0.1)
    except KeyboardInterrupt:
        print u'キーボード処理により停止します。'
    except urllib2.HTTPError, e:
        print e.code, e.msg
    except urllib2.URLError, e:
        print e.code, e.msg
    finally:
        if ser != None:
            ser.close()

if __name__ == '__main__':
    main()