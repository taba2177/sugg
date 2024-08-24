import time
import requests
from plyer import notification
import win32serviceutil
import win32service
import win32event
import logging
from win10toast import ToastNotifier
from win11toast import toast
toaster = ToastNotifier()

class ComplaintNotifierService(win32serviceutil.ServiceFramework):
    _svc_name_ = "ComplaintNotifierService"
    _svc_display_name_ = "Complaint Notifier Service"
    _svc_description_ = "Checks for unread complaints and notifies the user via Windows notifications."

    def __init__(self, args):
        win32serviceutil.ServiceFramework.__init__(self, args)
        self.hWaitStop = win32event.CreateEvent(None, 0, 0, None)
        self.stop_requested = False
        self.setup_logging()

    def setup_logging(self):
        logging.basicConfig(
            filename='C:\\ComplaintNotifierService.log',  # Change the path as needed
            level=logging.DEBUG,
            format='%(asctime)s - %(levelname)s - %(message)s',
        )
        logging.info('Service started.')

    def SvcStop(self):
        self.ReportServiceStatus(win32service.SERVICE_STOP_PENDING)
        win32event.SetEvent(self.hWaitStop)
        self.stop_requested = True
        logging.info('Service stop requested.')

    def SvcDoRun(self):
        last_count = 0

        while not self.stop_requested:
            try:
                headers = {
                    'Accept': 'application/json',
                    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'}
                response = requests.get('https://website-cb99bbb9.pkh.sga.mybluehost.me/api/complaints/count-unread', headers=headers)
                data = response.json()
                logging.debug(f'API Response: {data}')

                if data['count'] > last_count:
                    toast('شكاوى العملاء',f" لديك {data['count']}  شكوى جديدة ", image='https://4.bp.blogspot.com/-u-uyq3FEqeY/UkJLl773BHI/AAAAAAAAYPQ/7bY05EeF1oI/s800/cooking_toaster.png')
                #     toaster.show_toast(
                #     "شكاوي العملاء",
                #     f" لديك {data['count']}  شكوى جديدة ",
                #     duration=10,
                #     threaded=True
                # )
                    last_count = data['count']
                    logging.info(f'Notification sent. New count: {data["count"]}')

            except Exception as e:
                logging.error(f"Error: {e}")

            time.sleep(120)  # Check every 120 seconds
            self.ReportServiceStatus(win32service.SERVICE_RUNNING)

if __name__ == '__main__':
    win32serviceutil.HandleCommandLine(ComplaintNotifierService)

